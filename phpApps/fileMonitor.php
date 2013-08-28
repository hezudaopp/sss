<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/MonitorFileAccountant.class.php');

$smarty = SmartyManager::getSmarty();

$monitor = new MonitorFileAccountant($_GET['id']);
$smarty->assign('id', $_GET['id']);
$smarty->assign('filename', $_GET['filename']);
$monitor->execute();

$smarty->assign(array(
		'materialCode' => $monitor->getMaterialCode(),
		'shipsClassification' => $monitor->getShipsClassification(),
		'material' => $monitor->getMaterial(),
		'thickness' => $monitor->getThickness(),
		'width' => $monitor->getWidth(),
		'length' => $monitor->getLength(),
		'orderNumber' => $monitor->getOrderNumber(),
		'orderSubitemNumber' => $monitor->getOrderSubitemNuber(),
		'unitPrice'	=> $monitor->getUnitPrice(),
		'batchNumber' => $monitor->getBatchNumber(),
		'purchaseNumber' => $monitor->getPurchaseNumber(),
		'destination' => $monitor->getDestination(),
		'storePlace' => $monitor->getStorePlace(),
		'certificateNumber' => $monitor->getCertificateNumber(),
		'checkoutBatch' => $monitor->getCheckoutBatch(),
		'materialNumber' => $monitor->getMaterialNumber(),
		'consignmentBatch' => $monitor->getConsignmentBatch(),
		'sumCount' => $monitor->getSumCount(),
		'unRukuCount' => $monitor->getUnRukuCount(),
		'soldCount' => $monitor->getSoldCount(),
		'kuzhongCount' => $monitor->getKuzhongCount(),

		'notInMainMaterialCode' => $monitor->getNotInMainMaterialCode(),
		'notInMainShipsClassification' => $monitor->getNotInMainShipsClassification(),
		'notInMainMaterial' => $monitor->getNotInMainMaterial(),
		'notInMainThickness' => $monitor->getNotInMainThickness(),
		'notInMainWidth' => $monitor->getNotInMainWidth(),
		'notInMainLength' => $monitor->getNotInMainLength(),
		'notInMainOrderNumber' => $monitor->getNotInMainOrderNumber(),
		'notInMainOrderSubitemNumber' => $monitor->getNotInMainOrderSubitemNuber(),
		'notInMainUnitPrice'	=> $monitor->getNotInMainUnitPrice(),
		'notInMainBatchNumber' => $monitor->getNotInMainBatchNumber(),
		'notInMainPurchaseNumber' => $monitor->getNotInMainPurchaseNumber(),
		'notInMainDestination' => $monitor->getNotInMainDestination(),
		'notInMainStorePlace' => $monitor->getNotInMainStorePlace(),
		'notInMainCertificateNumber' => $monitor->getNotInMainCertificateNumber(),
		'notInMainCheckoutBatch' => $monitor->getNotInMainCheckoutBatch(),
		'notInMainMaterialNumber' => $monitor->getNotInMainMaterialNumber(),
		'notInMainConsignmentBatch' => $monitor->getNotInMainConsignmentBatch()
	));

$smarty->display("fileMonitor.html");
?>