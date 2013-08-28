<?php
require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/SpecialFileStateAccountant.class.php');

	$filename = $_GET['filename'];

	$account = new SpecialFileStateAccountant($filename);
	$account->execute();

	$remarks = array();
	$unRukus = $account->getUnRukuCount();
	$solds = $account->getSoldCount();
	$sumCounts = $account->getSumCount();
	foreach($sumCounts as $key => $val){
		if($unRukus[$key] == 0){
			array_push($remarks, '完成');
		}else if($sumCounts[$key] < $solds[$key]){
			array_push($remarks, '错误：销售总数大于总量');
		}else if($unRukus[$key] < 0){
			array_push($remarks, '错误：入库数大于计划总量');
		}else{
			array_push($remarks, "");
		}
	}

	$data = array(
		'材料代码' => $account->getMaterialCode(),
		'船级' => $account->getShipsClassification(),
		'材质' => $account->getMaterial(),
		'厚' => $account->getThickness(),
		'宽' => $account->getWidth(),
		'长' => $account->getLength(),
		'总量' => $account->getSumCount(),
		'未入库' => $account->getUnRukuCount(),
		'库中' => $account->getKuzhongCount(),
		'已销售' => $account->getSoldCount(),
		'订单号' => $account->getOrderNumber(),
		'订单子项号' => $account->getOrderSubitemNuber(),
		'受订单价' => $account->getUnitPrice(),
		'批号' => $account->getBatchNumber(),
		'物料号' => $account->getMaterialNumber(),
		'购单号' => $account->getPurchaseNumber(),
		'目的地' => $account->getDestination(),
		'库存地' => $account->getStorePlace(),
		'证书号' => $account->getCertificateNumber(),
		'结算批号' => $account->getCheckoutBatch(),
		'发货批次' => $account->getConsignmentBatch(),
		'备注' => $remarks
	);

	$filename = pathinfo($filename, PATHINFO_FILENAME).'物流状况-'.date('YmdHis').'.xls';
	generateHttpExcelByCols($data,$filename);
?>