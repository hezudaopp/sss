<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');
require_once ('Spreadsheet/Excel/Writer.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename0 = '2ndFile0';
$formFilename1 = '2ndFile1';
$formFilename2 = '2ndFile2';
$formFilename3 = '2ndFile3';

//不可缺少的列
$obligatoCols = array('MQ','生产厂家','船级', '材质','厚度', '宽度', '长度', '清单数量');

/************************************************************
 * 文件上传
 ***********************************************************/
try{
	//$file 在本程序中用作了全局变量，用来在需要filename的函数中使用
	$file0 = saveUploadedFile($formFilename0);
	$file1 = saveUploadedFile($formFilename1);
	if($_FILES["2ndFile2"]["error"] == 0)
		$file2 = saveUploadedFile($formFilename2);
	if($_FILES["2ndFile3"]["error"] == 0)
		$file3 = saveUploadedFile($formFilename3);
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	exit();
}

$sssReader = new SSS_ExcelReader();
$sssReader->read($file0->getNewPath());
$sssReader->obligatoColumns = $obligatoCols;
$sssReader->checkLackedColumns();

//表格A0用来比较的列的数据
$comparedArray0 = $sssReader->getComparedArray();
//表格A0不可缺少的列的数据
$obligatoArray0 = $sssReader->getObligatoArray();


$sssReader = new SSS_ExcelReader();
$sssReader->read($file1->getNewPath());
$sssReader->obligatoColumns = $obligatoCols;
$sssReader->checkLackedColumns();
//表格A1用来比较的列的数据
$comparedArray1 = $sssReader->getComparedArray();
//表格A1不可缺少的列的数据
$obligatoArray1 = $sssReader->getObligatoArray();
$allArray1 = $sssReader->getAllColumn();


//获取A2，A3表格的数组，同时将A2，A3表格的数组合并到A1表格数组中，方便处理
if($file2){
	$sssReader = new SSS_ExcelReader();
	$sssReader->read($file2->getNewPath());
	$sssReader->obligatoColumns = $obligatoCols;
	$sssReader->checkLackedColumns();
	$comparedArray2 = $sssReader->getComparedArray();
	$obligatoArray2 = $sssReader->getObligatoArray();
	$allArray2 = $sssReader->getAllColumn();
	foreach ($obligatoArray2 as $key => $val)
		array_push($obligatoArray1, $val);
	foreach ($comparedArray2 as $key => $val)
		array_push($comparedArray1, $val);
	foreach ($allArray2 as $key => $val)
		array_push($allArray1,$val);
}

if($file3){
	$sssReader = new SSS_ExcelReader();
	$sssReader->read($file3->getNewPath());
	$sssReader->obligatoColumns = $obligatoCols;
	$sssReader->checkLackedColumns();
	$comparedArray3 = $sssReader->getComparedArray();
	$obligatoArray3 = $sssReader->getObligatoArray();
	$allArray3 = $sssReader->getAllColumn();
	foreach ($obligatoArray3 as $key => $val)
		array_push($obligatoArray1, $val);
	foreach ($comparedArray3 as $key => $val)
		array_push($comparedArray1, $val);
	foreach ($allArray3 as $key => $val)
		array_push($allArray1,$val);
}


//$A0Array为经过原始清单比较合并得到的数组，适合保存为电子表格的数组形式
$A0Array = array();
for($i=0;$i<count($obligatoArray0);$i++){
	$A0Array[$i]['MQ'] = $obligatoArray0[$i][1];
	$transportMethodArray = explode("_",$obligatoArray0[$i][0]);
	for($j=0;$j<count($transportMethodArray);$j++){
		if($j==0) $A0Array[$i]['生产厂家'] = $transportMethodArray[$j];
		if($j==1) $A0Array[$i]['船级'] = $transportMethodArray[$j];
		if($j==2) $A0Array[$i]['材质'] = $transportMethodArray[$j];
		if($j==3) $A0Array[$i]['厚度'] = $transportMethodArray[$j];
		if($j==4) $A0Array[$i]['宽度'] = $transportMethodArray[$j];
		if($j==5) $A0Array[$i]['长度'] = $transportMethodArray[$j];
	}
	$A0Array[$i]['清单数量'] = $obligatoArray0[$i][2];
}

//$A1Array,$A2Array,$A3Array为A0表格分为3分经过修改之后得到的数组
$A1Array = array();
for($i=0;$i<count($obligatoArray1);$i++){
	$A1Array[$i]['MQ'] = $obligatoArray1[$i][1];
	$transportMethodArray = explode("_",$obligatoArray1[$i][0]);
	for($j=0;$j<count($transportMethodArray);$j++){
		if($j==0) $A1Array[$i]['生产厂家'] = $transportMethodArray[$j];
		if($j==1) $A1Array[$i]['船级'] = $transportMethodArray[$j];
		if($j==2) $A1Array[$i]['材质'] = $transportMethodArray[$j];
		if($j==3) $A1Array[$i]['厚度'] = $transportMethodArray[$j];
		if($j==4) $A1Array[$i]['宽度'] = $transportMethodArray[$j];
		if($j==5) $A1Array[$i]['长度'] = $transportMethodArray[$j];
	}
	$A1Array[$i]['清单数量'] = $obligatoArray1[$i][2];
}

//以下操作用来将数组导出表格
$filename = $file0->getFilename();
$workbook = new Spreadsheet_Excel_Writer();
$worksheet = $workbook->addWorksheet();
$formatRed = &$workbook->addFormat(array('fgcolor' => 'red'));
$formatFgcolor = &$workbook->addFormat(array('fgcolor' => 'yellow'));

//添加表头
$ths = array("MQ","生产厂家","船级","材质","厚度","宽度","长度","清单数量","MQ","生产厂家","船级","材质","厚度","宽度","长度","清单数量","备注");
$x = 0;
foreach($ths as $th){
	$worksheet->write(0, $x, iconv("UTF-8", "gbk", $th));
	$x++;
}

//写入A0数据，$y代表行，$x代表列
$y = 1;
foreach($A0Array as $col){
	$x = 0;
	foreach($col as $td){
		if(is_utf8($td))
			$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td));
		else
			$worksheet->write($y, $x, $td);
		$x++;
	}
	$y++;
}

