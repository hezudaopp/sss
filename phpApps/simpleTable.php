<?php

require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/Verifier.class.php');

$smarty = SmartyManager::getSmarty();

try{

$_GET['keyname'] = trim($_GET['keyname']);

$smarty->assign('keyname', $_GET['keyname']);
$smarty->assign('stype', $_GET['stype']);


if($_GET['stype'] == 'faNumber'){
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace from sss_fache where facheNumber like '%{$_GET['keyname']}%' order by facheNumber";
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
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fache_orderNumber' => $orderNumbers,
				'fache_orderSubitemNumber' => $orderSubitemNumbers,
				'fache_unitPrice' => $unitPrices,
				'fache_batchNumber' => $batchNumbers,
	            'fache_purchaseNumber' => $purchaseNumbers,
				'fache_destination' => $destinations,
				'fache_storePlace' => $storePlaces
			));
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight,
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fachuan where fachuanNumber like '%{$_GET['keyname']}%' order by fachuanNumber";
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
	
	$result = DB::getResult();
	
	while($row = $result->fetch_assoc()){
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fachuan_storePlace' => $storePlaces
			));
	$smarty->display('simpleTable.html');
}else if($_GET['stype'] == 'shipsClassification'){
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_main where shipsClassification ='{$_GET['keyname']}'";
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'main_storePlace' => $storePlaces
			));
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fache where shipsClassification = '{$_GET['keyname']}'";
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
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fache_orderNumber' => $orderNumbers,
				'fache_orderSubitemNumber' => $orderSubitemNumbers,
				'fache_unitPrice' => $unitPrices,
				'fache_batchNumber' => $batchNumbers,
	            'fache_purchaseNumber' => $purchaseNumbers,
				'fache_destination' => $destinations,
				'fache_storePlace' => $storePlaces
			));
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight,
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fachuan where shipsClassification ='{$_GET['keyname']}'";
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
	
	$result = DB::getResult();
	
	while($row = $result->fetch_assoc()){
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fachuan_storePlace' => $storePlaces
			));
	$smarty->display('simpleTable.html');
}else if($_GET['stype'] == 'material'){
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight,
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_main where material = '{$_GET['keyname']}'";
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'main_storePlace' => $storePlaces
			));
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight,
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fache where material = '{$_GET['keyname']}'";
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
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fache_orderNumber' => $orderNumbers,
				'fache_orderSubitemNumber' => $orderSubitemNumbers,
				'fache_unitPrice' => $unitPrices,
				'fache_batchNumber' => $batchNumbers,
	            'fache_purchaseNumber' => $purchaseNumbers,
				'fache_destination' => $destinations,
				'fache_storePlace' => $storePlaces
			));
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fachuan where material = '{$_GET['keyname']}'";
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
	
	$result = DB::getResult();
	
	while($row = $result->fetch_assoc()){
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fachuan_storePlace' => $storePlaces
			));
	$smarty->display('simpleTable.html');
}else if($_GET['stype'] == 'filename'){
	
	if(empty($_GET['keyname'])){
		//如果在按照文件名查询的时候没有输入关键字，结果就会按照上传时间列出所有文件。
		$query = "select count(*), filename, uploadTime from sss_main group by filename order by uploadTime desc";
		DB::query($query);
		
		$filenames = array();
		$uploadTimes = array();
		$counts = array();
		
		while($row = DB::getResult()->fetch_assoc()){
			array_push($filenames, $row['filename']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($counts, $row['count(*)']);
		}

		$smarty->assign(array(
					'main_filename' => $filenames,
					'main_uploadTime' => $uploadTimes,
					'main_count' => $counts
		));
		
		$filenames = array();
		$uploadTimes = array();
		$counts = array();
		
		$query = "select count(*), filename, uploadTime from sss_fache group by filename order by uploadTime desc";
		DB::query($query);
		while($row = DB::getResult()->fetch_assoc()){
			array_push($filenames, $row['filename']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($counts, $row['count(*)']);
		}

		$smarty->assign(array(
					'fache_filename' => $filenames,
					'fache_uploadTime' => $uploadTimes,
					'fache_count' => $counts
		));
		
		$filenames = array();
		$uploadTimes = array();
		$counts = array();
		
		$query = "select count(*), filename, uploadTime from sss_fachuan group by filename order by uploadTime desc";
		DB::query($query);
		while($row = DB::getResult()->fetch_assoc()){
			array_push($filenames, $row['filename']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($counts, $row['count(*)']);
		}

		$smarty->assign(array(
					'fachuan_filename' => $filenames,
					'fachuan_uploadTime' => $uploadTimes,
					'fachuan_count' => $counts
		));
		
		$smarty->display("listAllFiles.html");
		
	}else{
		$_GET['keyname'] = trim($_GET['keyname']);
		
		$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace
			from sss_main where filename like '%{$_GET['keyname']}%' order by filename, materialCode";
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
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
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
					'main_storePlace' => $storePlaces
				));
				
		$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight,
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, phase, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace
			from sss_fache where filename like '%{$_GET['keyname']}%' order by filename, materialCode";
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
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
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
					'fache_orderNumber' => $orderNumbers,
					'fache_orderSubitemNumber' => $orderSubitemNumbers,
					'fache_unitPrice' => $unitPrices,
					'fache_batchNumber' => $batchNumbers,
		            'fache_purchaseNumber' => $purchaseNumbers,
					'fache_destination' => $destinations,
					'fache_storePlace' => $storePlaces
				));
				
		$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
			remark1, remark2, remark3, remark4, remark5,
			uploadTime, filename, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace
			from sss_fachuan where filename like '%{$_GET['keyname']}%' order by filename, materialCode";
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
		
		$result = DB::getResult();
		
		while($row = $result->fetch_assoc()){
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
			array_push($unitPrices,$row['unitPrice']);
			array_push($batchNumbers,$row['batchNumber']);
			array_push($purchaseNumbers,$row['purchaseNumber']);
			array_push($destinations,$row['destination']);
			array_push($storePlaces,$row['storePlace']);
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
					'fachuan_storePlace' => $storePlaces
				));
		$smarty->display('simpleTable.html');
	}
	
}else if($_GET['stype'] == 'size'){
	
	$size = trim($_GET['keyname']);
	$sizes = preg_split("/[,，]+/", $size);
	if(count($sizes) != 3){
		throw new InvalidSizeSearchKeyword($size);
	}
	$sizes[0] = doubleval($sizes[0]);
	$sizes[1] = doubleval($sizes[1]);
	$sizes[2] = doubleval($sizes[2]);
	
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_main where thickness = {$sizes[0]}
		and width = {$sizes[1]} and length = {$sizes[2]} order by filename, materialCode";
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'main_storePlace' => $storePlaces
			));
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fache where thickness = {$sizes[0]}
		and width = {$sizes[1]} and length = {$sizes[2]} order by filename, materialCode";
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
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fache_orderNumber' => $orderNumbers,
				'fache_orderSubitemNumber' => $orderSubitemNumbers,
				'fache_unitPrice' => $unitPrices,
				'fache_batchNumber' => $batchNumbers,
	            'fache_purchaseNumber' => $purchaseNumbers,
				'fache_destination' => $destinations,
				'fache_storePlace' => $storePlaces
			));
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fachuan where thickness = {$sizes[0]}
		and width = {$sizes[1]} and length = {$sizes[2]} order by filename, materialCode";
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
	
	$result = DB::getResult();
	
	while($row = $result->fetch_assoc()){
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fachuan_storePlace' => $storePlaces
			));
	$smarty->display('simpleTable.html');
}else if($_GET['stype'] == 'faDate'){
	$dates = preg_split("/[,，]+/", $_GET['keyname']);
	if(count($dates) != 2){
		throw new InvalidfaDateSearchKeyword($_GET['keyname']);
	}
	
	$dates[0] = trim($dates[0]);
	$dates[1] = trim($dates[1]);
	
	foreach($dates as $date){
		if(!Verifier::isDATE($date)){
			throw new InvalidfaDateSearchKeyword($_GET['keyname']);
		}
	}
	
	$dates[0] = date('Y/m/d', strtotime($dates[0]));
	$dates[1] = date('Y/m/d', strtotime($dates[1]));
	
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight,
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fache where facheDate >= '{$dates[0]}' and  facheDate <= '{$dates[1]}' order by filename, materialCode";
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
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fache_orderNumber' => $orderNumbers,
				'fache_orderSubitemNumber' => $orderSubitemNumbers,
				'fache_unitPrice' => $unitPrices,
				'fache_batchNumber' => $batchNumbers,
	            'fache_purchaseNumber' => $purchaseNumbers,
				'fache_destination' => $destinations,
				'fache_storePlace' => $storePlaces
			));
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		where fachuanDate >= '{$dates[0]}' and  fachuanDate <= '{$dates[1]}' order by filename, materialCode";
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
	
	$result = DB::getResult();
	
	while($row = $result->fetch_assoc()){
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fachuan_storePlace' => $storePlaces
			));
	$smarty->display('simpleTable.html');
}else if($_GET['stype'] == 'uploadTime'){
	$dates = preg_split("/[,，]+/", $_GET['keyname']);
	if(count($dates) != 2){
		throw new InvalidUploadTimeSearchKeyword($_GET['keyname']);
	}
	
	$dates[0] = trim($dates[0]);
	$dates[1] = trim($dates[1]);
	
	foreach($dates as $date){
		if(!Verifier::isDATE($date)){
			throw new InvalidUploadTimeSearchKeyword($_GET['keyname']);
		}
	}
	
	$dates[0] = date('Y/m/d H:i:s', strtotime($dates[0]));
	$dates[1] = date('Y/m/d H:i:s', strtotime($dates[1]));
	
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_main where uploadTime >= '{$dates[0]}'
		 and uploadTime <= '{$dates[1]}' order by filename, materialCode";
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'main_storePlace' => $storePlaces
			));
			
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, phase, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fache where uploadTime >= '{$dates[0]}' and  uploadTime <= '{$dates[1]}' order by filename, materialCode";
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
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fache_orderNumber' => $orderNumbers,
				'fache_orderSubitemNumber' => $orderSubitemNumbers,
				'fache_unitPrice' => $unitPrices,
				'fache_batchNumber' => $batchNumbers,
	            'fache_purchaseNumber' => $purchaseNumbers,
				'fache_destination' => $destinations,
				'fache_storePlace' => $storePlaces
			));
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, 
		remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace
		from sss_fachuan where uploadTime >= '{$dates[0]}' and  uploadTime <= '{$dates[1]}' order by filename, materialCode";
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
	
	$result = DB::getResult();
	
	while($row = $result->fetch_assoc()){
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
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
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
				'fachuan_storePlace' => $storePlaces
			));
	$smarty->display('simpleTable.html');
}

}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}
?>