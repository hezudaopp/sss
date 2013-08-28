<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/exceptions/AppExceptions.class.php');

$filename = $_POST['filename'];

$smarty = SmartyManager::getSmarty();
try{
	//deleteOnlyByFilename($filename);
	LogInserter::logForDelete("更新文件名中所有“批号，物料号，目的地，库存地，结算批号，证书号”的信息，文件名：$filename");
//	deleteAllMaterialCodesInFile($filename);
	updateInFile($filename);
}catch (Exception $e){
	$smarty->assign('errMsg', '更新时，操作数据库出现错误,错误原因：'.$e);
	$smarty->assign('errTitle', '出现错误');
	$smarty->display('error.html');
	rollback();
	die();
}

$smarty->assign('successMsg', '更新成功');
$smarty->assign('successTitle', '成功');
$smarty->display('success.html');
?>