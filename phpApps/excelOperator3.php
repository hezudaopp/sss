<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');
require_once ('Spreadsheet/Excel/Writer.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename0 = '3rdFile0';
$formFilename1 = '3rdFile1';

//不可缺少的列
$obligatoCols = array('MQ','生产厂家','船级', '材质','厚度', '宽度', '长度', '清单数量','单规格小于10吨加价');

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

//获取原始清单表格的数据
$sssReader = new SSS_ExcelReader();
$sssReader->read($file0->getNewPath());
$sssReader->obligatoColumns = array('MQ','生产厂家','船级', '材质','厚度', '宽度', '长度', '清单数量','单重','清单重量');
$sssReader->checkLackedColumns();
$allArray0 = $sssReader->getAllColumn();
$firstRow0 = $sssReader->getFirstRow();
//print_r($allArray0);
//echo "<hr>";
//echo "<hr>";
//echo "<hr>";
//echo "<hr>";
//echo "<hr>";
//echo "<hr>";


//获取B0表格的数据
$sssReader = new SSS_ExcelReader();
$sssReader->read($file1->getNewPath());
$sssReader->obligatoColumns = $obligatoCols;
$sssReader->checkLackedColumns();
$allArray1 = $sssReader->getAllColumn();
$firstRow1 = $sssReader->getFirstRow();
//print_r($allArray1);
//echo "<hr>";

//以下操作用来将数组导出表格
$filename = $file0->getFilename();
$workbook = new Spreadsheet_Excel_Writer();
$worksheet = $workbook->addWorksheet();
$formatRed = &$workbook->addFormat(array('fgcolor' => 'red'));
$formatFgcolor = &$workbook->addFormat(array('fgcolor' => 'yellow'));

//$firstRow是表头数据
$firstRow = array_merge($firstRow0,$firstRow1);
array_push($firstRow,"备注");
//外层的array_merge函数用来重排键值
$firstRow = array_merge(array_unique($firstRow));

//print_r($firstRow);
//echo "<hr>";

//M0表格的单元格数据处理
foreach ($allArray1 as $num => $arrVal){//$arrVal是B0表格每行的数据,$num为行号
	//分割序号
	$transportMethodArray = array_filter(explode("M",$arrVal['MQ']));
	$sumCount = 0;
	$i = 0;
	$rowNumberArray = array();
	foreach ($transportMethodArray as $tempVal){
		$i++;
		foreach ($allArray0 as $rowNumber => $rowVal){//$rowVal是原始清单表格每行的数据数组
			if($tempVal == $rowVal['MQ']){//序号匹配
				array_push($rowNumberArray,$rowNumber);
				foreach($arrVal as $key => $val){//$val是B0表格的单元格数据，$key为表头（第一行单元格的某个值）
					if(array_key_exists($key,$rowVal) && $key != "清单数量" && $key != "MQ" && $key != "备注"){//处理匹配的列
						if($allArray0[$rowNumber][$key] != $val){
							$allArray0[$rowNumber][$key] = array($val,$formatRed);
						}
					}else if($key != "清单数量" && $key != "MQ" && $key != "备注"){//处理添加的列
						if($allArray0[$rowNumber][$key] != $val){
							$allArray0[$rowNumber][$key] = array($val,$formatFgcolor);
						}
					}//没有找到关键字的列不做处理
				}
				$allArray0[$rowNumber]['备注'] = "原始清单备注：".$allArray0[$rowNumber]['备注']."；B0备注：".$arrVal['备注'];
				if(!is_array($rowVal["清单数量"]))
					$sumCount += $rowVal["清单数量"];
				else 
					$sumCount += $rowVal["清单数量"][0];
				if($i == count($transportMethodArray) && $arrVal['清单数量'] > $sumCount){
					$allArray0[$rowNumber]["清单数量"] = array($rowVal["清单数量"] + $arrVal['清单数量'] - $sumCount,$formatRed);
					foreach ($rowNumberArray as $rowNumberVal){
						$allArray0[$rowNumberVal]['备注'] = array($allArray0[$rowNumberVal]['备注']."；清单数量有问题",$formatRed);
					}
				}
				
				if($i == count($transportMethodArray) && $arrVal['清单数量'] < $sumCount){
					$allArray0[$rowNumberVal]['备注'] .= "清单数量有问题";
				}
				break;
			}
		}
	}
}
//print_r($allArray0);
//echo "<hr>";

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
//		$td = iconv('utf-8', 'gbk', $td);
		if(is_array($td))
			$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td[0]),$td[1]);
		else 
			$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td));
		$x++;
	}
	$y++;
}

// sending HTTP headers
$workbook->send($filename);
$workbook->close();

?>