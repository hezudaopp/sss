<?php

require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/SimpleTableExporter.class.php');

$smarty = SmartyManager::getSmarty();

try{

$_GET['keyname'] = trim($_GET['keyname']);

$smarty->assign('keyname', $_GET['keyname']);
$smarty->assign('stype', $_GET['stype']);

$filename = $_GET['stype'].'_'.date('YmdHis').'.xls';

$exp = new SimpleTableExporter($filename);

if($_GET['stype'] == 'faNumber'){
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where facheNumber like '%{$_GET['keyname']}%' order by facheNumber";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($phases, $row['phase']);
	}
			
	$data = array($facheDates,$materialCodes, $facheNumbers, 
			$shipsClassifications, $materials, $thicknesses,
			$widths, $lengths, $counts, $unitWeights, $weights,
			$remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
	$exp->insertFacheData($data);
	
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where fachuanNumber like '%{$_GET['keyname']}%' order by fachuanNumber";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
			
	$data = array($fachuanDates, $materials, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers);
	$exp->insertFachuanData($data);
	$exp->export();
	
}else if($_GET['stype'] == 'shipsClassification'){
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_main where shipsClassification ='{$_GET['keyname']}'";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
	$data = array($sequenceNumbers, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers);
	$exp->insertMainData($data);
	
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where shipsClassification = '{$_GET['keyname']}'";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($phases, $row['phase']);
	}
	
	$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
	$exp->insertFacheData($data);
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where shipsClassification ='{$_GET['keyname']}'";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
	$data = array($fachuanDates, $materialCodes, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers);
	$exp->insertFachuanData($data);
	$exp->export();
	
}else if($_GET['stype'] == 'material'){
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_main where material = '{$_GET['keyname']}'";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
	$data = array($sequenceNumbers, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers);
	$exp->insertMainData($data);
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where material = '{$_GET['keyname']}'";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($phases, $row['phase']);
	}
	
	$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
	$exp->insertFacheData($data);
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where material = '{$_GET['keyname']}'";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
	$data = array($fachuanDates, $materialCodes, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers);
	$exp->insertFachuanData($data);
	$exp->export();
}else if($_GET['stype'] == 'filename'){
	
	if(empty($_GET['keyname'])){
		//如果在按照文件名查询的时候没有输入关键字，结果就会按照上传时间列出所有文件。
		$query = "select count(*), filename, uploadTime from sss_main group by filename, uploadTime";
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
		
		$query = "select count(*), filename, uploadTime from sss_fache group by filename, uploadTime";
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
		
		$query = "select count(*), filename, uploadTime from sss_fachuan group by filename, uploadTime";
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
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
			uploadTime, filename, orderNumber, orderSubitemNumber from sss_main where filename like '%{$_GET['keyname']}%' order by filename, materialCode";
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
				if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		}
		
	$data = array($sequenceNumbers, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers);
	$exp->insertMainData($data);
				
		$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
			uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where filename like '%{$_GET['keyname']}%' order by filename, materialCode";
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
				if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($phases, $row['phase']);
		}
		
		$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
		$exp->insertFacheData($data);

		$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
			thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
			uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where filename like '%{$_GET['keyname']}%' order by filename, materialCode";
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
				if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
			}
			array_push($remarks, $row['remark1']);
			array_push($uploadTimes, $row['uploadTime']);
			array_push($filenames, $row['filename']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		}
	
		$data = array($fachuanDates, $materialCodes, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers);
		$exp->insertFachuanData($data);
		$exp->export();
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
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_main where thickness = {$sizes[0]}
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
	$data = array($sequenceNumbers, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers);
	$exp->insertMainData($data);
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where thickness = {$sizes[0]}
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
	$orderNumbers = array();
	$orderSubitemNumbers = array();
	$phases = array();
	
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($phases, $row['phase']);
	}
	
	$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
	$exp->insertFacheData($data);
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where thickness = {$sizes[0]}
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
		$data = array($fachuanDates, $materialCodes, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers);
		$exp->insertFachuanData($data);
		$exp->export();
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
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where facheDate >= '{$dates[0]}' and  facheDate <= '{$dates[1]}' order by filename, materialCode";
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
	$orderNumbers = array();
	$orderSubitemNumbers = array();
	$phases = array();
	
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($phases, $row['phase']);
	}
	
		$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
		$exp->insertFacheData($data);
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where fachuanDate >= '{$dates[0]}' and  fachuanDate <= '{$dates[1]}' order by filename, materialCode";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
		$data = array($fachuanDates, $materialCodes, $fachuanNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers);
		$exp->insertFachuanData($data);
		$exp->export();
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
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_main where uploadTime >= '{$dates[0]}'
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
	
	$data = array($sequenceNumbers, $materialCodes, $manufactorys, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames, $orderNumbers, $orderSubitemNumbers);
	$exp->insertMainData($data);
			
			
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber, phase from sss_fache where uploadTime >= '{$dates[0]}' and  uploadTime <= '{$dates[1]}' order by filename, materialCode";
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
	$orderNumbers = array();
	$orderSubitemNumbers = array();
	$phases = array();
	
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($phases, $row['phase']);
	}
	
		$data = array($facheDates, $materialCodes, $facheNumbers, $shipsClassifications,
				$materials, $thicknesses, $widths, $lengths, $counts, $unitWeights,
				$weights, $remarks, $uploadTimes, $filenames,$orderNumbers, $orderSubitemNumbers, $phases);
		$exp->insertFacheData($data);
			
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2,
		uploadTime, filename, orderNumber, orderSubitemNumber from sss_fachuan where uploadTime >= '{$dates[0]}' and  uploadTime <= '{$dates[1]}' order by filename, materialCode";
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
			if(empty($row['remark1'])){
				$row['remark1'] = $row['remark2'];
			}else{
				$row['remark1'] = '1. '.$row['remark1']."\n2. ".$row['remark2'];
			}
		}
		array_push($remarks, $row['remark1']);
		array_push($uploadTimes, $row['uploadTime']);
		array_push($filenames, $row['filename']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
	}
}




}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}
?>