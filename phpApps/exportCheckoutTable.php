<?php
require_once ('../includes/SmartyManager.class.php');
require_once ("../includes/Verifier.class.php");
require_once ("../includes/Checkout.class.php");
require_once ('../includes/functions.inc.php');
require_once('../includes/SimpleTableExporter.class.php');

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "256M");


$rs = new Checkout ( $_GET['fromDate'], $_GET['toDate']);
$rs->findCommon ($_GET['type']);

	
	$filenameStr ="";
	if ($_GET['type'] == "settled") {
		$filenameStr .= "完成已结算-";
	}elseif ($_GET['type'] == "unsettled") {
		$filenameStr .= "完成未结算-";
	}else{
		$filenameStr .= "完成-";
	}
	
	if ((!empty ( $_GET['fromDate'] )) || (!empty ( $_GET['toDate'] ))) {
		$filenameStr .= '出库日期-' . $_GET['fromDate'] . '到' . $_GET['toDate'];
	}

	$filename = $filenameStr . '.xls';
//	generateHttpExcelByCols ( $data, $filename );
	$exp = new SimpleTableExporter($filename);
	$exp->insertCustomData(array($rs->getChukuDate(),
							 $rs->getChukuNumber(),
							 $rs->getMaterialCode(), 
							 $rs->getShips(), 
							 $rs->getMaterial(), 
							 $rs->getThickness(), $rs->getWidth(), $rs->getLength(),
							 $rs->getCount(),
							 $rs->getUnitWeight(),
							 $rs->getWeight(),
							 $rs->getOrderNumber(), 
							 $rs->getOrderSubitemNumber(),
							 $rs->getUnitPrice (), 
							 $rs->getPurchaseNumber (),
							 $rs->getBatchNumber (),  
							 $rs->getMaterialNumber(),
							 $rs->getConsignmentBatch(),
							 $rs->getDestination (),
							 $rs->getStoreplace (),
							 $rs->getCheckoutBatchs(),
							 $rs->getCertificateNumbers(), 
							 $rs->getFilenames(),
							 $rs->getRemarks()), 
					   array('日期', '车号|船号', '材料代码', '船级', '材质', '厚', '宽', '长', 
					   		'数量','单重','重量',
					   		 '订单号', '订单子项号','受订单价','购单号','批号','物料号','发货批次',
					   		 '目的地','库存地','结算批号','证书号','文件名','备注'), 
							'sheet1');
	$exp->export();
?>