<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/ConsignmentFileAccountant.class.php');

$smarty = SmartyManager::getSmarty();

$consignment = new ConsignmentFileAccountant($_GET['id']);
$smarty->assign('id', $_GET['id']);

$consignment->execute();

$smarty->assign(array('materialCode' => $consignment->getMaterialCode(),
		'shipNumber' => $consignment->getShipNumber(),
		'subsectionNumber' => $consignment->getSubsectionNumber(),
		'orderNumber' => $consignment->getOrderNumber(),
		'orderSubitemNumber' => $consignment->getOrderSubitemNuber(),
		'purchaseNumber' => $consignment->getPurchaseNumber(),
		'materialNumber' => $consignment->getMaterialNumber(),
		'consignmentBatch' => $consignment->getConsignmentBatch(),
		'remark' => $consignment->getRemark()
	));

$smarty->display("fileConsignment.html");
?>