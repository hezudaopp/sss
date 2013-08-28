<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$smarty = SmartyManager::getSmarty();
$smarty->assign('advancedSearch', true);

//以下是将GET获取的数据格式化：如去掉不必要的空格和tab键等等
if(isset($_GET['materialCode'])){
	$smarty->assign('materialCode', $_GET['materialCode']);
	$_GET['materialCode'] = Filter::forDBInsertion(trim($_GET['materialCode']));
}
if(isset($_GET['thicknessFrom'])){
	$smarty->assign('thicknessFrom', $_GET['thicknessFrom']);
	$_GET['thicknessFrom'] = trim($_GET['thicknessFrom']);
}
if(isset($_GET['thicknessTo'])){
	$smarty->assign('thicknessTo', $_GET['thicknessTo']);
	$_GET['thicknessTo'] = trim($_GET['thicknessTo']);
}
if(isset($_GET['widthFrom'])){
	$smarty->assign('widthFrom', $_GET['widthFrom']);
	$_GET['widthFrom'] = trim($_GET['widthFrom']);
}
if(isset($_GET['widthTo'])){
	$smarty->assign('widthTo', $_GET['widthTo']);
	$_GET['widthTo'] = trim($_GET['widthTo']);
}
if(isset($_GET['lengthFrom'])){
	$smarty->assign('lengthFrom', $_GET['lengthFrom']);
	$_GET['lengthFrom'] = trim($_GET['lengthFrom']);
}
if(isset($_GET['lengthTo'])){
	$smarty->assign('lengthTo', $_GET['lengthTo']);
	$_GET['lengthTo'] = trim($_GET['lengthTo']);
}
if(isset($_GET['uploadTimeFrom'])){
	$smarty->assign('uploadTimeFrom', $_GET['uploadTimeFrom']);
	$_GET['uploadTimeFrom'] = trim($_GET['uploadTimeFrom']);
}
if(isset($_GET['uploadTimeTo'])){
	$smarty->assign('uploadTimeTo', $_GET['uploadTimeTo']);
	$_GET['uploadTimeTo'] = trim($_GET['uploadTimeTo']);
}

if(isset($_GET['faDateFrom'])){
	$smarty->assign('faDateFrom', $_GET['faDateFrom']);
	$_GET['faDateFrom'] = trim($_GET['faDateFrom']);
}
if(isset($_GET['faDateTo'])){
	$smarty->assign('faDateTo', $_GET['faDateTo']);
	$_GET['faDateTo'] = trim($_GET['faDateTo']);
}

if(isset($_GET['faNumber'])){
	$smarty->assign('faNumber', $_GET['faNumber']);
	$_GET['faNumber'] = trim($_GET['faNumber']);
}

if(isset($_GET['shipsClassification'])){
	$smarty->assign('shipsClassification', $_GET['shipsClassification']);
	$_GET['shipsClassification'] = Filter::forDBInsertion(trim($_GET['shipsClassification']));
}
if(isset($_GET['material'])){
	$smarty->assign('material', $_GET['material']);
	$_GET['material'] = Filter::forDBInsertion(trim($_GET['material']));
}

if(isset($_GET['orderNumber'])){
	$smarty->assign('orderNumber', $_GET['orderNumber']);
	$_GET['orderNumber'] = Filter::forDBInsertion(trim($_GET['orderNumber']));
}

if(isset($_GET['orderSubitemNumber'])){
	$smarty->assign('orderSubitemNumber', $_GET['orderSubitemNumber']);
	$_GET['orderSubitemNumber'] = trim($_GET['orderSubitemNumber']);
}

if(isset($_GET['filename'])){
	$smarty->assign('filename', $_GET['filename']);
	$_GET['filename'] = Filter::forDBInsertion(trim($_GET['filename']));
}
if(isset($_GET['remark'])){
	$smarty->assign('remark', $_GET['remark']);
	$_GET['remark'] = Filter::forDBInsertion(trim($_GET['remark']));
}

