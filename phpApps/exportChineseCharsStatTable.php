<?php

require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/ChineseCharsAccountant.class.php');

if(isset($_GET['showFilename'])){
	$showFilename = true;
}else{
	$showFilename = false;
}

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

	 $unitPrice = array();
	
	 $batchNumber = array();
	
	 $purchaseNumber = array();
	 
	 $destination = array();
	
	 $storePlace = array();
	 
	 $certificateNumber = array();
	 
	 $checkoutBatch = array();
	 
	 $materialNumber = array();
	 
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
			array_push($filename,$row['filename']);
			array_push($certificateNumber,$row['certificateNumber']);
			array_push($checkoutBatch,$row['checkoutBatch']);
			array_push($materialNumber,$row['materialNumber']);
			array_push($consignmentBatch,$row['consignmentBatch']);
}

	$remarks = array();
	$unRukus = $unRukuCount;
	$solds = $soldCount;
	$sumCounts = $sumCount;
	foreach($sumCounts as $key => $val){
		if($sumCounts[$key] == $solds[$key]){
			array_push($remarks, '完成');
		}else if($sumCounts[$key] < $solds[$key]){
			array_push($remarks, '错误：销售总数大于总量');
		}else if($unRukus[$key] < 0){
			array_push($remarks, '错误：入库数大于计划总量');
		}else{
			array_push($remarks, "");
		}
	}

	$data = array(
		'材料代码' => $materialCode,
		'船级' => $shipsClassification,
		'材质' => $material,
		'厚' => $thickness,
		'宽' => $width,
		'长' => $length,
		'总量' => $sumCount,
		'未入库' => $unRukuCount,
		'库中' => $kuzhongCount,
		'已销售' => $soldCount,
		'订单号' => $orderNumber,
		'订单子项号' => $orderSubitemNumber,
		'受订单价' => $unitPrice,
		'批号' => $batchNumber,
		'物料号' => $materialNumber,
		'购单号' => $purchaseNumber,
		'目的地' => $destination,
		'库存地' => $storePlace,
		'证书号' => $certificateNumber,
		'结算批号' => $checkoutBatch,
		'发货批次' => $consignmentBatch,
		'备注' => $remarks
	);
	
	if($showFilename){
		$data['文件名'] = $filename;
	}

	$filename = '汉字-物流状况-'.date('YmdHis').'.xls';
	generateHttpExcelByCols($data,$filename);
?>