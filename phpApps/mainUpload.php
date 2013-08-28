<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'planFile';

//不可缺少的列
$obligatoCols = array(
	'序号','材料代码','批次','生产厂家','船级', '材质',
	'厚', '宽', '长', '数量', '单重', '重量','订单号','订单子项号','受订单价','购单号','备注'
);

//电子表格和数据库表中的列的对应关系
$tableCols = array(
		'批次' 		=> 	'sequenceNumber',
		'材料代码' 	=> 	'materialCode',
		'生产厂家' 	=> 	'manufactory',
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
		'购单号'    =>	'purchaseNumber',
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

$tableName = 'sss_main';

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

	//开始思考对于main上传，我们应该检查什么
	//1.一些必要的检查，查看第一行是否缺少某些必须的列
	//2.然后查看这个文件是否已经上传过
	//3.接着查看这个文件中的材料代码订单号订单子项号和以前数据库中的时候否有冲突
	//4.然后查看这个文件本身是否有冲突
	
	//对于main上传，3和4以警告来表示
	//对于fache,fachuan，不需要检查4，只需要检查3，而且对于3的出现不是警告，而是禁止上传。
	//另外对于fache,fachuan要多一项检查，就是要检查这个文件中的材料代码在总表中是否已经上传过
	
	//1.
	$sssReader->checkFirstRow("main");
	//2.
	if(($date = $sssReader->haveUploaded())){
		throw new UploadSameFileException($file->getFilename(), $date);
	}

	$warnMsg = "";
	//3.
	if(($conflictsPair = $sssReader->getMcAndOrdersConflictedWithDb()) != null){
		$table = "";
		foreach($conflictsPair as $pair){
			$table .= "| ".$pair[0]." | ".$pair[1]." |<br />";
		}
		$warnMsg .= "表格上传完成，但下列条目和数据库中已存在的条目有冲突，原因是材料代码相同但订单号订单子项号不同，这里只做提示：<br />
			| 文件中材料代码,订单号,订单子项号 | 数据库中材料代码,订单号,订单子项号 |<br />
			$table<br />";
	}
	
	//4.
	if(($conflictMcs = $sssReader->getConflictMcsInFile())){
		$warnMsg .="表格上传成功，但下列材料代码可能存在问题，他们在上传的文件中有多行，但订单号和订单子项号不同，这里只做提示：<br />".join(', ',$conflictMcs);
	}
	
	//5.检查材料代码，订单号，订单子项号以前是否已经上传过
	if(($mos = &$sssReader->getHaveUploadedMaterialCodeAndOrders()) != null){
		$mostring = "";
		foreach($mos as $mo){
			$mostring .= "{$mo[0]},{$mo[1]},{$mo[2]}<br />";
		}
		$warnMsg .= "<br />表格上传完成，但是程序检测到当前文件中有些材料代码，订单号，订单子项号以前已经上传过，程序在这里只是提示您一下，您应该检查是不是出现了错误。这些材料代码是：'".$mostring."'.";
	}
	
	//6.检查材料代码，订单号，订单子项号是否在表格中重复出现
	if($mcs = $sssReader->haveSameMaterialCodeAndOrders()){
		$warnMsg .= "<br />表格上传完成，但是程序检查到上传的文件中有重复的材料代码,订单号，订单子项号：'".join("', '", $mcs)."', 如果你确认这里出现了问题，可以直接在系统中进行修改或者删除刚才上传的文件";
	}
	

	$sssReader->readIntoDB();
	LogInserter::logForInsert("导入总表文件：{$file->getFilename()}");
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