if(isset($_GET['unitPrice'])){
	$smarty->assign('unitPrice', $_GET['unitPrice']);
	$_GET['unitPrice'] = trim($_GET['unitPrice']);
}
if(isset($_GET['batchNumber'])){
	$smarty->assign('batchNumber', $_GET['batchNumber']);
	$_GET['batchNumber'] = Filter::forDBInsertion(trim($_GET['batchNumber']));
}
if(isset($_GET['purchaseNumber'])){
	$smarty->assign('purchaseNumber', $_GET['purchaseNumber']);
	$_GET['purchaseNumber'] = Filter::forDBInsertion(trim($_GET['purchaseNumber']));
}
if(isset($_GET['destination'])){
	$smarty->assign('destination', $_GET['destination']);
	$_GET['destination'] = Filter::forDBInsertion(trim($_GET['destination']));
}
if(isset($_GET['storePlace'])){
	$smarty->assign('storePlace', $_GET['storePlace']);
	$_GET['storePlace'] = Filter::forDBInsertion(trim($_GET['storePlace']));
}
if(isset($_GET['sequenceNumber'])){
	$smarty->assign('sequenceNumber', $_GET['sequenceNumber']);
	$_GET['sequenceNumber'] = Filter::forDBInsertion(trim($_GET['sequenceNumber']));
}
if(isset($_GET['manufactory'])){
	$smarty->assign('manufactory', $_GET['manufactory']);
	$_GET['manufactory'] = Filter::forDBInsertion(trim($_GET['manufactory']));
}
if(isset($_GET['certificateNumber'])){
	$smarty->assign('certificateNumber', $_GET['certificateNumber']);
	$_GET['certificateNumber'] = Filter::forDBInsertion(trim($_GET['certificateNumber']));
}
if(isset($_GET['checkoutBatch'])){
	$smarty->assign('checkoutBatch', $_GET['checkoutBatch']);
	$_GET['checkoutBatch'] = Filter::forDBInsertion(trim($_GET['checkoutBatch']));
}
if(isset($_GET['materialNumber'])){
	$smarty->assign('materialNumber', $_GET['materialNumber']);
	$_GET['materialNumber'] = Filter::forDBInsertion(trim($_GET['materialNumber']));
}
if(isset($_GET['consignmentBatch'])){
	$smarty->assign('consignmentBatch', $_GET['consignmentBatch']);
	$_GET['consignmentBatch'] = Filter::forDBInsertion(trim($_GET['consignmentBatch']));
}
if(isset($_GET['ruku'])){
	$smarty->assign('ruku', $_GET['ruku']);
	$_GET['ruku'] = Filter::forDBInsertion(trim($_GET['ruku']));
}
if(isset($_GET['chuku'])){
	$smarty->assign('chuku', $_GET['chuku']);
	$_GET['chuku'] = Filter::forDBInsertion(trim($_GET['chuku']));
}
if(isset($_GET['sale'])){
	$smarty->assign('sale', $_GET['sale']);
	$_GET['sale'] = Filter::forDBInsertion(trim($_GET['sale']));
}
if(isset($_GET['main'])){
	$smarty->assign('main', $_GET['main']);
	$_GET['main'] = Filter::forDBInsertion(trim($_GET['main']));
}
if(isset($_GET['checkout'])){
	$smarty->assign('checkout', $_GET['checkout']);
	$_GET['checkout'] = Filter::forDBInsertion(trim($_GET['checkout']));
}
if(isset($_GET['year'])){
	$_GET['year'] = Filter::forDBInsertion(trim($_GET['year']));
}

//以下是数据库查询时的条件限制
$wheres = array();

if(!empty($_GET['materialCode'])){
	array_push($wheres, "materialCode like '%{$_GET['materialCode']}%'");
}

if(!empty($_GET['thicknessFrom']) && Verifier::isNUMBER($_GET['thicknessFrom'])){
	$thicknessFrom = doubleval($_GET['thicknessFrom']);
	array_push($wheres, "thickness >= {$thicknessFrom}");
}

if(!empty($_GET['thicknessTo']) && Verifier::isNUMBER($_GET['thicknessTo'])){
	$thicknessTo = doubleval($_GET['thicknessTo']);
	array_push($wheres, "thickness <= {$thicknessTo}");
}

if(!empty($_GET['widthFrom']) && Verifier::isNUMBER($_GET['widthFrom'])){
	$widthFrom = doubleval($_GET['widthFrom']);
	array_push($wheres, "width >= {$widthFrom}");
}

if(!empty($_GET['widthTo']) && Verifier::isNUMBER($_GET['widthTo'])){
	$widthTo = doubleval($_GET['widthTo']);
	array_push($wheres, "width <= {$widthTo}");
}

