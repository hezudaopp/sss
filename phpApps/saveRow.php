<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/functions.inc.php');

$id = $_POST['id'];
$type = $_POST['type'];
$smarty = SmartyManager::getSmarty();
try{
	

$materialCode = $_POST['materialCode'];
$shipsClassification = $_POST['shipsClassification'];
$material = $_POST['material'];

$thickness = $_POST['thickness'];
if(!Verifier::isNUMBER($thickness)){
	throw new CustomException('厚度应该为数字，您填写的这个值：'.$thickness.'不合格');
}
$length = $_POST['length'];
if(!Verifier::isNUMBER($length)){
	throw new CustomException('长度应该为数字，您填写的这个值：'.$length.'不合格');
}
$width = $_POST['width'];
if(!Verifier::isNUMBER($width)){
	throw new CustomException('宽度应该为数字，您填写的这个值：'.$width.'不合格');
}

$count = $_POST['count'];
if(!Verifier::isNUMBER($count)){
	throw new CustomException('数量应该为数字，您填写的这个值：'.$count.'不合格');
}

$unitWeight = $_POST['unitWeight'];
if(!Verifier::isNUMBER($unitWeight)){
	throw new CustomException('单重应该为数字，您填写的这个值：'.$unitWeight.'不合格');
}

$unitPrice = $_POST['unitPrice'];
if( !empty($unitPrice) && !Verifier::isNUMBER($unitPrice)){
	throw new CustomException('受订单价应该为数字，您填写的这个值：'.$unitPrice.'不合格');
}

$remark1 = Filter::forDBInsertion($_POST['remark1']);
$remark2 = Filter::forDBInsertion($_POST['remark2']);
$remark3 = Filter::forDBInsertion($_POST['remark3']);
$remark4 = Filter::forDBInsertion($_POST['remark4']);
$remark5 = Filter::forDBInsertion($_POST['remark5']);

$orderNumber = Filter::forDBInsertion($_POST['orderNumber']);
$orderSubitemNumber = Filter::forDBInsertion($_POST['orderSubitemNumber']);
$unitPrice = Filter::forDBInsertion($_POST['unitPrice']);
$batchNumber = Filter::forDBInsertion($_POST['batchNumber']);
$purchaseNumber = Filter::forDBInsertion($_POST['purchaseNumber']);
$destination = Filter::forDBInsertion($_POST['destination']);
$storePlace = Filter::forDBInsertion($_POST['storePlace']);
$certificateNumber = Filter::forDBInsertion($_POST['certificateNumber']);
$checkoutBatch = Filter::forDBInsertion($_POST['checkoutBatch']);
$materialNumber = Filter::forDBInsertion($_POST['materialNumber']);
$consignmentBatch = Filter::forDBInsertion($_POST['consignmentBatch']);
	
beginTransaction();

if($type == 'main'){
	$sequenceNumber = $_POST['sequenceNumber'];
	$manufactory = $_POST['manufactory'];
	
	$query = 'select * from sss_main where id = '.$id;
	findAndLogEdit($query, $_POST);
	
	if(empty($certificateNumber)&&empty($checkoutBatch)){
		$query = "update sss_main set materialCode = '$materialCode', shipsClassification = '$shipsClassification',
			material = '$material', thickness = $thickness, width = $width, length = $length, count = $count, 
			unitWeight = $unitWeight, orderNumber = '$orderNumber', orderSubitemNumber = '$orderSubitemNumber',
			unitPrice = '$unitPrice', batchNumber = '$batchNumber', purchaseNumber = '$purchaseNumber', 
			destination = '$destination', storePlace = '$storePlace',
			sequenceNumber = '$sequenceNumber', manufactory = '$manufactory', 
			remark1 = '$remark1', remark2 = '$remark2',remark3 = '$remark3',remark4 = '$remark4',remark5 = '$remark5',
			certificateNumber = NULL, checkoutBatch = NULL, materialNumber = '$materialNumber', consignmentBatch = '$consignmentBatch'
			where id = $id";
	}else{
		$query = "update sss_main set materialCode = '$materialCode', shipsClassification = '$shipsClassification',
		material = '$material', thickness = $thickness, width = $width, length = $length, count = $count, 
		unitWeight = $unitWeight, orderNumber = '$orderNumber', orderSubitemNumber = '$orderSubitemNumber',
		unitPrice = '$unitPrice', batchNumber = '$batchNumber', purchaseNumber = '$purchaseNumber', 
		destination = '$destination', storePlace = '$storePlace',
		sequenceNumber = '$sequenceNumber', manufactory = '$manufactory', 
		remark1 = '$remark1', remark2 = '$remark2',remark3 = '$remark3',remark4 = '$remark4',remark5 = '$remark5', 
		certificateNumber = '$certificateNumber', checkoutBatch = '$checkoutBatch', materialNumber = '$materialNumber', consignmentBatch = '$consignmentBatch'
 		where id = $id";
	}
}else if($type == 'fache'){
	$facheDate = $_POST['facheDate'];
	if(!Verifier::isDATE($facheDate)){
		throw new CustomException('发车时间格式不正确，请按要求输入发船时间');
	}
	
	$facheNumber = $_POST['facheNumber'];
	$phase = $_POST['phase'];
	
	$query = 'select * from sss_fache where id = '.$id;
	findAndLogEdit($query, $_POST);
	
	if(empty($certificateNumber)&&empty($checkoutBatch)){
	$query = "update sss_fache set materialCode = '$materialCode', shipsClassification = '$shipsClassification',
		material = '$material', thickness = $thickness, width = $width, length = $length, count = $count, 
		unitWeight = $unitWeight,
		remark1 = '$remark1', remark2 = '$remark2',remark3 = '$remark3',remark4 = '$remark4',remark5 = '$remark5', 
		facheDate = '$facheDate', facheNumber = '$facheNumber', phase = '$phase', orderNumber = '$orderNumber', 
		orderSubitemNumber = '$orderSubitemNumber',
		unitPrice = '$unitPrice', batchNumber = '$batchNumber', purchaseNumber = '$purchaseNumber', 
		destination = '$destination', storePlace = '$storePlace', 
		certificateNumber = NULL, checkoutBatch = NULL, materialNumber = '$materialNumber', consignmentBatch = '$consignmentBatch'
		where id = $id";
	}else{
		$query = "update sss_fache set materialCode = '$materialCode', shipsClassification = '$shipsClassification',
		material = '$material', thickness = $thickness, width = $width, length = $length, count = $count, 
		unitWeight = $unitWeight,
		remark1 = '$remark1', remark2 = '$remark2',remark3 = '$remark3',remark4 = '$remark4',remark5 = '$remark5', 
		facheDate = '$facheDate', facheNumber = '$facheNumber', phase = '$phase', orderNumber = '$orderNumber', 
		orderSubitemNumber = '$orderSubitemNumber',
		unitPrice = '$unitPrice', batchNumber = '$batchNumber', purchaseNumber = '$purchaseNumber', 
		destination = '$destination', storePlace = '$storePlace', 
		certificateNumber = '$certificateNumber', checkoutBatch = '$checkoutBatch', materialNumber = '$materialNumber', consignmentBatch = '$consignmentBatch'
 		where id = $id";
	}
	
}else if($type == 'fachuan'){
	$fachuanDate = $_POST['fachuanDate'];
	if(!Verifier::isDATE($fachuanDate)){
		throw new CustomException('发船时间格式不正确，请按要求输入发船时间');
	}
	$fachuanNumber = $_POST['fachuanNumber'];
	
	$query = 'select * from sss_fachuan where id = '.$id;
	findAndLogEdit($query, $_POST);
	
	if(empty($certificateNumber)&&empty($checkoutBatch)){
		$query = "update sss_fachuan set materialCode = '$materialCode', shipsClassification = '$shipsClassification',
			material = '$material', thickness = $thickness, width = $width, length = $length, count = $count, 
			unitWeight = $unitWeight, 
			remark1 = '$remark1', remark2 = '$remark2',remark3 = '$remark3',remark4 = '$remark4',remark5 = '$remark5', 
			fachuanDate = '$fachuanDate', fachuanNumber = '$fachuanNumber', orderNumber = '$orderNumber' , 
			orderSubitemNumber = '$orderSubitemNumber', 
			unitPrice = '$unitPrice', batchNumber = '$batchNumber', purchaseNumber = '$purchaseNumber', 
			destination = '$destination', storePlace = '$storePlace',
			certificateNumber = NULL, checkoutBatch = NULL, materialNumber = '$materialNumber', consignmentBatch = '$consignmentBatch'
			where id = $id";
	}else{
		$query = "update sss_fachuan set materialCode = '$materialCode', shipsClassification = '$shipsClassification',
		material = '$material', thickness = $thickness, width = $width, length = $length, count = $count, 
		unitWeight = $unitWeight, 
		remark1 = '$remark1', remark2 = '$remark2',remark3 = '$remark3',remark4 = '$remark4',remark5 = '$remark5', 
		fachuanDate = '$fachuanDate', fachuanNumber = '$fachuanNumber', orderNumber = '$orderNumber' , 
		orderSubitemNumber = '$orderSubitemNumber', 
		unitPrice = '$unitPrice', batchNumber = '$batchNumber', purchaseNumber = '$purchaseNumber', 
		destination = '$destination', storePlace = '$storePlace', 
		certificateNumber = '$certificateNumber', checkoutBatch = '$checkoutBatch', materialNumber = '$materialNumber', consignmentBatch = '$consignmentBatch'
 		where id = $id";
	}
}

DB::query($query);
	commit();
}catch(Exception $e){
	$smarty->assign('errTitle', '操作错误');
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	rollback();
	die();
}

$smarty->assign('successTitle','修改成功');
$smarty->display('success.html');

?>