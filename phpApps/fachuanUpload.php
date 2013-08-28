<?php
require '../includes/SSS_ExcelReader.class.php';
require '../includes/functions.inc.php';


$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'shipFile';
$warnMsg ="";

//不可缺少的列
$obligatoCols = array(
	'日期', '船号', '材料代码','船级', '材质',
	'厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','库存地','目的地','备注');

//电子表格和数据库表中的列的对应关系
$tableCols = array(
		'日期' 		=> 	'fachuanDate',
		'船号' 		=> 	'fachuanNumber',
		'材料代码' 	=> 	'materialCode',
		'船级' 		=> 	'shipsClassification',
		'材质' 		=> 	'material',
		'厚' 		=> 	'thickness',
		'宽' 		=> 	'width',
		'长' 		=> 	'length',
		'数量' 		=> 	'count',
		'单重'		=> 	'unitWeight',
		'订单号' 	=> 	'orderNumber',
		'订单子项号' 	=> 	'orderSubitemNumber',
		'受订单价'  =>   'unitPrice',
		'批号'      => 	'batchNumber',
		'物料号'		=>	'materialNumber',
		'购单号'    =>	'purchaseNumber',
		'目的地'    =>	'destination',
		'库存地'	    =>	'storePlace',
	    '备注1' 		=> 	'remark1',
		'备注2'		=>	'remark2',
		'备注3' 		=> 	'remark3',
		'备注4'		=>	'remark4',
		'备注5'		=> 	'remark5',
		'备注'		=> 	'remark1'
);

//对比时用来比较的列
$compareCols = array(
	'materialCode', 'orderNumber', 'orderSubitemNumber'
);

$tableName = 'sss_fachuan';

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
	$sssReader = new SSS_ExcelReader();
	$sssReader->read($file->getNewPath());

	$sssReader->obligatoColumns = $obligatoCols;
	$sssReader->tableColumns = $tableCols;
	$sssReader->compareColumns = $compareCols;
	$sssReader->tableName = $tableName;

	$sssReader->checkFirstRow('fachuan');
	if(($date = $sssReader->haveUploaded())){
		throw new UploadSameFileException($file->getFilename(), $date);
	}
	if(($havenot = $sssReader->getHaveNotUploadedMaterialCode())!=null){
		throw new HaveNotUploadedMaterialCode($havenot);
	}
	
	if(($conflictsPair = $sssReader->getMcAndOrdersNotInDb())!=null){
		$table = "";
		foreach($conflictsPair as $pair){
			$table .= "| ".$pair." |<br />";
		}
		$msg = "文件中有些材料代码，订单号，订单子项号的组合在数据库中不存在，他们是：<br />
			| 文件中材料代码,订单号,订单子项号 |<br />
			$table<br />";
		throw new CustomException($msg);
	}
	
	if(($conflictsPair = $sssReader->getMcAndOrdersChukuCountExceed())!=null){
			$table = "";
			foreach($conflictsPair as $pair){
				$table .= "| ".$pair." |<br />";
			}
			$msg = "文件中有些材料代码，订单号，订单子项号的组合数量超过了库中的数量，他们是：<br />
				| 文件中材料代码,订单号,订单子项号 ,数量|<br />
				$table<br />";
			throw new CustomException($msg);
		}
		
	/*
	if($mcs = $sssReader->haveSameMaterialCode()){
		$warnMsg = "但是，程序检查到上传的文件中有重复的材料代码：'".join("', '", $mcs)."', 如果你确认这里出现了问题，可以直接在系统中进行修改或者删除刚才上传的文件";
	}
	*/
	$sssReader->readIntoDB();
	LogInserter::logForInsert("导入发船文件：{$file->getFilename()}");
	
	$mcArray = $sssReader->getMaterialCodeAndOrdersArray();
	
	foreach ($mcArray as $value) {
		$mc = $value[0];
		$on = $value[1];
		$osn = $value[2];
		$destinations = array();
		$storePlaces = array();
		$sql1 = "select destination,storePlace from sss_fache 
		where  materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn';";
		$result1 = DB::query($sql1);
		while (($row1 = $result1->fetch_array())!=NULL) {
			if (!in_array($row1['destination'],$destinations) && $row1['destination']!=NULL) {
				array_push($destinations,$row1['destination']);
			}
			if (!in_array($row1['storePlace'],$storePlaces) && $row1['storePlace']!=NULL) {
				array_push($storePlaces,$row1['storePlace']);
			}
		}
		
		$sql1 = "select destination,storePlace from sss_fachuan
		where  materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn';";
		$result1 = DB::query($sql1);
		while (($row1 = $result1->fetch_array())!=NULL) {
			if (!in_array($row1['destination'],$destinations) && $row1['destination']!=NULL) {
				array_push($destinations,$row1['destination']);
			}
			if (!in_array($row1['storePlace'],$storePlaces) && $row1['storePlace']!=NULL) {
				array_push($storePlaces,$row1['storePlace']);
			}
		}	
		$destination = join(",",$destinations);
		$storePlace = join(",",$storePlaces);

		$sql2 = "update sss_main set destination = '{$destination}', storePlace = '{$storePlace}' 
		where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
		DB::query($sql2);
		$sql3 = "update sss_fache set destination = '{$destination}',storePlace = '{$storePlace}' 
		where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."' and phase = '入库';";
		DB::query($sql3);
	
}
	
	
	
}catch(DBQueryException $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	rollback();
	exit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	rollback();
	exit();
}

$smarty->assign('successMsg', $file->getFilename().'上传成功');
if(isset($warnMsg)){
	$smarty->assign('warnMsg', $warnMsg);
}
$smarty->display('success.html');
commit();
?>