if(!empty($_GET['lengthFrom']) && Verifier::isNUMBER($_GET['lengthFrom'])){
	$lengthFrom = doubleval($_GET['lengthFrom']);
	array_push($wheres, "length >= {$lengthFrom}");
}

if(!empty($_GET['lengthTo']) && Verifier::isNUMBER($_GET['lengthTo'])){
	$lengthTo = doubleval($_GET['lengthTo']);
	array_push($wheres, "length <= {$lengthTo}");
}

if(!empty($_GET['uploadTimeFrom']) && Verifier::isTIME($_GET['uploadTimeFrom'])){
	$time = date('Y/m/d H:i:s', strtotime($_GET['uploadTimeFrom']));
	array_push($wheres, "uploadTime >= '$time'");
}

if(!empty($_GET['uploadTimeTo']) && Verifier::isTIME($_GET['uploadTimeTo'])){
	$time = date('Y/m/d H:i:s', strtotime($_GET['uploadTimeTo']));
	array_push($wheres, "uploadTime <= '$time'");
}


if(!empty($_GET['shipsClassification'])){
	array_push($wheres, "shipsClassification = '{$_GET['shipsClassification']}'");
}

if(!empty($_GET['material'])){
	array_push($wheres, "material = '{$_GET['material']}'");
}

if(!empty($_GET['filename'])){
	array_push($wheres, "filename like '%{$_GET['filename']}%'");
}

if(!empty($_GET['remark'])){
	array_push($wheres, "(remark1 like '%{$_GET['remark']}%' or remark2 like '%{$_GET['remark']}%'
						  or remark3 like '%{$_GET['remark']}%' or remark4 like '%{$_GET['remark']}%' or remark5 like '%{$_GET['remark']}%')");
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

if(!empty($_GET['certificateNumber'])){
	array_push($wheres, "certificateNumber like '%{$_GET['certificateNumber']}%'");
}

if(!empty($_GET['checkoutBatch'])){
	array_push($wheres, "checkoutBatch like '%{$_GET['checkoutBatch']}%'");
}

if(!empty($_GET['materialNumber'])){
	array_push($wheres, "materialNumber like '%{$_GET['materialNumber']}%'");
}

if(!empty($_GET['consignmentBatch'])){
	array_push($wheres, "consignmentBatch like '%{$_GET['consignmentBatch']}%'");
}

if (empty($_GET['ruku']) and empty($_GET['chuku']) and empty($_GET['sale']) and empty($_GET['main'])){
	header('Location: advancedSearch.html');
}

if(!empty($_GET['checkout'])){
	array_push($wheres,"certificateNumber is not null and checkoutBatch is not null");
}

if(!empty($_GET['year'])){
	array_push($wheres,"filename like '%{$_GET['year']}%'");
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
	if(!empty($_GET['main'])){
	$mainWheres = $wheres;
	
	if(!empty($_GET['sequenceNumber'])){
		array_push($mainWheres, "sequenceNumber like '%{$_GET['sequenceNumber']}%'");
	}
	
	if(!empty($_GET['manufactory'])){
		array_push($mainWheres, "manufactory like '%{$_GET['manufactory']}%'");
	}
	
	$whereStr = join(' and ', $mainWheres);
//	var_dump($whereStr);

	if(count($mainWheres)==0){
		$query = "select id, sequenceNumber, manufactory, materialCode, shipsClassification, material,
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber, certificateNumber, checkoutBatch, materialNumber, consignmentBatch,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_main";
	}else{
		$query = "select id, sequenceNumber, manufactory, materialCode, shipsClassification, material,
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber, certificateNumber, checkoutBatch, materialNumber, consignmentBatch,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_main where $whereStr";
	}
	DB::query($query);

	$ids = array();
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

	$result = DB::getResult();

	while($row = $result->fetch_assoc()){
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
		array_push ($certificateNumbers, $row['certificateNumber']);
		array_push ($checkoutBatchs, $row['checkoutBatch']);
		array_push ($materialNumbers, $row['materialNumber']);
		array_push ($consignmentBatchs, $row['consignmentBatch']);
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
				'main_checkoutBatch' => $checkoutBatchs,
				'main_certificateNumber' => $certificateNumbers,
				'main_materialNumber' => $materialNumbers,
				'main_consignmentBatch' => $consignmentBatchs,
				'main_ids' => $ids
			));
	}

}



