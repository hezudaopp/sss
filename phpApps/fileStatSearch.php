<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/SpecialFileStateAccountant.class.php');
require_once('../includes/AllFileStateAccountant.class.php');

$smarty = SmartyManager::getSmarty();
try{

if(isset($_GET['filename'])){
	$filename = $_GET['filename'];
	$smarty->assign('filename', $filename);

	defined("DEBUG")?$pre=getMicroTime():null;

	$account = new SpecialFileStateAccountant($filename);
	$account->execute();

	$smarty->assign( array(
		'materialCode' => $account->getMaterialCode(),
		'shipsClassification' => $account->getShipsClassification(),
		'material' => $account->getMaterial(),
		'thickness' => $account->getThickness(),
		'width' => $account->getWidth(),
		'length' => $account->getLength(),
		'sumCount' => $account->getSumCount(),
		'kuzhong' => $account->getKuzhongCount(),
		'unRuku' => $account->getUnRukuCount(),
		'sold' => $account->getSoldCount(),
		'orderNumber' => $account->getOrderNumber(),
		'orderSubitemNumber' => $account->getOrderSubitemNuber(),
		'unitPrice' => $account->getUnitPrice(),
		'batchNumber' => $account->getBatchNumber(),
		'purchaseNumber' => $account->getPurchaseNumber(),
		'destination' => $account->getDestination(),
		'storePlace' => $account->getStorePlace(),
		'checkoutBatch' => $account->getCheckoutBatch(),
		'certificateNumber' => $account->getCertificateNumber(),
		'materialNumber' => $account->getMaterialNumber(),
		'consignmentBatch' => $account->getConsignmentBatch()
		));
	if(defined("DEBUG")){
		$inteval = getMicroTime()-$pre;
		echo "a speacial file state Time:".$inteval.'<br />';
	}
	$smarty->display('fileStat.html');

}else{

	defined("DEBUG")?$pre=getMicroTime():null;

	if(isset($_GET['all'])){
		$account = new AllFileStateAccountant(true);
		$account->execute();
		$errors = array();
		$mcCount = $account->getDistinctMaterialCodeCount();
		$finishedCount = $account->getFinishedCount();
		foreach($mcCount as $key => $val){
			if($mcCount[$key] == $finishedCount[$key]){
				array_push($errors, 1);
			}else{
				array_push($errors, '');
			}
		}
		if(defined("DEBUG")){
			$inteval = getMicroTime()-$pre;
			echo "get all files'state Time:".$inteval.'<br />';
		}
		$smarty->assign('filename', $account->getFilename());
		$smarty->assign('uploadTime', $account->getUploadTime());
		$smarty->assign('count', $account->getDistinctMaterialCodeCount());
		$smarty->assign('finished', $account->getFinishedCount());
		$smarty->assign('unfinished', $account->getUnFinishedCount());
		$smarty->assign('error', $errors);
		$smarty->assign('all',true);
		$smarty->display('allFileStats.html');
	}else{
		$account = new AllFileStateAccountant(false);
		$account->execute();

		if(defined("DEBUG")){
			$inteval = getMicroTime()-$pre;
			echo "get all files'state Time:".$inteval.'<br />';
		}
		$smarty->assign('filename', $account->getFilename());
		$smarty->assign('uploadTime', $account->getUploadTime());
		$smarty->assign('count', $account->getDistinctMaterialCodeCount());
		$smarty->assign('allCount', $account->getAllCount());
		$smarty->assign('allWeight', $account->getAllWeight());
		$smarty->display('allFileStats.html');

	}
}

}catch(Exception $e){
	$smarty->assign('errTitle', '出现错误');
	$smarty->assign('errMsg', '数据库查询和运算时出现错误');
	$smarty->display('error.html');
}
?>