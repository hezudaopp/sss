<?php
require('../includes/functions.inc.php');
require('Spreadsheet/Excel/Reader.php');

/************************************************************
 * 文件上传
 ***********************************************************/
try{
	//$file 在本程序中用作了全局变量，用来在需要filename的函数中使用
	$file = saveUploadedFile('planFile');
}catch(MyException $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errorTitle', '出现错误导致不能完成比对，错误原因：');
	$smarty->assign('errMsg', $e);
	$smarty->display('error.tpl');
	exit();
}

/*echo 'now the filename is: '.$file->getFilename()."<br />\n";
echo 'and the file path is :'. $file->getNewPath()."<br />\n";*/

/************************************************************
 * 读取文件
 ***********************************************************/
$reader = new Spreadsheet_Excel_Reader();
$reader->setUTFEncoder('iconv');
$reader->setOutputEncoding('UTF-8');
$reader->read($file->getNewPath());

/*echo "<pre>";
var_dump($reader->sheets);*/

//$mustIndex 检查文件时用到，这些属性在文件中必须存在。
//$indexArray 查询数据库时和生成最终的excel表格时用到的。
//$compareVIP 比较数据的时候用到的，这些数据必须相同，不相同会产生警告
//这些数据可以通过global声明在函数中引用
$mustIndex = array(
	'批次', '材料代码','生产厂家','船级', '材质', 
	'厚', '宽', '长', '数量', '单重', 
	'订单号', '订单子项号','受订单价','批号','购单号','目的地','库存地'
);
$indexArray = array(
		'文件名'		=>	'filename',
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
		'受订单价' => 'unitPrice',
		'批号' => 'batchNumber',
		'购单号' => 'purchaseNumber',
		'目的地' => 'destination',
		'库存地' => 'storePlace',
		'备注' 		=> 	'remark',
		'记录'		=>	'log'
);		

$compareVIP = array(
	'materialCode', 'length', 'width', 'thickness'
);
/************************************************************
 * 检查文件第一行是否有错误
 ***********************************************************/
try{
	//beginTransaction();
	$firstRow = checkFirstRow($reader);
}catch(MyException $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errMsg', $e);
	$smarty->display('error.tpl');
	rollback();
	exit();
}

/************************************************************
 * 检查文件中是否有相同的材料代码
 ***********************************************************/
if(haveSameMaterialCode($reader->sheets[0]['cells'])){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errorTitle',"您上传的表格有点问题，请解决后再上传");
	$smarty->assign('errMsg', '上传的表格中有相同的材料代码，请手动将他们合并后再上传');
	$smarty->display('error.tpl');
	rollback();
	exit();
}


/************************************************************
 * 对每一行进行处理
 ***********************************************************/
//下面的ok和ignore分别是用户选择确定还是选择忽略时要处理的查询语句
//两者都会在函数中使用global进行引用
$ok = "";
$ignore = "";
$result = array();
$haveRed = false;
//beginTransaction();

