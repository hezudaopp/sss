<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/functions.inc.php');

$filename = $_POST['filename'];
$oldFilename = $_POST['oldFilename'];
$smarty = SmartyManager::getSmarty();
try{
	
$consignmentBatch = Filter::forDBInsertion($_POST['consignmentBatch']);
	
beginTransaction();
	$query = "select filename, uploadTime from sss_main where filename = '$filename'";
	$result = DB::query($query);
	if(mysqli_num_rows($result) >= 1){
		$row = $result->fetch_assoc();
		throw new UploadSameFileException($filename, $row['uploadTime']);
	}else{
		$query = "update sss_main set filename = '$filename' where filename = '$oldFilename';";
	}
	DB::query($query);
	commit();
}catch(Exception $e){
	$smarty->assign('errTitle', '操作错误');
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	rollback();
	die();
}

$smarty->assign('successTitle','修改成功');
$smarty->display('success.html');

?>