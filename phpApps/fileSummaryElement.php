<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SummaryElementFileAccountant.class.php');

$smarty = SmartyManager::getSmarty();

$summaryElement = new SummaryElementFileAccountant($_GET['id']);
$smarty->assign('id', $_GET['id']);
$smarty->assign('filename', $_GET['filename']);
$summaryElement->execute();

$smarty->assign(array(
		'fileId' => $summaryElement->getFileId(),
		'manufactory' => $summaryElement->getManufactory(),
		'storePlace' => $summaryElement->getStorePlace(),
		'destination' => $summaryElement->getDestination(),
		'remark' => $summaryElement->getRemark()
	));

$smarty->display("fileSummaryElement.html");
?>