//写入A1，A2，A3表格数据，如果A1，A2，A3中的序号有重复，直接用后面的数据覆盖
foreach ($A1Array as $val1){
	$x = count($obligatoCols);
	foreach ($A0Array as $rowNumber => $val0){
		if($val1['MQ'] == $val0['MQ']){
			$change = false;
			foreach ($val1 as $key => $val){
				if(is_utf8($val))
					$val = iconv("UTF-8", "gbk", $val);
				if($val1[$key]!=$val0[$key]){
					$worksheet->write($rowNumber+1, $x, $val,$formatRed);
					if($key != "生产厂家")	$change = true;
				}else{
					$worksheet->write($rowNumber+1, $x, $val,$formatFgcolor);
				}	
				$x++;
//				echo $x."-".$change."<br>";
				if($x == count($obligatoCols)+count($val1) && $change){
					$worksheet->write($rowNumber+1, $x, iconv("UTF-8", "gbk", "列值有修改"),$formatRed);//生产厂家列修改不做提示
					$change = false;
				}
			}
		}
	}
}

//添加备注数据
foreach ($allArray1 as $val){
	foreach ($val as $key => $col) {
		if(!in_array($key,$obligatoCols)){
			for($i=0;$i<count($A0Array);$i++){
				if($val['MQ']==$A0Array[$i]['MQ']){
					$worksheet->write($i+1, $x, iconv("UTF-8", "gbk", "新增".$key."列:值为".$col),$formatRed);
				}
			}
		}
	}
}

//print_r($allArray1);
// sending HTTP headers
$workbook->send($filename);
$workbook->close();
?>