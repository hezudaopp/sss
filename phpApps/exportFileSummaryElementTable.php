<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SummaryElementFileAccountant.class.php');
require_once('../includes/SimpleTableExporter.class.php');

try{
$smarty = SmartyManager::getSmarty();
$summaryElement = new SummaryElementFileAccountant($_GET['id']);
$summaryElement->execute();

$query = 'select filename from sss_summary_element_file where id = '.$_GET['id'];
DB::query($query);
$row = DB::getResult()->fetch_assoc();
$summaryElementFilename = $row['filename'];

//echo $summaryElementFilename.'<br />';
$filename = iconv('utf-8', 'gbk', '汇总因素上传文件'.'_('.pathinfo($summaryElementFilename, PATHINFO_FILENAME).')_'.date('YmdHis').'.xls');
//echo iconv('utf-8', 'gbk', $filename)."<br />";

//die();
$exp = new SimpleTableExporter($filename);
$exp->insertCustomData(array($summaryElement->getManufactory(), $summaryElement->getStorePlace(), $summaryElement->getDestination(), $summaryElement->getRemark()), 
	array('厂家','库存地','目的地','备注'), 
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