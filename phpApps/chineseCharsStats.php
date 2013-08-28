<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/ChineseCharsAccountant.class.php');


$smarty = SmartyManager::getSmarty();
$smarty->assign('chineseChars', true);
try{
	
//如果查询的不是某个特定的材料，那么显示所有汉字的物流状态信息
if(!isset($_GET['orderNumber'])){
	
	
	/**
	 * 材料代码的数组
	 *
	 * @var array
	 */
	 $materialCode = array();

	/**
	 * 船级的数组
	 *
	 * @var array
	 */
	 $shipsClassification = array();

	/**
	 * 材料的数组
	 *
	 * @var array
	 */
	 $material = array();

	/**
	 * 厚度的数组
	 *
	 * @var array
	 */
	 $thickness = array();

	/**
	 * 宽度的数组
	 *
	 * @var array
	 */
	 $width = array();

	/**
	 * 长度的数组
	 *
	 * @var array
	 */
	 $length = array();

	/**
	 * 未入库数量的数组
	 *
	 * @var array
	 */
	 $unRukuCount = array();

	/**
	 * 库中的数量的数组
	 *
	 * @var array
	 */
	 $kuzhongCount = array();

	/**
	 * 直接销售的数量的数组
	 *
	 * @var array
	 */
	 $soldCount = array();

	/**
	 * 总量的数组
	 *
	 * @var array
	 */
	 $sumCount = array();


	/**
	 * 订单号
	 *
	 * @var array
	 */
	 $orderNumber = array();

	/**
	 * 订单子项号
	 *
	 * @var array
	 */
	 $orderSubitemNumber = array();

	/**
	 * 受订单价
	 *
	 * @var unknown_type
	 */
	$unitPrice = array();
	
	/**
	 * 批号
	 *
	 * @var unknown_type
	 */
	$batchNumber = array();
	
	/**
	 * 购单号
	 *
	 * @var unknown_type
	 */
	$purchaseNumber = array();
	 
	/**
	 * 目的地
	 *
	 * @var unknown_type
	 */
	$destination = array();
	
	/**
	 * 库存地
	 *
	 * @var unknown_type
	 */
	$storePlace = array();
	
	/**
	 * 结算批号
	 *
	 * @var unknown_type
	 */
	$checkoutBatch = array();
	
	/**
	 * 证书号
	 *
	 * @var unknown_type
	 */
	$certificateNumber = array();

	/**
	 * 物料号
	 */
	$materialNumber = array();
	
	/**
	 * 发货批次
	 *
	 * @var unknown_type
	 */
	$consignmentBatch = array();
	
	/**
	 * 文件名
	 *
	 * @var array
	 */
	 $filename = array();
	
	/**
	 * id最原始使用第一个select子查询得到的，因此他的意义就跟这个子查询有很大关系
	 * 目前，也就是我添加这个id的意义，是想让他表示某个东西，相同的东西在现实的时候id相同
	 *
	 * @var int
	 */
	 $id = array();
	
	$sql = "select materialCode, orderNumber, orderSubitemNumber from sss_main";
	$result = DB::query($sql);
	if($result == false){
		return;
	}
	$whereArr = array();
	while($row = $result->fetch_assoc()){
		$mc = $row['materialCode'];	
		if(preg_match("/(\m*[\x80-\xff]+\m*)+/",$mc) && $mc != "加片" && $mc != "舾装" && $mc != "扁钢")	{
			array_push($whereArr, "(materialCode = '{$mc}' and orderNumber = '{$row['orderNumber']}' and orderSubitemNumber = '{$row['orderSubitemNumber']}')");	
		}
	}
	$where = join(" or ", $whereArr);
	$sql = "select materialCode, shipsClassification, material, thickness, width, length,
		sumCount, (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
		(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount,
		(coalesce(chukuCount, 0) + coalesce(directCount, 0)) as soldCount,
		 orderNumber, orderSubitemNumber,unitPrice, batchNumber, purchaseNumber, destination, storePlace, filename,
		 certificateNumber, checkoutBatch, materialNumber, consignmentBatch
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount, filename,
			certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_main
			where $where
			group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库' and ($where)
			group by materialCode, orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(halfChukuCount) as chukuCount
			from
				(
					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fache
					where phase = '出库' and ({$where})
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fachuan
					where ($where)
					group by materialCode, orderNumber, orderSubitemNumber
					)
				)as nativeChukuCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as directCount
			from sss_fache
			where phase='销售' and ($where)
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		order by materialCode, orderNumber, orderSubitemNumber, thickness, width, length;
";
	$result = DB::query($sql);
	if($result == false){
		return;
	}
while($row = $result->fetch_assoc()){
			
			array_push($materialCode, $row['materialCode']);
			array_push($shipsClassification, $row['shipsClassification']);
			array_push($material, $row['material']);
			array_push($thickness, $row['thickness']);
			array_push($width, $row['width']);
			array_push($length, $row['length']);
			array_push($kuzhongCount, $row['kuzhongCount']);
			array_push($unRukuCount, $row['unRukuCount']);
			array_push($soldCount, $row['soldCount']);
			array_push($sumCount, $row['sumCount']);
			array_push($orderNumber, $row['orderNumber']);
			array_push($orderSubitemNumber, $row['orderSubitemNumber']);
			array_push($unitPrice,$row['unitPrice']);
			array_push($batchNumber,$row['batchNumber']);
			array_push($purchaseNumber,$row['purchaseNumber']);
			array_push($destination,$row['destination']);
			array_push($storePlace,$row['storePlace']);
			array_push($certificateNumber, $row['certificateNumber']);
			array_push($checkoutBatch,$row['checkoutBatch']);
			array_push($materialNumber,$row['materialNumber']);
			array_push($consignmentBatch,$row['consignmentBatch']);
}
	$smarty->assign( array(
		'materialCode' => $materialCode,
		'shipsClassification' => $shipsClassification,
		'material' => $material,
		'thickness' => $thickness,
		'width' => $width,
		'length' => $length,
		'sumCount' => $sumCount,
		'kuzhong' => $kuzhongCount,
		'unRuku' => $unRukuCount,
		'sold' => $soldCount,
		'orderNumber' => $orderNumber,
		'orderSubitemNumber' => $orderSubitemNumber,
		'unitPrice' => $unitPrice,
		'batchNumber' => $batchNumber,
		'purchaseNumber' => $purchaseNumber,
		'destination' => $destination,
		'storePlace' => $storePlace,
		'certificateNumber' => $certificateNumber,
		'checkoutBatch' => $checkoutBatch,
		'materialNumber' => $materialNumber,
		'consignmentBatch' => $consignmentBatch,
		));
	$smarty->display('chineseCharsStats.html');
}else{
	//首先查询总表，总表没有什么特殊情况，查询完后将结果中的count和weight全部相加一次作为小结即可完成
	$mc = Filter::forDBInsertion($_GET['materialCode']);
	$orderNumber = $_GET['orderNumber'];
	$orderSubitemNumber = $_GET['orderSubitemNumber'];

	$query = "select id,sequenceNumber, manufactory, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_main
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'
		order by uploadTime";
	DB::query($query);

	if(DB::num_rows() > 0){
		$sequenceNumbers = array();
		$manufactorys = array();
		$materialCodes = array();
		$shipsClassifications = array();
		$materials = array();
		$thicknesses = array();
		$widths = array();
		$lengths = array();
		$counts = array();
		$unitWeights = array();
		$weights = array();
		$remarks = array();
		$uploadTimes = array();
		$filenames = array();
		$orderNumbers = array();
		$orderSubitemNumbers = array();
		$unitPrices = array();
		$batchNumbers = array();
		$purchaseNumbers = array();
		$destinations = array();
		$storePlaces = array();
		$certificateNumbers = array();
		$checkoutBatchs = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
		$ids = array();

		$result = DB::getResult();

		while($row = $result->fetch_assoc()){
			array_push($sequenceNumbers, $row['sequenceNumber']);
			array_push($manufactorys, $row['manufactory']);
			array_push($materialCodes, $mc);
			array_push($shipsClassifications, $row['shipsClassification']);
			array_push($materials, $row['material']);
			array_push($thicknesses, $row['thickness']);
			array_push($widths, $row['width']);
			array_push($lengths, $row['length']);
			array_push($counts, $row['count']);
			array_push($unitWeights, $row['unitWeight']);
			array_push($weights, $row['weight']);
			if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
			}
			if(!empty($row['remark3'])){
					$row['remark1'] .= '<br />3. '.$row['remark3'];
			}
			if(!empty($row['remark4'])){
					$row['remark1'] .= '<br />4. '.$row['remark4'];
			}
			if(!empty($row['remark5'])){
					$row['remark1'] .= '<br />5. '.$row['remark5'];
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
			array_push($certificateNumbers,$row['certificateNumber']);
			array_push($checkoutBatchs,$row['checkoutBatch']);
			array_push($materialNumbers,$row['materialNumber']);
			array_push($consignmentBatchs,$row['consignmentBatch']);
			array_push($ids, $row['id']);
		}

		$smarty->assign(array('main_sequenceNumber' => $sequenceNumbers,
					'main_manufactory' => $manufactorys,
					'main_materialCode' => $materialCodes,
					'main_shipsClassification' => $shipsClassifications,
					'main_material' => $materials,
					'main_thickness' => $thicknesses,
					'main_width' => $widths,
					'main_length' => $lengths,
					'main_count' => $counts,
					'main_unitWeight' => $unitWeights,
					'main_weight' => $weights,
					'main_remark' => $remarks,
					'main_uploadTime' => $uploadTimes,
					'main_filename' => $filenames,
					'main_orderNumber' => $orderNumbers,
					'main_orderSubitemNumber' => $orderSubitemNumbers,
					'main_unitPrice' => $unitPrices,
					'main_batchNumber' => $batchNumbers,
		            'main_purchaseNumber' => $purchaseNumbers,
					'main_destination' => $destinations,
					'main_storePlace' => $storePlaces,
					'main_certificateNumber' => $certificateNumbers,
					'main_checkoutBatch' => $checkoutBatchs,
					'main_materialNumber' => $materialNumbers,
					'main_consignmentBatch' => $consignmentBatchs,
					'main_id' => $ids
				));
		$planSumCount = 0;
		$planSumWeight = 0;
		foreach($counts as $count){
			$planSumCount += intval($count);
		}
		foreach($weights as $weight){
			$planSumWeight += $weight;
		}

		$smarty->assign('planSumCount', $planSumCount);
		$smarty->assign('planSumWeight', $planSumWeight);
		$smarty->assign('planRowspan', DB::num_rows() + 2);
	}

	//然后是对于入库的查询，也是简简单单地只需要查询那些phase为"入库"的发车的项目，然后进行小结

	$query = "select id, facheDate, facheNumber, materialCode, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_fache
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'
			and phase = '入库'
		order by uploadTime";
	DB::query($query);

	if(DB::num_rows() > 0){
		$facheDates = array();
		$facheNumbers = array();
		$materialCodes = array();
		$shipsClassifications = array();
		$materials = array();
		$thicknesses = array();
		$widths = array();
		$lengths = array();
		$counts = array();
		$unitWeights = array();
		$weights = array();
		$remarks = array();
		$uploadTimes = array();
		$filenames = array();
		$phases = array();
		$orderNumbers = array();
		$orderSubitemNumbers = array();
		$unitPrices = array();
		$batchNumbers = array();
		$purchaseNumbers = array();
		$destinations = array();
		$storePlaces = array();
		$certificateNumbers = array();
		$checkoutBatchs = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
		$ids = array();

		$result = DB::getResult();

		while($row = $result->fetch_assoc()){
			array_push($facheDates, $row['facheDate']);
			array_push($facheNumbers, $row['facheNumber']);
			array_push($materialCodes, $mc);
			array_push($shipsClassifications, $row['shipsClassification']);
			array_push($materials, $row['material']);
			array_push($thicknesses, $row['thickness']);
			array_push($widths, $row['width']);
			array_push($lengths, $row['length']);
			array_push($counts, $row['count']);
			array_push($unitWeights, $row['unitWeight']);
			array_push($weights, $row['weight']);
			if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
			}
			if(!empty($row['remark3'])){
					$row['remark1'] .= '<br />3. '.$row['remark3'];
			}
			if(!empty($row['remark4'])){
					$row['remark1'] .= '<br />4. '.$row['remark4'];
			}
			if(!empty($row['remark5'])){
					$row['remark1'] .= '<br />5. '.$row['remark5'];
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($phases, $row['phase']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
			array_push($certificateNumbers,$row['certificateNumber']);
			array_push($checkoutBatchs,$row['checkoutBatch']);
			array_push($materialNumbers, $row['materialNumber']);
			array_push($consignmentBatchs, $row['consignmentBatch']);
			array_push($ids, $row['id']);
		}
		$inSumCount = 0;
		$inSumWeight = 0;
		foreach($counts as $count){
			$inSumCount += $count;
		}
		foreach($weights as $weight){
			$inSumWeight += $weight;
		}
		$smarty->assign(array('inFache_facheDate' => $facheDates,
					'inFache_facheNumber' => $facheNumbers,
					'inFache_materialCode' => $materialCodes,
					'inFache_shipsClassification' => $shipsClassifications,
					'inFache_material' => $materials,
					'inFache_thickness' => $thicknesses,
					'inFache_width' => $widths,
					'inFache_length' => $lengths,
					'inFache_count' => $counts,
					'inFache_unitWeight' => $unitWeights,
					'inFache_weight' => $weights,
					'inFache_remark' => $remarks,
					'inFache_uploadTime' => $uploadTimes,
					'inFache_filename' => $filenames,
					'inFache_phase' => $phases,
					'inFache_orderNumber' => $orderNumbers,
					'inFache_orderSubitemNumber' => $orderSubitemNumbers,
					'inFache_unitPrice' => $unitPrices,
					'inFache_batchNumber' => $batchNumbers,
		            'inFache_purchaseNumber' => $purchaseNumbers,
					'inFache_destination' => $destinations,
					'inFache_storePlace' => $storePlaces,
					'inFache_certificateNumber' => $certificateNumbers,
					'inFache_checkoutBatch' => $checkoutBatchs,
					'inFache_materialNumber' => $materialNumbers,
					'inFache_consignmentBatch' => $consignmentBatchs,
					'inFache_id' => $ids
				));
		$smarty->assign('inSumWeight', $inSumWeight);
		$smarty->assign('inSumCount', $inSumCount);
		$smarty->assign('rukuRowspan', DB::num_rows() + 2);
	}
	//对于出库的， 需要查询发船中的所有和发车中的标志为“出库”的信息，然后进行相加求出小结
	$query = "select id, facheDate, facheNumber, materialCode, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_fache
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'
			and phase = '出库'
		order by uploadTime";
	DB::query($query);

	$outSumCount = 0;
	$outSumWeight = 0;
	$chukuRowspan = 2;

	$haveInFache = false;
	$haveFachuan = false;

	if(DB::num_rows() > 0){
		$haveInFache = true;

		$facheDates = array();
		$facheNumbers = array();
		$materialCodes = array();
		$shipsClassifications = array();
		$materials = array();
		$thicknesses = array();
		$widths = array();
		$lengths = array();
		$counts = array();
		$unitWeights = array();
		$weights = array();
		$remarks = array();
		$uploadTimes = array();
		$filenames = array();
		$phases = array();
		$orderNumbers = array();
		$orderSubitemNumbers = array();
		$unitPrices = array();
		$batchNumbers = array();
		$purchaseNumbers = array();
		$destinations = array();
		$storePlaces = array();
		$certificateNumbers = array();
		$checkoutBatchs = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
		$ids = array();

		$result = DB::getResult();

		while($row = $result->fetch_assoc()){
			array_push($facheDates, $row['facheDate']);
			array_push($facheNumbers, $row['facheNumber']);
			array_push($materialCodes, $mc);
			array_push($shipsClassifications, $row['shipsClassification']);
			array_push($materials, $row['material']);
			array_push($thicknesses, $row['thickness']);
			array_push($widths, $row['width']);
			array_push($lengths, $row['length']);
			array_push($counts, $row['count']);
			array_push($unitWeights, $row['unitWeight']);
			array_push($weights, $row['weight']);
			if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
			}
			if(!empty($row['remark3'])){
					$row['remark1'] .= '<br />3. '.$row['remark3'];
			}
			if(!empty($row['remark4'])){
					$row['remark1'] .= '<br />4. '.$row['remark4'];
			}
			if(!empty($row['remark5'])){
					$row['remark1'] .= '<br />5. '.$row['remark5'];
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($phases, $row['phase']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
			array_push($certificateNumbers,$row['certificateNumber']);
			array_push($checkoutBatchs,$row['checkoutBatch']);
			array_push($materialNumbers,$row['$materialNumber']);
			array_push($consignmentBatchs,$row['consignmentBatch']);
			array_push($ids, $row['id']);
		}
		foreach($counts as $count){
			$outSumCount += $count;
		}
		foreach($weights as $weight){
			$outSumWeight += $weight;
		}
		$chukuRowspan += DB::num_rows();
		$smarty->assign(array('outFache_facheDate' => $facheDates,
					'outFache_facheNumber' => $facheNumbers,
					'outFache_materialCode' => $materialCodes,
					'outFache_shipsClassification' => $shipsClassifications,
					'outFache_material' => $materials,
					'outFache_thickness' => $thicknesses,
					'outFache_width' => $widths,
					'outFache_length' => $lengths,
					'outFache_count' => $counts,
					'outFache_unitWeight' => $unitWeights,
					'outFache_weight' => $weights,
					'outFache_remark' => $remarks,
					'outFache_uploadTime' => $uploadTimes,
					'outFache_filename' => $filenames,
					'outFache_phase' => $phases,
					'outFache_orderNumber' => $orderNumbers,
					'outFache_orderSubitemNumber' => $orderSubitemNumbers,
					'outFache_unitPrice' => $unitPrices,
					'outFache_batchNumber' => $batchNumbers,
		            'outFache_purchaseNumber' => $purchaseNumbers,
					'outFache_destination' => $destinations,
					'outFache_storePlace' => $storePlaces,
					'outFache_certificateNumber' => $certificateNumbers,
					'outFache_checkoutBatch' => $checkoutBatchs,
					'outFache_materialNumber' => $materialNumbers,
					'outFache_consignmentBatch' => $consignmentBatchs,
					'outFache_id' => $ids
				));

	}

	$query = "select id, fachuanDate, fachuanNumber, materialCode, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_fachuan
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'
		order by uploadTime";
	DB::query($query);
	if(DB::num_rows() > 0){
		$haveFachuan = true;

		$fachuanDates = array();
		$fachuanNumbers = array();
		$materialCodes = array();
		$shipsClassifications = array();
		$materials = array();
		$thicknesses = array();
		$widths = array();
		$lengths = array();
		$counts = array();
		$unitWeights = array();
		$weights = array();
		$remarks = array();
		$uploadTimes = array();
		$filenames = array();
		$orderNumbers = array();
		$orderSubitemNumbers = array();
		$unitPrices = array();
		$batchNumbers = array();
		$purchaseNumbers = array();
		$destinations = array();
		$storePlaces = array();
		$certificateNumbers = array();
		$checkoutBatchs = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
		$ids = array();

		$result = DB::getResult();

		while($row = $result->fetch_assoc()){
			array_push($fachuanDates, $row['fachuanDate']);
			array_push($fachuanNumbers, $row['fachuanNumber']);
			array_push($materialCodes, $mc);
			array_push($shipsClassifications, $row['shipsClassification']);
			array_push($materials, $row['material']);
			array_push($thicknesses, $row['thickness']);
			array_push($widths, $row['width']);
			array_push($lengths, $row['length']);
			array_push($counts, $row['count']);
			array_push($unitWeights, $row['unitWeight']);
			array_push($weights, $row['weight']);
			if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
			}
			if(!empty($row['remark3'])){
					$row['remark1'] .= '<br />3. '.$row['remark3'];
			}
			if(!empty($row['remark4'])){
					$row['remark1'] .= '<br />4. '.$row['remark4'];
			}
			if(!empty($row['remark5'])){
					$row['remark1'] .= '<br />5. '.$row['remark5'];
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
			array_push($certificateNumbers, $row['certificateNumber']);
			array_push($checkoutBatchs,$row['checkoutBatch']);
			array_push($materialNumbers,$row['materialNumber']);
			array_push($consignmentBatchs,$row['consignmentBatch']);
			array_push($ids, $row['id']);
		}

		$smarty->assign(array('fachuan_fachuanDate' => $fachuanDates,
					'fachuan_fachuanNumber' => $fachuanNumbers,
					'fachuan_materialCode' => $materialCodes,
					'fachuan_shipsClassification' => $shipsClassifications,
					'fachuan_material' => $materials,
					'fachuan_thickness' => $thicknesses,
					'fachuan_width' => $widths,
					'fachuan_length' => $lengths,
					'fachuan_count' => $counts,
					'fachuan_unitWeight' => $unitWeights,
					'fachuan_weight' => $weights,
					'fachuan_remark' => $remarks,
					'fachuan_uploadTime' => $uploadTimes,
					'fachuan_filename' => $filenames,
					'fachuan_orderNumber' => $orderNumbers,
					'fachuan_orderSubitemNumber' => $orderSubitemNumbers,
					'fachuan_unitPrice' => $unitPrices,
					'fachuan_batchNumber' => $batchNumbers,
		            'fachuan_purchaseNumber' => $purchaseNumbers,
					'fachuan_destination' => $destinations,
					'fachuan_storePlace' => $storePlaces,
					'fachuan_certificateNumber' => $certificateNumbers,
					'fachuan_checkoutBatch' => $checkoutBatchs,
					'fachuan_materialNumber' => $materialNumbers,
					'fachuan_consignmentBatch' => $consignmentBatchs,
					'fachuan_id' => $ids
				));
		$chukuRowspan += DB::num_rows();
		foreach($counts as $count){
			$outSumCount += $count;
		}
		foreach($weights as $weight){
			$outSumWeight += $weight;
		}
	}

	if($haveFachuan || $haveInFache){
		$smarty->assign('outSumCount', $outSumCount);
		$smarty->assign('outSumWeight', $outSumWeight);
		$smarty->assign('chukuRowspan', $chukuRowspan);
	}
	//最后是直接销售的，跟入库的差不多

	$query = "select id, facheDate, facheNumber, materialCode, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_fache
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'
			and phase = '销售'
		order by uploadTime";
	DB::query($query);

	if(DB::num_rows() > 0){
		$facheDates = array();
		$facheNumbers = array();
		$materialCodes = array();
		$shipsClassifications = array();
		$materials = array();
		$thicknesses = array();
		$widths = array();
		$lengths = array();
		$counts = array();
		$unitWeights = array();
		$weights = array();
		$remarks = array();
		$uploadTimes = array();
		$filenames = array();
		$phases = array();
		$orderNumbers = array();
		$orderSubitemNumbers = array();
		$unitPrices = array();
		$batchNumbers = array();
		$purchaseNumbers = array();
		$destinations = array();
		$storePlaces = array();
		$certificateNumbers = array();
		$checkoutBatchs = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
		$ids = array();

		$result = DB::getResult();

		while($row = $result->fetch_assoc()){
			array_push($facheDates, $row['facheDate']);
			array_push($facheNumbers, $row['facheNumber']);
			array_push($materialCodes, $mc);
			array_push($shipsClassifications, $row['shipsClassification']);
			array_push($materials, $row['material']);
			array_push($thicknesses, $row['thickness']);
			array_push($widths, $row['width']);
			array_push($lengths, $row['length']);
			array_push($counts, $row['count']);
			array_push($unitWeights, $row['unitWeight']);
			array_push($weights, $row['weight']);
			if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
			}
			if(!empty($row['remark3'])){
					$row['remark1'] .= '<br />3. '.$row['remark3'];
			}
			if(!empty($row['remark4'])){
					$row['remark1'] .= '<br />4. '.$row['remark4'];
			}
			if(!empty($row['remark5'])){
					$row['remark1'] .= '<br />5. '.$row['remark5'];
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($phases, $row['phase']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
			array_push($certificateNumbers,$row['certificateNumber']);
			array_push($checkoutBatchs,$row['checkoutBatch']);
			array_push($materialNumbers,$row['materialNumber']);
			array_push($consignmentBatchs,$row['consignmentBatch']);
			array_push($ids, $row['id']);
		}
		$directSumCount = 0;
		$directSumWeight = 0;
		foreach($counts as $count){
			$directSumCount += $count;
		}
		foreach($weights as $weight){
			$directSumWeight += $weight;
		}
		$smarty->assign(array('directFache_facheDate' => $facheDates,
					'directFache_facheNumber' => $facheNumbers,
					'directFache_materialCode' => $materialCodes,
					'directFache_shipsClassification' => $shipsClassifications,
					'directFache_material' => $materials,
					'directFache_thickness' => $thicknesses,
					'directFache_width' => $widths,
					'directFache_length' => $lengths,
					'directFache_count' => $counts,
					'directFache_unitWeight' => $unitWeights,
					'directFache_weight' => $weights,
					'directFache_remark' => $remarks,
					'directFache_uploadTime' => $uploadTimes,
					'directFache_filename' => $filenames,
					'directFache_phase' => $phases,
					'directFache_orderNumber' => $orderNumbers,
					'directFache_orderSubitemNumber' => $orderSubitemNumbers,
					'directFache_unitPrice' => $unitPrices,
					'directFache_batchNumber' => $batchNumbers,
		            'directFache_purchaseNumber' => $purchaseNumbers,
					'directFache_destination' => $destinations,
					'directFache_storePlace' => $storePlaces,
					'directFache_certificateNumber' => $certificateNumbers,
					'directFache_checkoutBatch' => $checkoutBatchs,
					'directFache_materialNumber' => $materialNumbers,
					'directFache_consignmentBatch' => $consignmentBatchs,
					'directFache_id' => $ids
				));
		$smarty->assign('directSumWeight', $directSumWeight);
		$smarty->assign('directSumCount', $directSumCount);
		$smarty->assign('directRowspan', DB::num_rows() + 2);
	}
	$smarty->display('multipleTable.html');
}
}catch(Exception $e){
	$smarty->assign('errTitle', '出现错误');
	$smarty->assign('errMsg', '数据库查询和运算时出现错误'.$e);
	$smarty->display('error.html');
}
?>