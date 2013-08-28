<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ConsignmentExcelReader.class.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'consignmentFile';

//不可缺少的列
$obligatoCols = array(
	'序号','材料代码','船号','分段号','订单号','订单子项号','购单号','发货批次','备注'
);

//电子表格和数据库表中的列的对应关系
$tableCols = array(
		'材料代码' 	=> 	'materialCode',
		'船号'	=> 'shipNumber',
		'分段号'	=> 'subsectionNumber',
		'订单号' 	=> 	'orderNumber',
		'订单子项号' 	=> 	'orderSubitemNumber',
		'购单号' 		=> 	'purchaseNumber',
		'发货批次'	=>	'consignmentBatch',
		'备注' => 'remark'		
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
	$sssReader = new SSS_ConsignmentExcelReader();
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
	
	$sssReader->checkShipNumberAndSubsectionNumber();

	if($date = $sssReader->myHaveUploaded()){
		throw new UploadSameFileException($file->getFilename(), $date);
	}else {
		$sssReader->saveFileInfo($record);
	}
	//这个放在saveFileInfo下面是因为如果放在上边的话，保存file信息后获得不到inser_id。
	//beginTransaction();

	
	$sssReader->myReadIntoDB();
	LogInserter::logForInsert("导入发货批次文件：{$file->getFilename()}");
	
	$mcArray = $sssReader->getConsignmentColumn();
	
	foreach ($mcArray as $value) {
		$materialCode = $value[0];
		$shipNumber = $value[1];
		$subsectionNumber = $value[2];
		$orderNumber = $value[3];
		$orderSubitemNumber = $value[4];
		$purchaseNumber = $value[5];
		$consignmentBatch = $value[6];
		
		if($consignmentBatch)
			$sql2 = "update sss_main set consignmentBatch = '$consignmentBatch' where ";
		else 
			$sql2 = "update sss_main set consignmentBatch = NULL where ";
		$wheres = array();
		if($materialCode){
			array_push($wheres, "materialCode = '{$materialCode}'");
		}
		if($shipNumber){
			array_push($wheres, "substring(materialCode,1,5) = '{$shipNumber}'");
		}
		if($subsectionNumber){
			array_push($wheres, "substring(materialCode,9,3) = '{$subsectionNumber}'");
		}
		if($orderNumber){
			array_push($wheres, "orderNumber = '{$orderNumber}'");
		}
		if($orderSubitemNumber){
			array_push($wheres, "orderSubitemNumber = '{$orderSubitemNumber}'");
		}
		if($purchaseNumber){
			array_push($wheres, "purchaseNumber = '{$purchaseNumber}'");
		}
		$where = join(' and ',$wheres);
		$sql2 .= $where;
		DB::query($sql2);
		
		if($consignmentBatch)
			$sql3 = "update sss_fache set consignmentBatch = '$consignmentBatch' where ";
		else 
			$sql3 = "update sss_fache set consignmentBatch = NULL where ";
		$wheres = array();
		if($materialCode){
			array_push($wheres, "materialCode = '{$materialCode}'");
		}
		if($shipNumber){
			array_push($wheres, "substring(materialCode,1,5) = '{$shipNumber}'");
		}
		if($subsectionNumber){
			array_push($wheres, "substring(materialCode,9,3) = '{$subsectionNumber}'");
		}
		if($orderNumber){
			array_push($wheres, "orderNumber = '{$orderNumber}'");
		}
		if($orderSubitemNumber){
			array_push($wheres, "orderSubitemNumber = '{$orderSubitemNumber}'");
		}
		if($purchaseNumber){
			array_push($wheres, "purchaseNumber = '{$purchaseNumber}'");
		}
		$where = join(' and ',$wheres);
		$sql3 .= $where;
		DB::query($sql3);
		if($consignmentBatch)
			$sql4 = "update sss_fachuan set consignmentBatch = '$consignmentBatch' where ";
		else 
			$sql4 = "update sss_fachuan set consignmentBatch = NULL where ";
		$wheres = array();
		if($materialCode){
			array_push($wheres, "materialCode = '{$materialCode}'");
		}
		if($shipNumber){
			array_push($wheres, "substring(materialCode,1,5) = '{$shipNumber}'");
		}
		if($subsectionNumber){
			array_push($wheres, "substring(materialCode,9,3) = '{$subsectionNumber}'");
		}
		if($orderNumber){
			array_push($wheres, "orderNumber = '{$orderNumber}'");
		}
		if($orderSubitemNumber){
			array_push($wheres, "orderSubitemNumber = '{$orderSubitemNumber}'");
		}
		if($purchaseNumber){
			array_push($wheres, "purchaseNumber = '{$purchaseNumber}'");
		}
		$where = join(' and ',$wheres);
		$sql4 .= $where;
		DB::query($sql4);	
	}
}catch(DBQueryException $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errorType', 'consignmentUpload');
	$smarty->assign("errTitle", '上传发货批次文件失败1');
	$smarty->display('error.html');
	
	rollback();
	exit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->assign('errorType', 'consignmentUpload');
	$smarty->assign("errTitle", '上传发货批次文件失败2');
	$smarty->display('error.html');
	rollback();
	exit();
}

$smarty->assign('successMsg', $file->getFilename().'上传成功');
$smarty->assign("successTitle", '上传发货批次文件成功');
$smarty->assign('successType', 'consignmentUpload');
if(isset($warnMsg)){
	$smarty->assign('warnMsg', $warnMsg);
}
$smarty->display('success.html');
commit();
?>