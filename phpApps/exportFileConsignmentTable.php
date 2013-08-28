<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/ConsignmentFileAccountant.class.php');
require_once('../includes/SimpleTableExporter.class.php');

try{
$smarty = SmartyManager::getSmarty();
$consignment = new ConsignmentFileAccountant($_GET['id']);
$consignment->execute();

$query = 'select filename from sss_consignment_file where id = '.$_GET['id'];
DB::query($query);
$row = DB::getResult()->fetch_assoc();
$consignmentFilename = $row['filename'];

//echo $consignmentFilename.'<br />';
$filename = iconv('utf-8', 'gbk', '发货批次'.'_('.pathinfo($consignmentFilename, PATHINFO_FILENAME).')_'.date('YmdHis').'.xls');
//echo iconv('utf-8', 'gbk', $filename)."<br />";

//die();
$exp = new SimpleTableExporter($filename);
$exp->insertCustomData(array($consignment->getMaterialCode(),
							 $consignment->getShipNumber(),
							 $consignment->getSubsectionNumber(),
							 $consignment->getOrderNumber(), 
							 $consignment->getOrderSubitemNuber(),
							 $consignment->getPurchaseNumber(),
							 $consignment->getMaterialNumber(),
							 $consignment->getConsignmentBatch(),
							 $consignment->getRemark()), 
	array('材料代码','船号', '分段号', '订单号', '订单子项号','购单号','物料号','发货批次', '备注'), 
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