<?php

require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SimpleTableExporter.class.php');

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$filename = '高级搜索-物流形式'.'_'.date('YmdHis').'.xls';
$exp = new SimpleTableExporter($filename);

if(isset($_GET['materialCode'])){
	$form['materialCode'] = Filter::forDBInsertion(trim($_GET['materialCode']));
}
if(isset($_GET['thicknessFrom'])){
	$form['thicknessFrom'] = trim($_GET['thicknessFrom']);
}
if(isset($_GET['thicknessTo'])){
	$form['thicknessTo'] = trim($_GET['thicknessTo']);
}
if(isset($_GET['widthFrom'])){
	$form['widthFrom'] = trim($_GET['widthFrom']);
}
if(isset($_GET['widthTo'])){
	$form['widthTo'] = trim($_GET['widthTo']);
}
if(isset($_GET['lengthFrom'])){
	$form['lengthFrom'] = trim($_GET['lengthFrom']);
}
if(isset($_GET['lengthTo'])){
	$form['lengthTo'] = trim($_GET['lengthTo']);
}
if(isset($_GET['uploadTimeFrom'])){
	$form['uploadTimeFrom'] = trim($_GET['uploadTimeFrom']);
}
if(isset($_GET['uploadTimeTo'])){
	$form['uploadTimeTo'] = trim($_GET['uploadTimeTo']);
}

if(isset($_GET['faDateFrom'])){
	$form['faDateFrom'] = trim($_GET['faDateFrom']);
}
if(isset($_GET['faDateTo'])){
	$form['faDateTo'] = trim($_GET['faDateTo']);
}

if(isset($_GET['faNumber'])){
	$form['faNumber'] = trim($_GET['faNumber']);
}

if(isset($_GET['shipsClassification'])){
	$form['shipsClassification'] = Filter::forDBInsertion(trim($_GET['shipsClassification']));
}
if(isset($_GET['material'])){
	$form['material'] = Filter::forDBInsertion(trim($_GET['material']));
}

if(isset($_GET['orderNumber'])){
	$form['orderNumber'] = Filter::forDBInsertion(trim($_GET['orderNumber']));
}

if(isset($_GET['orderSubitemNumber'])){
	$form['orderSubitemNumber'] = trim($_GET['orderSubitemNumber']);
}

if(isset($_GET['filename'])){
	$form['filename'] = Filter::forDBInsertion(trim($_GET['filename']));
}
if(isset($_GET['remark'])){
	$form['remark'] = Filter::forDBInsertion(trim($_GET['remark']));
}
if(isset($_GET['unitPrice'])){
	$_GET['unitPrice'] = trim($_GET['unitPrice']);
}
if(isset($_GET['batchNumber'])){
	$_GET['batchNumber'] = Filter::forDBInsertion(trim($_GET['batchNumber']));
}
if(isset($_GET['purchaseNumber'])){
	$_GET['purchaseNumber'] = Filter::forDBInsertion(trim($_GET['purchaseNumber']));
}
if(isset($_GET['destination'])){
	$_GET['destination'] = Filter::forDBInsertion(trim($_GET['destination']));
}
if(isset($_GET['storePlace'])){
	$_GET['storePlace'] = Filter::forDBInsertion(trim($_GET['storePlace']));
}
if(isset($_GET['sequenceNumber'])){
	$_GET['sequenceNumber'] = Filter::forDBInsertion(trim($_GET['sequenceNumber']));
}
if(isset($_GET['manufactory'])){
	$_GET['manufactory'] = Filter::forDBInsertion(trim($_GET['manufactory']));
}
if(isset($_GET['checkoutBatch'])){
	$_GET['checkoutBatch'] = Filter::forDBInsertion(trim($_GET['checkoutBatch']));
}
if(isset($_GET['certificateNumber'])){
	$_GET['certificateNumber'] = Filter::forDBInsertion(trim($_GET['certificateNumber']));
}
if(isset($_GET['materialNumber'])){
	$_GET['materialNumber'] = Filter::forDBInsertion(trim($_GET['materialNumber']));
}
if(isset($_GET['consignmentBatch'])){
	$_GET['consignmentBatch'] = Filter::forDBInsertion(trim($_GET['consignmentBatch']));
}
if(isset($_GET['ruku'])){
	$_GET['ruku'] = Filter::forDBInsertion(trim($_GET['ruku']));
}
if(isset($_GET['chuku'])){
	$_GET['chuku'] = Filter::forDBInsertion(trim($_GET['chuku']));
}
if(isset($_GET['sale'])){
	$_GET['sale'] = Filter::forDBInsertion(trim($_GET['sale']));
}
if(isset($_GET['main'])){
	$_GET['main'] = Filter::forDBInsertion(trim($_GET['main']));
}

