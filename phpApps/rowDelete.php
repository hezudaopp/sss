<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/functions.inc.php');

$type = $_POST['type'];
$id = $_POST['id'];

beginTransaction();
$query = "";
if($type == 'main'){
	$query = "select * from sss_main where id = $id";
	findAndLog($query,'delete');
	
	$query = "delete from sss_main where id = $id";
}else if($type == 'fache'){
	$query = "select * from sss_fache where id = $id";
	findAndLog($query,'delete');
	
	$query = "delete from sss_fache where id = $id";
}else if($type == 'fachuan'){
	$query = "select * from sss_fachuan where id = $id";
	findAndLog($query,'delete');
	
	$query = "delete from sss_fachuan where id = $id";
}

$smarty = SmartyManager::getSmarty();
try{
/*	var_dump($query);*/
	DB::query($query);
	commit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '删除时出现错误');
	$smarty->display('error.html');
	rollback();
	die();
}

$smarty->assign('successTitle','删除成功');
$smarty->assign('successMsg', '条目成功删除');
$smarty->display('success.html');

?>