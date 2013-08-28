<?php
require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/functions.inc.php');
require_once ('../includes/exceptions/AppExceptions.class.php');
require_once ('../includes/LogInserter.class.php');

$smarty = SmartyManager::getSmarty ();
try {
	$mc = $_POST ['materialCode'];
	beginTransaction ();
	
	//先记录
	$query = "select * from sss_fache where materialCode = '$mc'";
	DB::query ( $query );
	$result = DB::getResult ();
	$rows = array ();
	while ( $row = $result->fetch_assoc () ) {
		array_push ( $rows, $row );
	}
	LogInserter::logForDelete ( $rows );
	$query = "select * from sss_fachuan where materialCode = '$mc'";
	DB::query ( $query );
	$result = DB::getResult ();
	$rows = array ();
	while ( $row = $result->fetch_assoc () ) {
		array_push ( $rows, $row );
	}
	LogInserter::logForDelete ( $rows );
	$query = "select * from sss_main where materialCode = '$mc'";
	DB::query ( $query );
	$result = DB::getResult ();
	$rows = array ();
	while ( $row = $result->fetch_assoc () ) {
		array_push ( $rows, $row );
	}
	LogInserter::logForDelete ( $rows );
	
	deleteMaterialCode ( $mc );
	commit ();
} catch ( Exception $e ) {
	rollback ();
	$smarty->assign ( 'errMsg', '删除时，操作数据库出现错误,错误原因：' . $e );
	$smarty->assign ( 'errTitle', '出现错误' );
	$smarty->display ( 'error.html' );
	die ();
}

$smarty->assign ( 'successMsg', '删除成功' );
$smarty->assign ( 'successTitle', '成功' );
$smarty->display ( 'success.html' );

?>