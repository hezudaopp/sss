<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');

$smarty = SmartyManager::getSmarty();

$smarty->assign('filename', $_GET['filename']);
$smarty->display('filenameEdit.html');
?>