for($i = 2; $i <= $reader->sheets[0]['numRows']; $i++){
	
/*	echo $reader->sheets[0]['numRows'].'<br /><pre>';
	var_dump($reader->sheets[0]['cells'][$i]);
	die();*/
	
	/*$rows = insert($reader, $i);
	if(count($rows) != 0){
		foreach($rows as $row)
		array_push($result, $row);
	}*/
	$formatedSheetRow = getFormatedFromSheetRow($reader->sheets[0]['cells'][$i]);
/*	echo "<pre>";
	var_dump($formatedSheetRow);
	die();*/
	
	$dbRow = getRowByMaterialCode($formatedSheetRow['materialCode']);
	
/*	echo "<pre>";
	var_dump($dbRow);
	die();*/
	
	if($dbRow === false){
	//如果数据库中不存在这个，那么首先修改$formatedSheetRow中的记录信息，然后直接将它们插入
		date_default_timezone_set('Asia/Chongqing');
		$currentTime = date('Y/m/d H:i:s');
		$formatedSheetRow['log'] = "总表（{$file->getFilename()}）{$formatedSheetRow['count']}@$currentTime\n";
		
		$setString = "";
		if($formatedSheetRow['orderSubitemNumber'] == ''){
			unset($formatedSheetRow['orderSubitemNumber']);
		}
		
		foreach($formatedSheetRow as $key => $val){
			$setString .= ", $key = '$val'";
		}
		$setString = "uploadTime = '$currentTime'$setString";
		$query = "insert into sss_mainTable set $setString;;;";
		$ok .= $query;
		$ignore .= $query;
	} else {
	//如果数据库中存在这个代码，那么将他们对比，然后进行数据库的更新
		//$resultPlanRow = getResultPlanRow($dbRow, $formatedSheetRow);
		date_default_timezone_set('Asia/Chongqing');
		$currentTime = date('Y/m/d H:i:s');
		$newCount = intval($formatedSheetRow['count']) + intval($dbRow['count']);
		
/*		if($i == $reader->sheets[0]['numRows'])
		{
			echo "<pre>";
			var_dump($dbRow);
			var_dump($formatedSheetRow);
			die();
		}*/
		
		
		$formatedSheetRow['log'] = "总表（{$file->getFilename()}）{$formatedSheetRow['count']}=>{$newCount}@$currentTime\n";
		
		$materialCode = $formatedSheetRow['materialCode'];
		
		$resultPlanRow = array();

		foreach($dbRow as $key => $val){
			$resultPlanRow[$key] = $val;
		}
		
		
		$resultPlanRow['count'] = $newCount;
		$resultPlanRow['log'] .= $formatedSheetRow['log'];
		$resultPlanRow['remark'] .= $formatedSheetRow['remark'];
		
		$updateQuery = "update sss_mainTable set updateTime = '$currentTime', count = {$resultPlanRow['count']}, log = '{$resultPlanRow['log']}', remark = '{$resultPlanRow['remark']}' where materialCode = '$materialCode';;;";
		
/*		echo "<pre>";
		var_dump($dbRow);
		var_dump($formatedSheetRow);
		var_dump($resultPlanRow);
		var_dump($newCount);
		die();*/
		
		if(CompareForPlanInsertion($dbRow, $formatedSheetRow)){
			//如果数据比对相同，原来数据库中的信息用绿色表示
			//合并的结果用黑色表示
			$newArray = array(
				'color' => 'green',
				'row' => $dbRow
			);
			array_push($result, $newArray);
			
			$newArray = array(
				'color' => 'black',
				'row' => $resultPlanRow
			);
			array_push($result, $newArray);
			
			$ok .= $updateQuery;
			$ignore .= $updateQuery;
		}else{
			//如果有些数据不相同，用红色的表示，
			//每部分第一行是数据库中的信息，
			//第二行是excel表格中的数据，
			//第三行是强制合并后数据库中的信息（不是红色，是黑色）
			$haveRed = true;
			
			$newArray = array(
				'color' => 'purple',
				'row' => $dbRow
			);
			array_push($result, $newArray);
			
			$newArray = array(
				'color' => 'purple',
				'row' => $formatedSheetRow
			);
			array_push($result, $newArray);
			
			$newArray = array(
				'color' => 'black',
				'row' => $resultPlanRow
			);
			array_push($result, $newArray);
			
/*			echo("<pre>");
			var_dump($resultPlanRow);
			var_dump($newArray);
			die();*/
			
			$ignore .= $updateQuery;
		}
	}
}
//commit();

$insertId = insertSQL($ok, $ignore);

/*echo "<pre>";
var_dump($result);*/

if(count($result) == 0){
	executeSQL($insertId, 'ok');
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('successMsg', '合并成功，未出现任何异常问题');
	$smarty->assign('successTitle', '上传成功');
	die();
}

$excelFile = generateExcel($result);

$smarty = SmartyManager::getSmarty();
$smarty->assign('haveRed', $haveRed);
$smarty->assign('id', $insertId);
$smarty->assign('filename', basename($excelFile));

$smarty->display('confirm.html');


/*$okArray = explode(";;;", $ok);
$ignoreArray = explode(";;;", $ignore);
var_dump($okArray);
var_dump($ignoreArray);*/
//commit();

?>