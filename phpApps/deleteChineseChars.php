<?php
require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/functions.inc.php');
require_once ('../includes/exceptions/AppExceptions.class.php');

$mc = $_POST ['materialCode'];
$orderNumber = $_POST ['orderNumber'];
$orderSubitemNumber = $_POST ['orderSubitemNumber'];

beginTransaction ();

$query = "select * from sss_main
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'
			";
findAndLog ( $query, 'delete' );

$query = "delete from sss_main
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'";
DB::query ( $query );

$query = "select * from sss_fache
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'";
findAndLog ( $query, 'delete' );

$query = "delete from sss_fache
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'";
DB::query ( $query );

$query = "select * from sss_fachuan
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'";
findAndLog ( $query, 'delete' );

$query = "delete from sss_fachuan
		where materialCode = '$mc'
			and orderNumber = '$orderNumber'
			and orderSubitemNumber = '$orderSubitemNumber'";
DB::query ( $query );
commit ();
$smarty = SmartyManager::getSmarty ();
$smarty->assign ( 'successTitle', '删除成功' );
$smarty->display ( 'success.html' );
?>