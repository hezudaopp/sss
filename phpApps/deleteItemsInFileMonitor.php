<?php
require_once ('../includes/DB.class.php');
require_once ("../includes/exceptions/AppExceptions.class.php");
require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/MonitorFileAccountant.class.php');
require_once ("../includes/LogInserter.class.php");

$smarty = SmartyManager::getSmarty ();

try {
	$monitor = new MonitorFileAccountant ( $_GET ['id'] );
	$smarty->assign ( 'id', $_GET ['id'] );
	$monitor->executeDelete ();
	$query = "select filename from sss_monitor_file where id = {$_GET['id']}";
	DB::query ( $query );
	$row = DB::getResult ()->fetch_assoc ();
	LogInserter::logForDelete ( '删除监控文件（' . $row ['filename'] . '）监控的所有数据' );

} catch ( Exception $e ) {
	$smarty->assign ( "errTitle", '删除监控的条目失败' );
	$smarty->assign ( 'errorType', 'deleteInMonitor' );
	$smarty->assign ( 'errMsg', $e );
	$smarty->display ( 'error.html' );
	die ();
}

$smarty->assign ( "successTitle", '删除监控的条目成功' );
$smarty->assign ( 'successType', 'deleteInMonitor' );
$smarty->assign ( 'successMsg', '删除监控的条目成功' );
$smarty->display ( 'success.html' );
?>