if($faTable){
		
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
		
//		var_dump($facheWhereStr);
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
	
		$ids = array();
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
	
		while($row = $result->fetch_assoc()){
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
			array_push($ids, $row['id']);
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
		
		$smarty->assign(array('fache_facheDate' => $facheDates,
					'fache_facheNumber' => $facheNumbers,
					'fache_materialCode' => $materialCodes,
					'fache_shipsClassification' => $shipsClassifications,
					'fache_material' => $materials,
					'fache_thickness' => $thicknesses,
					'fache_width' => $widths,
					'fache_length' => $lengths,
					'fache_count' => $counts,
					'fache_unitWeight' => $unitWeights,
					'fache_weight' => $weights,
					'fache_remark' => $remarks,
					'fache_uploadTime' => $uploadTimes,
					'fache_filename' => $filenames,
					'fache_phase' => $phases,
					'fache_ids' => $ids,
					'fache_orderNumber' => $orderNumbers,
					'fache_orderSubitemNumber' => $orderSubitemNumbers,
					'fache_unitPrice' => $unitPrices,
					'fache_batchNumber' => $batchNumbers,
					'fache_purchaseNumber' => $purchaseNumbers,
					'fache_destination' => $destinations,
					'fache_storePlace' => $storePlaces,
					'fache_checkoutBatch' => $checkoutBatchs,
					'fache_materialNumber' => $materialNumbers,
					'fache_consignmentBatch' => $consignmentBatchs,
					'fache_certificateNumber' => $certificateNumbers,
				));
		
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
//		var_dump($fachuanWhereStr);
		
	
		if(count($fachuanWheres)!=0){
		$query = "select id, fachuanDate, fachuanNumber, materialCode, shipsClassification, material,
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber, checkoutBatch, certificateNumber, materialNumber, consignmentBatch,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fachuan where $fachuanWhereStr";
		}else{
			$query = "select id, fachuanDate, fachuanNumber, materialCode, shipsClassification, material,
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber, checkoutBatch, certificateNumber, materialNumber, consignmentBatch,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fachuan";
		}
		DB::query($query);
	
		$ids = array();
		$fachuanDates = array();
		$fachuanNumbers = array();
		$phases = array();
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
		$result = DB::getResult();
	
		while($row = $result->fetch_assoc()){
			array_push($fachuanDates, $row['fachuanDate']);
			array_push($fachuanNumbers, $row['fachuanNumber']);
			array_push($phases, '发船');
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
			array_push($ids, $row['id']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices, $row['unitPrice']);
			array_push($batchNumbers, $row['batchNumber']);
			array_push($purchaseNumbers, $row['purchaseNumber']);		
			array_push($destinations, $row['destination']);
			array_push($storePlaces, $row['storePlace']);
			array_push($checkoutBatchs, $row['checkoutBatch']);
			array_push($certificateNumbers, $row['certificateNumber']);
			array_push($materialNumbers, $row['materialNumber']);
			array_push($consignmentBatchs, $row['consignmentBatch']);
		}
		
		$smarty->assign(array('fachuan_fachuanDate' => $fachuanDates,
					'fachuan_fachuanNumber' => $fachuanNumbers,
					'fachuan_phase' => $phases,
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
					'fachuan_ids' => $ids,
					'fachuan_orderNumber' => $orderNumbers,
					'fachuan_orderSubitemNumber' => $orderSubitemNumbers,
					'fachuan_unitPrice' => $unitPrices,
					'fachuan_batchNumber' => $batchNumbers,
					'fachuan_purchaseNumber' => $purchaseNumbers,
					'fachuan_destination' => $destinations,
					'fachuan_storePlace' => $storePlaces,
					'fachuan_checkoutBatch' => $checkoutBatchs,
					'fachuan_materialNumber' => $materialNumbers,
					'fachuan_consignmentBatch' => $consignmentBatchs,
					'fachuan_certificateNumber' => $certificateNumbers
				));
	}

}
	if(!empty($_GET['checkout']))//结算批号的修改
		$smarty->assign("checkout",$_GET['checkout']);
	$smarty->display('simpleTable.html');
?>