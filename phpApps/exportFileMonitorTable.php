<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/MonitorFileAccountant.class.php');
require_once('../includes/SimpleTableExporter.class.php');

try{
$smarty = SmartyManager::getSmarty();
$monitor = new MonitorFileAccountant($_GET['id']);
$monitor->execute();

$query = 'select filename from sss_monitor_file where id = '.$_GET['id'];
DB::query($query);
$row = DB::getResult()->fetch_assoc();
$monitorFilename = $row['filename'];

//echo $monitorFilename.'<br />';
$filename = iconv('utf-8', 'gbk', '监控'.'_('.pathinfo($monitorFilename, PATHINFO_FILENAME).')_'.date('YmdHis').'.xls');
//echo iconv('utf-8', 'gbk', $filename)."<br />";

//die();
$exp = new SimpleTableExporter($filename);
$exp->insertCustomData(array($monitor->getMaterialCode(), $monitor->getShipsClassification(), $monitor->getMaterial(), $monitor->getThickness(), $monitor->getWidth(), $monitor->getLength(),$monitor->getOrderNumber(), $monitor->getOrderSubitemNuber(),$monitor->getUnitPrice(),$monitor->getBatchNumber(), $monitor->getMaterialNumber(),$monitor->getPurchaseNumber(),$monitor->getDestination(),$monitor->getStorePlace(),$monitor->getCertificateNumber(), $monitor->getCheckoutBatch(), $monitor->getConsignmentBatch(), $monitor->getSumCount(), $monitor->getUnRukuCount(), $monitor->getKuzhongCount(), $monitor->getSoldCount()), 
	array('材料代码', '船级', '材质', '厚', '宽', '长', '订单号', '订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','总量', '未入库', '库中', '销售'), 
		'sheet1');
$exp->insertCustomData(array($monitor->getNotInMainMaterialCode(), $monitor->getNotInMainShipsClassification(), $monitor->getNotInMainMaterial(), $monitor->getNotInMainThickness(), $monitor->getNotInMainWidth(), $monitor->getNotInMainLength(),$monitor->getNotInMainOrderNumber(), $monitor->getNotInMainOrderSubitemNuber(),$monitor->getNotInMainUnitPrice(),$monitor->getNotInMainBatchNumber(), $monitor->getNotInMainMaterialNumber(),$monitor->getNotInMainPurchaseNumber(),$monitor->getNotInMainDestination(),$monitor->getNotInMainStorePlace(),$monitor->getNotInMainCertificateNumber(), $monitor->getNotInMainCheckoutBatch(), $monitor->getNotInMainConsignmentBatch()), 
array('材料代码', '船级', '材质', '厚', '宽', '长', '订单号', '订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次'), 
	'sheet2');
$exp->export();
}catch(Exception $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}

?>