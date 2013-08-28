<?php

require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SimpleTableExporter.class.php');

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$filename = '高级搜索'.'_'.date('YmdHis').'.xls';
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
$faTable = true;
if(!empty($_GET['faDateFrom']) or !empty($_GET['faDateTo']) or !empty($_GET['faNumber'])){
	$mainTable = false;
}

if(!empty($_GET['sequenceNumber']) or !empty($_GET['manufactory'])){
	$faTable = false;
}


if($mainTable){
	$mainWheres = $wheres;
	
	if(!empty($_GET['sequenceNumber'])){
		array_push($mainWheres, "sequenceNumber like '%{$_GET['sequenceNumber']}%'");
	}
	
	if(!empty($_GET['manufactory'])){
		array_push($mainWheres, "manufactory like '%{$_GET['manufactory']}%'");
	}
	
	$sum = true;
	
	$whereStr = join(' and ', $mainWheres);
	//var_dump($whereStr);
	
	if(count($mainWheres)==0){
		$query = "select id, sequenceNumber, manufactory, materialCode, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber, checkoutBatch, certificateNumber, materialNumber, consignmentBatch,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_main";
	}else{
		$query = "select id, sequenceNumber, manufactory, materialCode, shipsClassification, material,
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber, checkoutBatch, certificateNumber, materialNumber, consignmentBatch,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_main where $whereStr";
	}
	DB::query($query);
	
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
	$checkoutBatchs = array();
	$certificateNumbers = array();
	$materialNumbers = array();
	$consignmentBatchs = array();
	$phases = array();//总表本来没有阶段单元格，但是为了匹配发车sheet中的阶段，故增加该列
	
	$result = DB::getResult();
	
	$rukuColumn = 0.0;
	$sumCount = 0;
	
	while(($row = $result->fetch_assoc())!=NULL){
		array_push($sequenceNumbers, $row['sequenceNumber']);
		array_push($manufactorys, $row['manufactory']);
		array_push($materialCodes, $row['materialCode']);
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
		array_push($unitPrices, $row['unitPrice']);
		array_push($batchNumbers, $row['batchNumber']);
		array_push($purchaseNumbers, $row['purchaseNumber']);		
		array_push($destinations, $row['destination']);
		array_push($storePlaces, $row['storePlace']);
		array_push($checkoutBatchs, $row['checkoutBatch']);
		array_push($certificateNumbers, $row['certificateNumber']);
		array_push($consignmentBatchs, $row['consignmentBatch']);
		array_push($materialNumbers, $row['materialNumber']);
		array_push($phases,'总表');
		$rukuColumn += $row['weight'];
		$sumCount += $row['count'];
	}
	
	if($sum){
		array_push($sequenceNumbers, "<<<总计>>>");
		array_push($weights, $rukuColumn);
		array_push($counts, $sumCount);
	}
	
	$data = array($sequenceNumbers, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers,
				$unitPrices, $batchNumbers, $materialNumbers, $purchaseNumbers, $destinations, $storePlaces,$certificateNumbers,$checkoutBatchs, $consignmentBatchs,$phases);
	$exp->insertMainData($data);
			
}

