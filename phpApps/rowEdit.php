<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');

$id=$_GET['id'];

$smarty = SmartyManager::getSmarty();
$assignList = array();
try{

if($_GET['type'] == 'main'){
	$query = "select sequenceNumber, manufactory, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_main where id = $id";
	DB::query($query);
	
	$result = DB::getResult();
	$row = $result->fetch_assoc();
	$assignList = array(
		'id' => $id,
		'sequenceNumber' => $row['sequenceNumber'],
		'manufactory' => $row['manufactory'],
		'materialCode' => $row['materialCode'],
		'shipsClassification' => $row['shipsClassification'],
		'material' => $row['material'],
		'thickness' => $row['thickness'],
		'width' => $row['width'],
		'length' => $row['length'],
		'count' => $row['count'],
		'unitWeight' => $row['unitWeight'],
		'remark1' => $row['remark1'],
		'remark2' => $row['remark2'],
		'remark3' => $row['remark3'],
		'remark4' => $row['remark4'],
		'remark5' => $row['remark5'],
		'orderNumber' => $row['orderNumber'],
		'orderSubitemNumber' => $row['orderSubitemNumber'],
		'unitPrice'	=> $row['unitPrice'],
		'batchNumber' => $row['batchNumber'],
		'purchaseNumber' => $row['purchaseNumber'],
		'destination' => $row['destination'],
		'storePlace' => $row['storePlace'],
		'certificateNumber' => $row['certificateNumber'],
		'checkoutBatch' => $row['checkoutBatch'],
		'materialNumber' => $row['materialNumber'],
		'consignmentBatch' => $row['consignmentBatch']
	);
}else if($_GET['type'] == 'fache'){
	$query = "select facheDate, facheNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace, phase,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_fache where id = $id";
	DB::query($query);
	
	$result = DB::getResult();
	$row = $result->fetch_assoc();
	$assignList = array(
		'id' => $id,
		'facheDate' => $row['facheDate'],
		'facheNumber' => $row['facheNumber'],
		'materialCode' => $row['materialCode'],
		'shipsClassification' => $row['shipsClassification'],
		'material' => $row['material'],
		'thickness' => $row['thickness'],
		'width' => $row['width'],
		'length' => $row['length'],
		'count' => $row['count'],
		'unitWeight' => $row['unitWeight'],
		'remark1' => $row['remark1'],
		'remark2' => $row['remark2'],
		'remark3' => $row['remark3'],
		'remark4' => $row['remark4'],
		'remark5' => $row['remark5'],
		'phase' => $row['phase'],
		'orderNumber' => $row['orderNumber'],
		'orderSubitemNumber' => $row['orderSubitemNumber'],
		'unitPrice'	=> $row['unitPrice'],
		'batchNumber' => $row['batchNumber'],
		'purchaseNumber' => $row['purchaseNumber'],
		'destination' => $row['destination'],
		'storePlace' => $row['storePlace'],
		'certificateNumber' => $row['certificateNumber'],
		'checkoutBatch' => $row['checkoutBatch'],
		'materialNumber' => $row['materialNumber'],
		'consignmentBatch' => $row['consignmentBatch']
	);
}else if($_GET['type'] == 'fachuan'){
	$query = "select fachuanDate, fachuanNumber, materialCode, shipsClassification, material, 
		thickness, width, length, count, unitWeight, (count * unitWeight) as weight, remark1, remark2, remark3, remark4, remark5,
		uploadTime, filename, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
		from sss_fachuan where id = $id";
	DB::query($query);
	
	$result = DB::getResult();
	$row = $result->fetch_assoc();
	$assignList = array(
		'id' => $id,
		'fachuanDate' => $row['fachuanDate'],
		'fachuanNumber' => $row['fachuanNumber'],
		'materialCode' => $row['materialCode'],
		'shipsClassification' => $row['shipsClassification'],
		'material' => $row['material'],
		'thickness' => $row['thickness'],
		'width' => $row['width'],
		'length' => $row['length'],
		'count' => $row['count'],
		'unitWeight' => $row['unitWeight'],
		'remark1' => $row['remark1'],
		'remark2' => $row['remark2'],
		'remark3' => $row['remark3'],
		'remark4' => $row['remark4'],
		'remark5' => $row['remark5'],
		'orderNumber' => $row['orderNumber'],
		'orderSubitemNumber' => $row['orderSubitemNumber'],
		'unitPrice'	=> $row['unitPrice'],
		'batchNumber' => $row['batchNumber'],
		'purchaseNumber' => $row['purchaseNumber'],
		'destination' => $row['destination'],
		'storePlace' => $row['storePlace'],
		'certificateNumber' => $row['certificateNumber'],
		'checkoutBatch' => $row['checkoutBatch'],
		'materialNumber' => $row['materialNumber'],
		'consignmentBatch' => $row['consignmentBatch']
	);
}


}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}


$smarty->assign($assignList);
$smarty->assign('type', $_GET['type']);
$smarty->assign('id', $_GET['id']);
$smarty->assign($_GET['type'], true);
$smarty->display('rowEdit.html');
?>