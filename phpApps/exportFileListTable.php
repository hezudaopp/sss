<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/ListFileAccountant.class.php');
require_once('../includes/SimpleTableExporter.class.php');

try{
$smarty = SmartyManager::getSmarty();
$list = new ListFileAccountant($_GET['id']);
$list->execute();

$query = 'select filename from sss_list_file where id = '.$_GET['id'];
DB::query($query);
$row = DB::getResult()->fetch_assoc();
$listFilename = $row['filename'];

//echo $listFilename.'<br />';
$filename = iconv('utf-8', 'gbk', '结算清单'.'_('.pathinfo($listFilename, PATHINFO_FILENAME).')_'.date('YmdHis').'.xls');
//echo iconv('utf-8', 'gbk', $filename)."<br />";

//die();
$exp = new SimpleTableExporter($filename);
$exp->insertCustomData(array($list->getMaterialCode(),
							 $list->getChukuDate(),
							 $list->getChukuNumber(), 
							 $list->getShipsClassification(), 
							 $list->getMaterial(), 
							 $list->getThickness(), $list->getWidth(), $list->getLength(),
							 $list->getcount(),
							 $list->getUnitWeight(),
							 $list->getWeight(),
							 $list->getFilename(),
							 $list->getOrderNumber(), 
							 $list->getOrderSubitemNuber(),
							 $list->getUnitPrice(),
							 $list->getBatchNumber(),
							 $list->getPurchaseNumber(),
							 $list->getDestination(),
							 $list->getStorePlace(), 
							 $list->getCertificateNumber(),
							 $list->getCheckoutBatch(),
							 $list->getRemarks()), 
	array('材料代码','日期','车号|船号', '船级', '材质', '厚', '宽', '长',
		  '数量', '单重', '重量','文件名', '订单号', '订单子项号','受订单价','批号','购单号','目的地','库存地',
		  '证书号','结算批号','备注'), 
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