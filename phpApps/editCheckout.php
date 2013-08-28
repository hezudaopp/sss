<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');

$id=$_GET['id'];

$smarty = SmartyManager::getSmarty();
$smarty->assign('materialCode', $_GET['materialCode']);
$smarty->assign('orderNumber', $_GET['orderNumber']);
$smarty->assign('orderSubitemNumber', $_GET['orderSubitemNumber']);
$smarty->assign('certificateNumber', $_GET['certificateNumber']);
$smarty->assign('checkoutBatch', $_GET['checkoutBatch']);
$smarty->display('editCheckout.html');
?>