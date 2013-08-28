<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SummaryFileAccountant.class.php');

$smarty = SmartyManager::getSmarty();

$summary = new SummaryFileAccountant($_GET['id']);
$smarty->assign('id', $_GET['id']);
$smarty->assign('filename', $_GET['filename']);
$summary->execute();

$smarty->assign(array(
		'fileId' => $summary->getFileId(),
		'shipNumber' => $summary->getShipNumber(),
		'subNumber' => $summary->getSubNumber(),
		'consignmentBatch' => $summary->getConsignmentBatch(),
		'remark' => $summary->getRemark()
	));

$smarty->display("fileSummary.html");
?>