<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/functions.inc.php');


$fn = $_POST['filename'];
$ut = $_POST['uploadTime'];
$table = 'sss_'.$_POST['type'];

$smarty = SmartyManager::getSmarty();

beginTransaction();
$query = "delete from $table where uploadTime = '$ut' and filename = '$fn'";
try{
	LogInserter::logForDelete("删除文件名中所有信息，文件名：$fn");
	DB::query($query);
	commit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '删除时发现数据库错误');
	$smarty->display('error.html');
	rollback();
	die();
}

$smarty->assign('successTitle', '删除文件成功');
$smarty->assign('successMsg','');
$smarty->display('success.html');
?>