if(isset($_GET['year'])){
	$_GET['year'] = Filter::forDBInsertion(trim($_GET['year']));
}

$wheres = array();

if(!empty($form['materialCode'])){
	array_push($wheres, "materialCode like '%{$form['materialCode']}%'");
}

if(!empty($form['thicknessFrom']) && Verifier::isNUMBER($form['thicknessFrom'])){
	$thicknessFrom = doubleval($form['thicknessFrom']);
	array_push($wheres, "thickness >= {$thicknessFrom}");
}

if(!empty($form['thicknessTo']) && Verifier::isNUMBER($form['thicknessTo'])){
	$thicknessTo = doubleval($form['thicknessTo']);
	array_push($wheres, "thickness <= {$thicknessTo}");
}

if(!empty($form['widthFrom']) && Verifier::isNUMBER($form['widthFrom'])){
	$widthFrom = doubleval($form['widthFrom']);
	array_push($wheres, "width >= {$widthFrom}");
}

if(!empty($form['widthTo']) && Verifier::isNUMBER($form['widthTo'])){
	$widthTo = doubleval($form['widthTo']);
	array_push($wheres, "width <= {$widthTo}");
}

if(!empty($form['lengthFrom']) && Verifier::isNUMBER($form['lengthFrom'])){
	$lengthFrom = doubleval($form['lengthFrom']);
	array_push($wheres, "length >= {$lengthFrom}");
}

if(!empty($form['lengthTo']) && Verifier::isNUMBER($form['lengthTo'])){
	$lengthTo = doubleval($form['lengthTo']);
	array_push($wheres, "length <= {$lengthTo}");
}

if(!empty($form['uploadTimeFrom']) && Verifier::isTIME($form['uploadTimeFrom'])){
	$time = date('Y/m/d H:i:s', strtotime($form['uploadTimeFrom']));
	array_push($wheres, "uploadTime >= '$time'");
}

if(!empty($form['uploadTimeTo']) && Verifier::isTIME($form['uploadTimeTo'])){
	$time = date('Y/m/d H:i:s', strtotime($form['uploadTimeTo']));
	array_push($wheres, "uploadTime <= '$time'");
}


if(!empty($form['shipsClassification'])){
	array_push($wheres, "shipsClassification = '{$form['shipsClassification']}'");
}

if(!empty($form['material'])){
	array_push($wheres, "material = '{$form['material']}'");
}

if(!empty($form['filename'])){
	array_push($wheres, "filename like '%{$form['filename']}%'");
}

if(!empty($form['remark'])){
	array_push($wheres, "(remark1 like '%{$form['remark']}%' or remark2 like '%{$form['remark']}%')");
}


if(!empty($_GET['orderNumber'])){
	array_push($wheres, "orderNumber like '%{$_GET['orderNumber']}%'");
}

if(!empty($_GET['orderSubitemNumber'])){
	array_push($wheres, "orderSubitemNumber = '{$_GET['orderSubitemNumber']}'");
}

if(!empty($_GET['unitPrice'])){
	array_push($wheres, "unitPrice = '{$_GET['unitPrice']}'");
}

if(!empty($_GET['batchNumber'])){
	array_push($wheres, "batchNumber like '%{$_GET['batchNumber']}%'");
}

if(!empty($_GET['purchaseNumber'])){
	array_push($wheres, "purchaseNumber like '%{$_GET['purchaseNumber']}%'");
}

if(!empty($_GET['destination'])){
	array_push($wheres, "destination = '{$_GET['destination']}'");
}

if(!empty($_GET['storePlace'])){
	array_push($wheres, "storePlace = '{$_GET['storePlace']}'");
}

if(!empty($_GET['checkoutBatch'])){
	array_push($wheres, "checkoutBatch like '%{$_GET['checkoutBatch']}%'");
}

if(!empty($_GET['certificateNumber'])){
	array_push($wheres, "certificateNumber like '%{$_GET['certificateNumber']}%'");
}

