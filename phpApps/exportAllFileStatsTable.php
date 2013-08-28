<?php

require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/functions.inc.php');
require_once('../includes/AllFileStateAccountant.class.php');

if(isset($_GET['all'])){
	$account = new AllFileStateAccountant(true);
	$account->execute();
	$errors = array();
	$mcCount = $account->getDistinctMaterialCodeCount();
	$finishedCount = $account->getFinishedCount();
	foreach($mcCount as $key => $val){
		if($mcCount[$key] == $finishedCount[$key]){
			array_push($errors, '全部完成');
		}else{
			array_push($errors, '');
		}
	}

	$data = array(
		'文件名' => $account->getFilename(),
		'上传时间' => $account->getUploadTime(),
		'条目总数' => $account->getDistinctMaterialCodeCount(),
		'数量'	=> $account->getAllCount(),
		'重量'	=> $account->getAllWeight(),
	/*	'加片数' => $jiapianCounts,*/
		'完成数' => $account->getFinishedCount(),
		'未完成数' => $account->getUnFinishedCount(),
		'备注' => $errors
	);
}else{
	$account = new AllFileStateAccountant();
	$account->execute();
	$data = array(
		'文件名' => $account->getFilename(),
		'上传时间' => $account->getUploadTime(),
		'条目总数' => $account->getDistinctMaterialCodeCount(),
		'数量'	=> $account->getAllCount(),
		'重量'	=> $account->getAllWeight()
	);
}
$filename = '物流状况-'.date('YmdHis').'.xls';
generateHttpExcelByCols($data,$filename);

?>