if($faTable){
	$facheWheres = $wheres;

	if(!empty($_GET['ruku']) || !empty($_GET['chuku']) || !empty($_GET['sale']) ){
		$facheWheres = $wheres;
		if(!empty($_GET['faNumber'])){
			array_push($facheWheres, "facheNumber like '%{$_GET['faNumber']}%' ");
		}

		
		if(!empty($_GET['faDateFrom']) || Verifier::isTIME($_GET['faDateFrom'])){
			$time = date('Y/m/d', strtotime($_GET['faDateFrom']));
			array_push($facheWheres, "facheDate >= '$time'");
		}
	
		if(!empty($_GET['faDateTo']) || Verifier::isTIME($_GET['faDateTo'])){
			$time = date('Y/m/d', strtotime($_GET['faDateTo']));
			array_push($facheWheres, "facheDate <= '$time'");
		}
	
		
		$facheWhereStr = join(' and ', $facheWheres);
		
		$phaseArray = array();
		if(!empty($_GET['ruku'])){
			array_push($phaseArray,"phase = '{$_GET['ruku']}'");
		}
		if(!empty($_GET['chuku'])){
		    array_push($phaseArray,"phase = '{$_GET['chuku']}'");
		}
		if(!empty($_GET['sale'])){
			array_push($phaseArray,"phase = '{$_GET['sale']}'");
		}
	
		$phaseStr = join(' or ',$phaseArray);
				
		$facheWhereStr .= ' and ('.$phaseStr.')';
		
	//var_dump($facheWhereStr);
	
	if(count($facheWheres)==0){
			$query = "select id, facheDate, facheNumber, materialCode, shipsClassification, material,
				thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
				remark1, remark2, remark3, remark4, remark5,
				uploadTime, filename, phase, orderNumber, orderSubitemNumber, certificateNumber, checkoutBatch, materialNumber, consignmentBatch,
				unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fache where $phaseStr";
		}else{
			$query = "select id, facheDate, facheNumber, materialCode, shipsClassification, material,
					thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
					remark1, remark2, remark3, remark4, remark5,
					uploadTime, filename, phase, orderNumber, orderSubitemNumber, certificateNumber, checkoutBatch, materialNumber, consignmentBatch,
					unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fache where $facheWhereStr";
		}
	DB::query($query);
	
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
	$checkoutBatchs = array();
	$certificateNumbers = array();
	$materialNumbers = array();
	$consignmentBatchs = array();
	$result = DB::getResult();
	
	while(($row = $result->fetch_assoc())!=NULL){
		array_push($facheDates, $row['facheDate']);
		array_push($facheNumbers, $row['facheNumber']);
		array_push($materialCodes, $row['materialCode']);
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
		array_push($unitPrices, $row['unitPrice']);
		array_push($batchNumbers, $row['batchNumber']);
		array_push($purchaseNumbers, $row['purchaseNumber']);		
		array_push($destinations, $row['destination']);
		array_push($storePlaces, $row['storePlace']);
		array_push($certificateNumbers, $row['certificateNumber']);
		array_push($checkoutBatchs, $row['checkoutBatch']);
		array_push($materialNumbers, $row['materialNumber']);
		array_push($consignmentBatchs, $row['consignmentBatch']);
	}
	$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers,
				$unitPrices, $batchNumbers, $materialNumbers, $purchaseNumbers, $destinations, $storePlaces,$certificateNumbers, $checkoutBatchs, $consignmentBatchs, $phases);
	$exp->insertFacheData($data);
	}
	
	if(!empty($_GET['chuku'])){
		$fachuanWheres = $wheres;
	
		if(!empty($_GET['faDateFrom']) && Verifier::isTIME($_GET['faDateFrom'])){
			$time = date('Y/m/d', strtotime($_GET['faDateFrom']));
			array_push($fachuanWheres, "fachuanDate >= '$time'");
			$mainTable = false;
		}
	
		if(!empty($_GET['faDateTo']) && Verifier::isTIME($_GET['faDateTo'])){
			$time = date('Y/m/d', strtotime($_GET['faDateTo']));
			array_push($fachuanWheres, "fachuanDate <= '$time'");
			$mainTable = false;
		}
		
		if(!empty($_GET['faNumber'])){
			array_push($fachuanWheres, "fachuanNumber like '%{$_GET['faNumber']}%' ");
		}
		
		$fachuanWhereStr = join(' and ', $fachuanWheres);
	//var_dump($fachuanWhereStr);

	if(count($fachuanWheres)!=0){
		$query = "select id, fachuanDate, fachuanNumber, materialCode, shipsClassification, material,
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber, certificateNumber, checkoutBatch, materialNumber, consignmentBatch,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fachuan where $fachuanWhereStr";
		}else{
			$query = "select id, fachuanDate, fachuanNumber, materialCode, shipsClassification, material,
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber, certificateNumber, checkoutBatch, materialNumber, consignmentBatch,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fachuan";
		}
	DB::query($query);
	
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
	$phases = array();
	
	$result = DB::getResult();
	
	while(($row = $result->fetch_assoc())!=NULL){
		array_push($fachuanDates, $row['fachuanDate']);
		array_push($fachuanNumbers, $row['fachuanNumber']);
		array_push($materialCodes, $row['materialCode']);
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
		array_push($unitPrices, $row['unitPrice']);
		array_push($batchNumbers, $row['batchNumber']);
		array_push($materialNumbers, $row['materialNumber']);
		array_push($purchaseNumbers, $row['purchaseNumber']);
		array_push($destinations, $row['destination']);
		array_push($storePlaces, $row['storePlace']);
		array_push($certificateNumbers, $row['certificateNumber']);
		array_push($checkoutBatchs, $row['checkoutBatch']);
		array_push($consignmentBatchs, $row['consignmentBatch']);
		array_push($phases,'发船');
	}

	$data = array($fachuanDates, $materialCodes, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers,
				$unitPrices, $batchNumbers, $materialNumbers, $purchaseNumbers, $destinations, $storePlaces,$certificateNumbers, $checkoutBatchs, $consignmentBatchs,$phases);
	$exp->insertFachuanData($data);
	}
}
$exp->export();
?>