if(!empty($_GET['materialNumber'])){
	array_push($wheres, "materialNumber like '%{$_GET['materialNumber']}%'");
}

if(!empty($_GET['consignmentBatch'])){
	array_push($wheres, "consignmentBatch like '%{$_GET['consignmentBatch']}%'");
}

if(!empty($_GET['year'])){
	array_push($wheres,"filename like '%{$_GET['year']}%'");
}

if (empty($_GET['ruku']) and empty($_GET['chuku']) and empty($_GET['sale']) and empty($_GET['main'])){
	header('Location: advancedSearch.html');
}


$mainTable = true;

if(!empty($_GET['faDateFrom']) or !empty($_GET['faDateTo']) or !empty($_GET['faNumber'])){
	$mainTable = false;
}

if ($mainTable) {
	if (isset ( $_GET ['main'] )) {
		$mainWheres = $wheres;
		
		if (! empty ( $_GET ['sequenceNumber'] )) {
			array_push ( $mainWheres, "sequenceNumber like '%{$_GET['sequenceNumber']}%'" );
		}
		
		if (! empty ( $_GET ['manufactory'] )) {
			array_push ( $mainWheres, "manufactory like '%{$_GET['manufactory']}%'" );
		}
		
		$whereStr = join ( ' and ', $mainWheres );
		//	var_dump($whereStr);
		

		if (count ( $mainWheres ) == 0) {
			$query = "select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, thickness, width, length, 
		sumCount, coalesce(rukuCount, 0) as rukuCount, coalesce(directCount, 0) as directCount, coalesce(chukuCount, 0) as chukuCount, 
		unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename, 
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,certificateNumber, checkoutBatch, materialNumber, consignmentBatch
from
		(
			(
			select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, width, thickness, length, 
			unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount,certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_main";
		} else {
			$query = "select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, thickness, width, length, 
		sumCount, coalesce(rukuCount, 0) as rukuCount, coalesce(directCount, 0) as directCount, coalesce(chukuCount, 0) as chukuCount, 
		unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace,certificateNumber, checkoutBatch, materialNumber, consignmentBatch
from
		(
			(
			select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, width, thickness, length, 
			unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount,certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_main where $whereStr";
		}
		$query .= " group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库'
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
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fachuan
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
			where phase='销售'
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		order by materialCode, orderNumber, orderSubitemNumber, thickness, width, length;";
		DB::query ( $query );
	
		$sequenceNumbers = array ();
		$manufactorys = array ();
		$materialCodes = array ();
		$shipsClassifications = array ();
		$materials = array ();
		$thicknesses = array ();
		$widths = array ();
		$lengths = array ();
		$sumCounts = array ();
		$unRukuCounts = array ();
		$kuzhongCounts = array ();
		$soldCounts = array ();
		$unitWeights = array ();
		$weights = array ();
		$remarks = array ();
		$uploadTimes = array ();
		$filenames = array ();
		$orderNumbers = array ();
		$orderSubitemNumbers = array ();
		$unitPrices = array ();
		$batchNumbers = array ();
		$purchaseNumbers = array ();
		$destinations = array ();
		$storePlaces = array ();
		$certificateNumbers = array();
		$checkoutBatchs = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
	
	$result = DB::getResult();
	
	$i=0;
	$dates2 = array();
	$faNumbers2 = array();
	while ( ($row = $result->fetch_assoc ())!=NULL ) {
			
			$sold = $row ['chukuCount'] + $row ['directCount'];
			$kuzhong = $row ['rukuCount'] - $row ['chukuCount'];
			$unRuku = $row ['sumCount'] - $row ['directCount'] - $row ['rukuCount'];
			
			array_push ( $sequenceNumbers, $row ['sequenceNumber'] );
			array_push ( $manufactorys, $row ['manufactory'] );
			array_push ( $materialCodes, $row ['materialCode'] );
			array_push ( $shipsClassifications, $row ['shipsClassification'] );
			array_push ( $materials, $row ['material'] );
			array_push ( $thicknesses, $row ['thickness'] );
			array_push ( $widths, $row ['width'] );
			array_push ( $lengths, $row ['length'] );
			array_push ( $sumCounts, intval ( $row ['sumCount'] ) );
			array_push ( $unRukuCounts, $unRuku );
			array_push ( $kuzhongCounts, $kuzhong );
			array_push ( $soldCounts, $sold );
			array_push ( $unitWeights, $row ['unitWeight'] );
			array_push ( $weights, $row ['weight'] );
			if (! empty ( $row ['remark2'] )) {
				$row ['remark1'] = '1. ' . $row ['remark1'] . '<br />2. ' . $row ['remark2'];
			}
			if (! empty ( $row ['remark3'] )) {
				$row ['remark1'] .= '<br />3. ' . $row ['remark3'];
			}
			if (! empty ( $row ['remark4'] )) {
				$row ['remark1'] .= '<br />4. ' . $row ['remark4'];
			}
			if (! empty ( $row ['remark5'] )) {
				$row ['remark1'] .= '<br />5. ' . $row ['remark5'];
			}
			array_push ( $remarks, $row ['remark1'] );
			array_push ( $uploadTimes, $row ['uploadTime'] );
			array_push ( $filenames, $row ['filename'] );
			array_push ( $orderNumbers, $row ['orderNumber'] );
			array_push ( $orderSubitemNumbers, $row ['orderSubitemNumber'] );
			array_push ( $unitPrices, $row ['unitPrice'] );
			array_push ( $batchNumbers, $row ['batchNumber'] );
			array_push ( $purchaseNumbers, $row ['purchaseNumber'] );
			array_push ( $destinations, $row ['destination'] );
			array_push ( $storePlaces, $row ['storePlace'] );
			array_push ( $certificateNumbers, $row['certificateNumber']);
			array_push ( $checkoutBatchs, $row['checkoutBatch']);
			array_push ( $materialNumbers, $row['materialNumber']);
			array_push ( $consignmentBatchs, $row['consignmentBatch']);

			$dates1 = array();
			$faNumbers1 = array();
			$sql_fache = "select facheDate, facheNumber from sss_fache 
					where materialCode = '".$row['materialCode']."' and orderNumber = '".$row['orderNumber']."' and orderSubitemNumber = '".$row['orderSubitemNumber']."'";
			DB::query ( $sql_fache );
			$result1 = DB::getResult();
			while ( ($row1 = $result1->fetch_assoc ())!=NULL ){
				if (! empty ( $row1 ['facheDate'] )) {
					array_push($dates1, $row1['facheDate']);
				}
				if(! empty($row1['facheNumber'])){
					array_push($faNumbers1, $row1['facheNumber']);
				}
			}
			$sql_fachuan = "select fachuanDate, fachuanNumber from sss_fachuan 
					where materialCode = '".$row['materialCode']."' and orderNumber = '".$row['orderNumber']."' and orderSubitemNumber = '".$row['orderSubitemNumber']."'";
			DB::query ( $sql_fachuan );
			$result2 = DB::getResult();
			while ( ($row2 = $result2->fetch_assoc ())!=NULL ){
				if (! empty ( $row2 ['fachuanDate'] )) {
					array_push($dates1, $row2['fachuanDate']);
				}
				if(! empty($row2['fachuanNumber'])){
					array_push($faNumbers1, $row2['fachuanNumber']);
				}
			}
			$dates2[$i] = $dates1;
			$faNumbers2[$i] = $faNumbers1;
			$i++;
		}
		
		$dates1 = array();
		$faNumbers1 = array();
		foreach ($dates2 as $value2){
			$tempDates = "";
			foreach ($value2 as $value1){
				$tempDates .= $value1." ";
			}
			array_push($dates1,$tempDates);
			
		}
		foreach ($faNumbers2 as $value2){
			$tempFaNumber = "";
			foreach ($value2 as $value1){
				$tempFaNumber .= $value1." ";
			}
			array_push($faNumbers1,$tempFaNumber);
		}
	
	$data = array($sequenceNumbers, $dates1, $faNumbers1, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $sumCounts, $unRukuCounts, $kuzhongCounts, $soldCounts,
				$unitWeights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers,
				$unitPrices, $batchNumbers, $materialNumbers, $purchaseNumbers, $destinations, $storePlaces,$certificateNumbers, $checkoutBatchs, $consignmentBatchs);
	$exp->insertCustomData($data,
							 array('批次','日期','车号/船号','材料代码','生产商', '船级',
							 '材质', '厚', '宽', '长', '总量','未入库','库中','已销售',
							 '单重','备注','上传时间','文件名','订单号', '订单子项号',
							 '受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次'), 
							 'sheet1');
	$exp->export();
			
	}
}