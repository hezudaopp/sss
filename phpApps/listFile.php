<?php
require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/DB.class.php');
require_once ('../includes/Verifier.class.php');
require_once ('../includes/Filter.class.php');
require_once ('../includes/ListFileAccountant.class.php');

$smarty = SmartyManager::getSmarty ();

$list = new ListFileAccountant ( $_GET ['id'] );
$smarty->assign ( 'id', $_GET ['id'] );

$list->execute ();

$smarty->assign ( array ('chukuDate' => $list->getChukuDate(),
						'chukuNumber' => $list->getChukuNumber(),
						'materialCode' => $list->getMaterialCode (), 
						'shipsClassification' => $list->getShipsClassification (), 
						'material' => $list->getMaterial (), 
						'thickness' => $list->getThickness (), 
						'width' => $list->getWidth (), 
						'length' => $list->getLength (), 
						'count' => $list->getCount (),
						'unitWeight' => $list->getUnitWeight(),
						'weight' => $list->getWeight(),
						'filename' => $list->getFilename(),
						'orderNumber' => $list->getOrderNumber (), 
						'orderSubitemNumber' => $list->getOrderSubitemNuber (), 
						'unitPrice' => $list->getUnitPrice (), 
						'batchNumber' => $list->getBatchNumber (), 
						'purchaseNumber' => $list->getPurchaseNumber (), 
						'destination' => $list->getDestination (), 
						'storePlace' => $list->getStorePlace (), 
						'remarks' => $list->getRemarks(),
						'checkoutBatch' => $list->getCheckoutBatch(),
						'certificateNumber' => $list->getCertificateNumber() ) );

$smarty->display ( "listFile.html" );
?>