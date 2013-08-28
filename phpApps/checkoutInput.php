<?php
require_once ('../includes/SmartyManager.class.php');
$smarty = SmartyManager::getSmarty ();

if (isset ( $_GET ['err'] )) {
	if ($_GET ['err'] == 'format1') {
		$smarty->assign ( "errMsg", '第一个输入框里的时间格式不正确' );
	} else if ($_GET ['err'] == 'format2') {
		$smarty->assign ( "errMsg", "第二个输入框里的时间格式不正确" );
	}
}

if (isset ( $_GET ['fromDate'] )) {
	$smarty->assign ( "fromDate", $_GET ['fromDate'] );
}

if (isset ( $_GET ['toDate'] )) {
	$smarty->assign ( "toDate", $_GET ['toDate'] );
}


$smarty->display ( "checkoutInput.html" );

?>