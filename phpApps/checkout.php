<?php
require_once ('../includes/SmartyManager.class.php');
require_once ("../includes/Verifier.class.php");
require_once ("../includes/Checkout.class.php");
require_once ('../includes/functions.inc.php');

ini_set ( "max_execution_time", 0 );

function redirect($str) {
	$url = "checkoutInput.php?$str";
	
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



$rs = new Checkout ( $fromDate, $toDate);
$rs->findCommon ($_POST['group']);

$smarty = SmartyManager::getSmarty ();

$smarty->assign ( array ('chukuDate' => $rs->getChukuDate (),
						 'chukuNumber' => $rs->getChukuNumber (),
						 'materialCode' => $rs->getMaterialCode (), 
						 'ships' => $rs->getShips (), 
						 'material' => $rs->getMaterial (), 
						 'thickness' => $rs->getThickness (), 
						 'width' => $rs->getWidth (), 
						 'length' => $rs->getLength (), 
						 'count' => $rs->getCount (),
						 'unitWeight' => $rs->getUnitWeight (), 
						 'weight' => $rs->getWeight (), 
						 'orderNumber' => $rs->getOrderNumber (), 
						 'orderSubitemNumber' => $rs->getOrderSubitemNumber (), 
						 'unitPrice' => $rs->getUnitPrice (), 
						 'batchNumber' => $rs->getBatchNumber (), 
						 'materialNumber' => $rs->getMaterialNumber(),
						 'consignmentBatch' => $rs->getConsignmentBatch(),
						 'purchaseNumber' => $rs->getPurchaseNumber (), 
						 'storePlace' => $rs->getStoreplace (), 
						 'destination' => $rs->getDestination (),
						 'filename' => $rs->getFilenames(),
						 'checkoutBatch' => $rs->getCheckoutBatchs(),
						 'certificateNumber' => $rs->getCertificateNumbers(),
						 'remark' => $rs->getRemarks() ) );
$smarty->assign(fromDate,$fromDate);
$smarty->assign(toDate,$toDate);
$smarty->assign(type,$_POST['group']);
$smarty->display ( 'checkoutTable.html' );
//print_r($rs->getMaterialCode()); echo "<hr>";
//print_r($rs->getBatchNumber()); echo "<hr>";
//print_r($rs->getChukuDate()); echo "<hr>";
//print_r($rs->getChukuNumber()); echo "<hr>";
//print_r($rs->getMaterialCode()); echo "<hr>";
//print_r($rs->getMaterialCode()); echo "<hr>";
?>