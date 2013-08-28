<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SummaryFileAccountant.class.php');
require_once('../includes/SimpleTableExporter.class.php');

try{
$smarty = SmartyManager::getSmarty();
$summary = new SummaryFileAccountant($_GET['id']);
$summary->execute();

$query = 'select filename from sss_summary_file where id = '.$_GET['id'];
DB::query($query);
$row = DB::getResult()->fetch_assoc();
$summaryFilename = $row['filename'];

//echo $summaryFilename.'<br />';
$filename = iconv('utf-8', 'gbk', '汇总上传文件'.'_('.pathinfo($summaryFilename, PATHINFO_FILENAME).')_'.date('YmdHis').'.xls');
//echo iconv('utf-8', 'gbk', $filename)."<br />";

//die();
$exp = new SimpleTableExporter($filename);
$exp->insertCustomData(array($summary->getShipNumber(), $summary->getSubNumber(), $summary->getConsignmentBatch(), $summary->getRemark()), 
	array('船号','分段号','发货批次','备注'), 
		'sheet1');
$exp->export();
}catch(Exception $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}

?>