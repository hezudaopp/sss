<?php
require ('../includes/functions.inc.php');
//require_once('../includes/Exceptions/MyException.class.php');
require_once ('../includes/Exceptions/AppExceptions.class.php');

$id = $_POST ['id'];
$type = $_POST ['type'];

try {
	executeSQL ( $id, $type );
} catch ( Exception $e ) {
	$smarty = SmartyManager::getSmarty ();
	$smarty->assign ( 'errMsg', $e );
	$smarty->display ( 'error.tpl' );
	exit ();
}

if ($type == 'cancel') {
	header ( "Location: ../index.html" );
}

$smarty = SmartyManager::getSmarty ();
$smarty->assign ( 'successMsg', '提交成功' );
$smarty->assign ( 'successTitle', '成功提交' );
$smarty->display ( 'success.tpl' );
?>