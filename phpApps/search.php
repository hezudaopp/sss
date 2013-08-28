<?php

require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');


$smarty = SmartyManager::getSmarty();

$stypes = array('faNumber'=>'车号、船号',
		'shipsClassification' => '船级',
		'materialCode' => '材料代码',
		'material' => '材质',
		'filename' => '上传时的文件名',
		'size' => '尺寸',
		'faDate' => '发车、发船日期',
		'uploadTime' => '上传时间',
		'filestat' => '物流状况',
		'jiapian' => '加片状况',
		'biangang' => '扁钢/舾装',
		'chineseChars' => '其他汉字材料代码'
		);
if(isset($_GET['stype']) && in_array($_GET['stype'], array_keys($stypes))){
	if($_GET['stype'] == 'materialCode'){
		$smarty->assign('simple', false);
		$smarty->assign('filestat', false);
		if(isset($_GET['orderNumber'])){
			$smarty->assign('haveOrderNumber', true);
			$smarty->assign('orderNumber', $_GET['orderNumber']);
			$smarty->assign('orderSubitemNumber', $_GET['orderSubitemNumber']);
		}
	}else if($_GET['stype'] == 'filestat'){
		$smarty->assign('filestat', true);
	}else if($_GET['stype'] == 'jiapian'){
		if(isset($_GET['orderNumber'])){
			$smarty->assign('jiapianDetail', true);
			$smarty->assign('orderNumber', $_GET['orderNumber']);
			$smarty->assign('orderSubitemNumber', $_GET['orderSubitemNumber']);
		}else{
			$smarty->assign('jiapianStats', true);
		}
	}else if ($_GET['stype'] == 'biangang') {
		$smarty->assign('biangangStats',true);
	}
	else if($_GET['stype'] == 'chineseChars'){
		if(isset($_GET['materialCode'])){
			$smarty->assign('chineseCharsDetail', true);
			$smarty->assign('materialCode', $_GET['materialCode']);
			$smarty->assign('orderNumber', $_GET['orderNumber']);
			$smarty->assign('orderSubitemNumber', $_GET['orderSubitemNumber']);
		}else{
			$smarty->assign('chineseCharsStats', true);
		}
	}
	else{
		$smarty->assign('simple', true);
	}

	$smarty->assign($_GET['stype'].'Check', true);
	$smarty->assign('stype', $_GET['stype']);
	$smarty->assign('keyname', isset($_GET['keyname'])?$_GET['keyname']:"");
	$smarty->assign('searchType', ' 按'.$stypes[$_GET['stype']].'查询的结果：');
	$smarty->display('main.html');
	die();
}else{
	$errMsg = '还未支持当前这个查询';
	$smarty->assign('errTitle', '搜索出现个小问题');
	$smarty->assign('errMsg', $errMsg);
	$smarty->display('error.html');
	die();
}

?>