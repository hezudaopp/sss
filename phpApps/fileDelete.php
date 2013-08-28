<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/exceptions/AppExceptions.class.php');

$filename = $_POST['filename'];

$smarty = SmartyManager::getSmarty();
try{
	//deleteOnlyByFilename($filename);
	LogInserter::logForDelete("删除文件名中所有“材料代码+订单号+订单子项号”的信息，文件名：$filename");
	//deleteAllMaterialCodesInFile($filename);
	deleteAllMaterialCodesAndOrdersInFile($filename);
}catch (Exception $e){
	$smarty->assign('errMsg', '删除时，操作数据库出现错误,错误原因：'.$e);
	$smarty->assign('errTitle', '出现错误');
	$smarty->display('error.html');
	rollback();
	die();
}

$smarty->assign('successMsg', '删除成功');
$smarty->assign('successTitle', '成功');
$smarty->display('success.html');
?>