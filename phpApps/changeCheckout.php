<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/functions.inc.php');

$smarty = SmartyManager::getSmarty();
if ($_POST['group']=="unsettled") {
	$id = $_POST['id'];
	$type = $_POST['type'];
	$materialCode = $_POST['materialCode'];
	$orderNumber = $_POST['orderNumber'];
	$orderSubitemNumber = $_POST['orderSubitemNumber'];
	try{
		beginTransaction();
		$query1 = "UPDATE `sss_main` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
				where materialCode = '{$materialCode}' and orderNumber = '{$orderNumber}' and orderSubitemNumber = '{$orderSubitemNumber}'";
		$query2 = "UPDATE `sss_fache` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
					where materialCode = '{$materialCode}' and orderNumber = '{$orderNumber}' and orderSubitemNumber = '{$orderSubitemNumber}'";
		$query3 = "UPDATE `sss_fachuan` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
					where materialCode = '{$materialCode}' and orderNumber = '{$orderNumber}' and orderSubitemNumber = '{$orderSubitemNumber}'";
		$query4 = "UPDATE `sss_list` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
					where materialCode = '{$materialCode}' and orderNumber = '{$orderNumber}' and orderSubitemNumber = '{$orderSubitemNumber}'";
		DB::query($query1);
		DB::query($query2);
		DB::query($query3);
		DB::query($query4);
		commit();
	}catch(Exception $e){
		$smarty->assign('errTitle', '操作错误');
		$smarty->assign('errMsg', $e);
		$smarty->display('error.html');
		rollback();
		die();
	}
	
	$smarty->assign('successTitle','修改成功');
}else {
	$smarty->assign('successTitle','没有做修改');
}
	$smarty->display('success.html');

?>