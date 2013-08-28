<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');
require_once ('Spreadsheet/Excel/Writer.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = '1stFile';

//不可缺少的列
$obligatoCols = array('MQ','生产厂家','船级', '材质','厚度', '宽度', '长度', '清单数量');

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

$sssReader = new SSS_ExcelReader();
$sssReader->read($file->getNewPath());
$sssReader->obligatoColumns = $obligatoCols;
$sssReader->checkLackedColumns();

$comparedArray = $sssReader->getComparedArray();
$obligatoArray = $sssReader->getObligatoArray();
$uniqueArray = array_unique($comparedArray);

//以下操作类似SQL语句中的Group by
$resultArray = array();
foreach ($uniqueArray as $uniqueKey => $uniqueValue){
	$mqValue = "";
	$sum = 0;
	for ($i=0;$i<count($obligatoArray);$i++){
		if($obligatoArray[$i][0] == $uniqueValue){
			$mqValue .= "M".$obligatoArray[$i][1];
			$sum += $obligatoArray[$i][2];
		}
	}
	$resultArrayTemp = array();
	$resultArrayTemp[0] = $mqValue;
	foreach(explode("_",$uniqueValue) as $val){
		array_push($resultArrayTemp,$val);
	}
	array_push($resultArrayTemp,$sum); 
	array_push($resultArray,$resultArrayTemp);
}

//以下操作用来将数组导出表格
$filename = $file->getFilename();
$workbook = new Spreadsheet_Excel_Writer();
$worksheet = $workbook->addWorksheet();
$worksheet->setInputEncoding('utf-8'); 

//添加表头
$ths = array("MQ","生产厂家","船级","材质","厚度","宽度","长度","清单数量");
$x = 0;
foreach($ths as $th){
	$worksheet->write(0, $x, iconv("UTF-8", "gbk", $th));
	$x++;
}

//添加数据，$y代表行，$x代表列
$y = 1;
foreach($resultArray as $col){
	$x = 0;
	foreach($col as $td){
//		$td = iconv('utf-8', 'gbk', $td);
		$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td));
		$x++;
	}
	$y++;
}

// sending HTTP headers
$workbook->send($filename);
$workbook->close();
?>