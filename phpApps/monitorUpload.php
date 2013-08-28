<?php
require('../includes/functions.inc.php');
require('../includes/SSS_MonitorExcelReader.class.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'monitorFile';

//不可缺少的列
$obligatoCols = array(
);

//电子表格和数据库表中的列的对应关系
$tableCols = array(
		'材料代码' 	=> 	'materialCode',
		'船级' 		=> 	'shipsClassification',
		'材质' 		=> 	'material',
		'厚' 		=> 	'thickness',
		'宽' 		=> 	'width',
		'长' 		=> 	'length',
		'数量' 		=> 	'count',
		'订单号' 	=> 	'orderNumber',
		'订单子项号' 	=> 	'orderSubitemNumber',
		'受订单价' 		=> 	'unitPrice',
		'批号' 		=> 	'batchNumber',
		'购单号' 		=> 	'purchaseNumber',
		'目的地' 		=> 	'destination',
		'库存地' 	=> 	'storePlace',
		'物料号'		=>	'materialNumber',
		'发货批次'	=>	'consignmentBatch'		
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
	$sssReader = new SSS_MonitorExcelReader();
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
	
	//这个放在saveFileInfo下面是因为如果放在上边的话，保存file信息后获得不到inser_id。
	//beginTransaction();
	$mcs =array();
	if($mcs = $sssReader->haveSameMaterialCode()){
		$warnMsg .= "<br />表格上传完成，但是程序检查到上传的文件中有重复的材料代码：'".join("', '", $mcs)."', 如果你确认这里出现了问题，可以直接在系统中进行修改或者删除刚才上传的文件";
	}

	$sssReader->myReadIntoDB();
	LogInserter::logForInsert("导入监控文件：{$file->getFilename()}");
}catch(DBQueryException $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errorType', 'monitorUpload');
	$smarty->assign("errTitle", '上传监控文件失败1');
	$smarty->display('error.html');
	
	rollback();
	exit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errorType', 'monitorUpload');
	$smarty->assign("errTitle", '上传监控文件失败2');
	$smarty->display('error.html');
	rollback();
	exit();
}

$smarty->assign('successMsg', $file->getFilename().'上传成功');
$smarty->assign("successTitle", '上传监控文件成功');
$smarty->assign('successType', 'monitorUpload');
if(isset($warnMsg)){
	$smarty->assign('warnMsg', $warnMsg);
}
$smarty->display('success.html');
commit();
?>