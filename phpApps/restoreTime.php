<?php
require_once ('../includes/SmartyManager.class.php');
require_once ("../includes/Verifier.class.php");
require_once ("../includes/RestoreTime.class.php");
require_once ('../includes/functions.inc.php');

ini_set ( "max_execution_time", 0 );
ini_set ( "memory_limit" , "128M");

function redirect($str) {
	$url = "restoreTimeInput.php?$str";
	
	$gets = array ();
	foreach ( $_POST as $key => $val ) {
		array_push ( $gets, urlencode ( $key ) . "=" . urlencode ( $val ) );
	}
	$url .= "&" . join ( "&", $gets );
	header ( "location: $url" );
}

//echo $_POST['fromDate'] ."===". $_POST['toDate'];


$fromDate = null;
$toDate = null;
if (isset ( $_POST ['fromDate'] )) {
	if (Verifier::isDATE ( $_POST ['fromDate'] )) {
		$fromDate = $_POST ['fromDate'];
	} else if (empty ( $_POST ['fromDate'] )) {
		$fromDate = NULL;
	} else {
		redirect ( "err=format1" );
		exit ();
	}
}

if (isset ( $_POST ['toDate'] )) {
	if (Verifier::isDATE ( $_POST ['toDate'] )) {
		$toDate = $_POST ['toDate'];
	} else if (empty ( $_POST ['toDate'] )) {
		$toDate = NULL;
	} else {
		redirect ( "err=format2" );
		exit ();
	}
}

if (! empty ( $_POST ['storePlace'] )) {
	$storePlace = $_POST ['storePlace'];
}

if (! empty ( $_POST ['destination'] )) {
	$destination = $_POST ['destination'];
}

/*if(empty($toDate) || empty($fromDate)){
	
	if(empty($fromDate)){
		redirect("err=format1");
		exit();
	}
	if(empty($toDate)){
		redirect("err=format2");
		exit();
	}
}*/
//echo "hello ok";


$rs = new RestoreTime ( $fromDate, $toDate, $storePlace, $destination );
$rs->findCommon ();

$export = false;
if (isset ( $_POST ['export'] )) {
	$export = true;
}

if (! $export) {
	$smarty = SmartyManager::getSmarty ();
	$smarty->assign ( array ('materialCode' => $rs->getMaterialCode (), 
							 'ships' => $rs->getShips (), 
							 'material' => $rs->getMaterial (), 
							 'thickness' => $rs->getThickness (), 
							 'width' => $rs->getWidth (), 'length' => $rs->getLength (), 
							 'unitWeight' => $rs->getUnitWeight (), 
							 'weight' => $rs->getWeight (), 'rukuDate' => $rs->getRukuDate (), 
							 'chukuDate' => $rs->getChukuDate (), 'rukuNumber' => $rs->getRukuNumber (), 
							 'chukuNumber' => $rs->getChukuNumber (), 'interval' => $rs->getInterval (), 
							 'count' => $rs->getCount (), 
							 'orderNumber' => $rs->getOrderNumber (), 
							 'orderSubitemNumber' => $rs->getOrderSubitemNumber (), 
							 'unitPrice' => $rs->getUnitPrice (), 
							 'batchNumber' => $rs->getBatchNumber (), 
							 'purchaseNumber' => $rs->getPurchaseNumber (), 
							 'storePlace' => $rs->getStoreplace (), 
							 'destination' => $rs->getDestination (),
							 'certificateNumber' => $rs->getCertificateNumber(),
							 'checkoutBatch' => $rs->getCheckoutBatch(),
							 'materialNumber' => $rs->getMaterialNumber(),
							 'consignmentBatch' => $rs->getConsignmentBatch(),
							 'isExistFalseItem' => $rs->getIsExistFalseItem()) );
	$smarty->display ( 'restoreTimeTable.html' );

} else {
	$data = array ('材料代码' => $rs->getMaterialCode (), '船级' => $rs->getShips (), 
				   '材质' => $rs->getMaterial (), 
				   '厚' => $rs->getThickness (), '宽' => $rs->getWidth (), '长' => $rs->getLength (), 
				   '数量' => $rs->getCount (), 
				   '单重' => $rs->getUnitWeight (), 
				   '重量' => $rs->getWeight(), '订单号' => $rs->getOrderNumber (), 
				   '订单子项号' => $rs->getOrderSubitemNumber (), 
				   '受订单价' => $rs->getUnitPrice (), '购单号' => $rs->getPurchaseNumber (), 
				   '批号' => $rs->getBatchNumber (),
				   '物料号' => $rs->getMaterialNumber(), 
				   '库存地' => $rs->getStoreplace (), 
				   '目的地' => $rs->getDestination (), 
				   '证书号' => $rs->getCertificateNumber(),
				   '结算批号' => $rs->getCheckoutBatch(),
				   '发货批次' => $rs->getConsignmentBatch(),
				   '停留时间' => $rs->getInterval (), 
				   '入库日期' => $rs->getRukuDate (), '入库车号' => $rs->getRukuNumber (), 
				   '出库日期' => $rs->getChukuDate (), '出库车号/船号' => $rs->getChukuNumber () );
	$isExistFalseItem = $rs->getIsExistFalseItem();
	$filenameStr = '滞留-';
	if (! empty ( $fromDate ) || ! empty ( $toDate )) {
		$filenameStr .= '停留时间-' . $fromDate . '到' . $toDate;
	}
	if (! empty ( $storePlace )) {
		$filenameStr .= '库存地-' . $storePlace;
	}
	if (! empty ( $destination )) {
		$filenameStr .= '目的地-' . $destination;
	}
	$filename = $filenameStr . '.xls';
	if($isExistFalseItem){
		generateHttpExcelByColsSpecial ( $data, $filename );
	}else{
		generateHttpExcelByCols ( $data, $filename );
	}
}
?>