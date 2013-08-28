<?php
require('../includes/functions.inc.php');
require('../includes/SSS_SummaryExcelReader.class.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'summaryFile';

//不可缺少的列
$obligatoCols = array(
);

//电子表格和数据库表中的列的对应关系
$tableCols = array(
		'船号' 	=> 	'shipNumber',
		'分段号' 		=> 	'subNumber',
		'发货批次'	=>	'consignmentBatch',
		'备注'	=>	'remark'		
);

//对比时用来比较的列
$compareCols = array();
$record = Filter::forDBInsertion($_POST['record'], true);

/************************************************************
 * 文件上传
 ***********************************************************/
try{
	//$file 在本程序中用作了全局变量，用来在需要filename的函数中使用
	$file = saveUploadedFile($formFilename);
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	exit();
}

try{
	beginTransaction();
	
	//然后read上传的表格的信息，将其中的数据存入数据库中
	$sssReader = new SSS_SummaryExcelReader();
	$sssReader->read($file->getNewPath());

	$sssReader->obligatoColumns = $obligatoCols;
	$sssReader->tableColumns = $tableCols;
	//$sssReader->compareColumns = $compareCols;

	$warnMsg = "";
	try{
		$sssReader->checkMyFirstRow();
	}catch(UnknownColumnIndex $e){
		//这个函数可能抛出两个异常，一个是LackSomeColumn
		//另一个是UnknownColumnIndex。
		//对于UnknownColumnIndex只需要一个警告提醒，而不会像LackSomeColumn一样停止运行
		$warnMsg .= $e->getMessage();
	}
	
	if($date = $sssReader->myHaveUploaded()){
		throw new UploadSameFileException($file->getFilename(), $date);
	}else {
		$sssReader->saveFileInfo($record);
	}
	
	$sssReader->myReadIntoDB();
	LogInserter::logForInsert("导入汇总文件：{$file->getFilename()}");
}catch(DBQueryException $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errorType', 'summaryUpload');
	$smarty->assign("errTitle", '上传汇总文件失败1');
	$smarty->display('error.html');
	
	rollback();
	exit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errorType', 'summaryUpload');
	$smarty->assign("errTitle", '上传汇总文件失败2');
	$smarty->display('error.html');
	rollback();
	exit();
}

$smarty->assign('successMsg', $file->getFilename().'上传成功');
$smarty->assign("successTitle", '上传汇总文件成功');
$smarty->assign('successType', 'summaryUpload');
if(isset($warnMsg)){
	$smarty->assign('warnMsg', $warnMsg);
}
$smarty->display('success.html');
commit();
?>