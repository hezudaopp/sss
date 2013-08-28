<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');
require_once ('Spreadsheet/Excel/Writer.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename0 = '4thFile0';
$formFilename1 = '4thFile1';

//不可缺少用作比较的数组
$comparedArray = array('生产厂家', '材质','厚度');

/************************************************************
 * 文件上传
 ***********************************************************/
try{
	//$file 在本程序中用作了全局变量，用来在需要filename的函数中使用
	$file0 = saveUploadedFile($formFilename0);
	$file1 = saveUploadedFile($formFilename1);
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	exit();
}

$sssReader = new SSS_ExcelReader();
$sssReader->read($file0->getNewPath());
$sssReader->obligatoColumns = $comparedArray;
$sssReader->checkLackedColumns();

//表格A0不可缺少的列的数据
$allArray0 = $sssReader->getAllColumn();
$firstRow = $sssReader->getFirstRow();
if(!in_array('价格',$firstRow))
	array_push($firstRow,'价格');
//print_r($allArray0);
//echo "<hr>";


$sssReader = new SSS_ExcelReader();
$sssReader->read($file1->getNewPath());
$sssReader->obligatoColumns = array('生产厂家', '材质','厚度','价格');
$sssReader->checkLackedColumns();
//表格A1不可缺少的列的数据
$obligatoColumn1 = $sssReader->getObligatoColumn();
//print_r($obligatoColumn1);
//echo "<hr>";


$count = count($comparedArray);
foreach ($obligatoColumn1 as $obligatoKey => $obligatoVal){
	foreach ($allArray0 as $allKey => $allVal){
		for ($i = 0;strstr($allVal[$comparedArray[$i]], $obligatoVal[$comparedArray[$i]]) && $i < $count; $i++);
		if ($i == $count){
			$allArray0[$allKey]['价格'] = $obligatoVal['价格'];
		}
	}
}

//print_r($allArray0);
//echo "<hr>";

//准备写入电子表格的对象
$workbook = new Spreadsheet_Excel_Writer();
$worksheet = $workbook->addWorksheet();
$filename = $file0->getFilename();

//将$allArray0的数据格式化，方便导出到电子表格
$ths = array();
foreach ($allArray0 as $key => $val){
	$row = array();
	foreach ($firstRow as $keyNum => $keyVal){
		if(array_key_exists($keyVal,$val)){
			$row[$keyVal] = $val[$keyVal];
		}else{
			$row[$keyVal] = null;
		}
	}
	array_push($ths,$row);
}

//插入表头
$x = 0;
foreach($firstRow as $th){
	$worksheet->write(0, $x, iconv("UTF-8", "gbk", $th));
	$x++;
}

//添加数据，$y代表行，$x代表列
$y = 1;
foreach($ths as $col){
	$x = 0;
	foreach($col as $td){
		$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td));
		$x++;
	}
	$y++;
}


// sending HTTP headers
$workbook->send($filename);
$workbook->close();
?>