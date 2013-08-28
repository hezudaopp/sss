<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');
require_once ('Spreadsheet/Excel/Writer.php');

//print_r($_POST);
//echo "<hr>";

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = '5thFile';

//不可缺少用作比较的数组

$comparedArray = array('生产厂家','材料代码','船级','材质','厚度','宽度','长度','清单数量','单重','清单重量','备注','切割批次',
'订货批次','分段号','理货','运输方式','TMCP','Z向','双船级','探伤','船号','订货月份','方向','抛丸','库存地','价格','单规格小于10吨加价');

/************************************************************
 * 文件上传
 ***********************************************************/
try{
	//$file 在本程序中用作了全局变量,用来在需要filename的函数中使用
	$file = saveUploadedFile($formFilename);
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	exit();
}

$sssReader = new SSS_ExcelReader();
$sssReader->read($file->getNewPath());
$sssReader->obligatoColumns = $comparedArray;
$sssReader->checkLackedColumns();

//表格A0不可缺少的列的数据
$allArray = $sssReader->getAllColumn();
$firstRow = $sssReader->getFirstRow();

//往$firstRow中添加额外的数据列(这些数据列就是我们需要获取的数据)
//注意小括号符的中英文区别
array_push($firstRow,'双船级加价','宽度加价','TMCP加价','Z15/Z25/Z35','火焰切割','探伤加价','抛丸加价','平板加价',
'出厂含税单价','出厂含税金额','短途运费','基本港杂费','长12.5米上加50%','分票垫装费','代理费','理货费用金额','到营口港单价',
'到营口港金额','到鲅鱼圈均单价(含税)','到鲅鱼圈总金额(含税)','到厂运费(汽运)','到厂运费金额(汽运)','到厂含税单价(汽运)','到厂含税金额(汽运)',
'汽运到厂协议单价','汽运到厂协议金额','到厂运费(船运)','到厂运费金额(船运)','到厂含税单价(船运)','到厂含税金额(船运)',
'船运到厂协议单价','船运到厂协议金额','不含运费单价','不含运费金额(含税)','不含运费平均单价','不含运费总金额(含税)',
'铁运到厂价格(铁运)(含保险)','铁运到厂费用金额(铁运)(含保险)','协议单价(含税)',
'协议金额(含税)','铁路运费(铁运)','运费金额');

//将$allArray的数据格式化,方便导出到电子表格
$ths = array();
foreach ($allArray as $key => $val){
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


$qiyunSum = 0;
$chuanyunSum = 0;
$bayuquanSum = 0;
$weightSum = 0;
$yingkouganSum = 0;
$buhanyunfeiSum = 0;
//根据条件添加数据
foreach ($ths as $key => $val){
	//双船级认证的船板加价70元/吨
	if(!empty($val['双船级'])) $ths[$key]['双船级加价'] = 70;
	
	//TMCP加价(不同的工艺) TMCP列单元格的形式为：工艺方式-数值
	$TCMPArray = explode("-",$val['TMCP']);
	if(isset($TCMPArray[1]))
		$ths[$key]['TMCP加价'] = $TCMPArray[1];
		
	//库存地单元格形式为：库存地-基本港杂费-分票垫装费
	$storePlaceArray = explode("-",$val['库存地']);
	//长12.5米上加50%
	$ths[$key]['长12.5米上加50%'] = null;
	
	//基本港杂费
	if(isset($storePlaceArray[1]) && !empty($val['理货'])) $ths[$key]['基本港杂费'] = round($storePlaceArray[1],2);
	
	//分票垫装费
	if(isset($storePlaceArray[2]) && !empty($val['理货'])) $ths[$key]['分票垫装费'] = round($storePlaceArray[2],2);
		
	//长12.5米上加50%
	if($storePlaceArray[0] == '营口港务局' && $val['长度'] >= 12500 && !empty($val['理货'])) $ths[$key]['长12.5米上加50%'] = round($ths[$key]['基本港杂费'] * 0.50,2);
	
	//运输方式单元格形式：运输方式-价格，运输方式可能有：平板，车厢，销售，出库
	$transportMethodArray = explode("-",$val['运输方式']);
	//运输方式
	$transportMethod = $transportMethodArray[0];
	
	//抛丸加价
	if(!empty($val['抛丸'])) $ths[$key]['抛丸加价'] = 60;
	
	//Z15/Z25/Z35(Z向性能)
	if($val['Z向'] == 'Z15') $ths[$key]['Z15/Z25/Z35'] = 400;
	else if($val['Z向'] == 'Z25') $ths[$key]['Z15/Z25/Z35'] = 600;
	else if($val['Z向'] == 'Z35') $ths[$key]['Z15/Z25/Z35'] = 1000;
		
	if(strstr($val['生产厂家'],'厚板')){
		//宽度加价
		if(900 <= $val['宽度'] &&  $val['宽度']<= 1500) $ths[$key]['宽度加价'] = round($val['价格'] * 0.10,2);
		else if(2650 < $val['宽度'] && $val['宽度'] <= 3200) $ths[$key]['宽度加价'] = round($val['价格'] * 0.01,2);
		else if(3200 < $val['宽度'] && $val['宽度'] <= 3600) $ths[$key]['宽度加价'] = round($val['价格'] * 0.03,2);
		else if(3600 < $val['宽度'] && $val['宽度'] < 4000) $ths[$key]['宽度加价'] = round($val['价格'] * 0.05,2);
		else if(4000 <= $val['宽度'] && $val['宽度'] < 4500) $ths[$key]['宽度加价'] = round($val['价格'] * 0.07,2);
		else if($val['宽度'] >= 4500) $ths[$key]['宽度加价'] = round($val['价格'] * 0.10,2);
		
		//单规格小于10吨加价
//		if($val['清单重量'] < 10) $ths[$key]['单规格小于10吨加价'] = round(50 * $val['清单重量'],2);
		
		//探伤加价
		if($val['探伤'] == '一级探伤'){
			if(8 <= $val['厚度'] && $val['厚度'] <= 20) $ths[$key]['探伤加价'] = round($val['价格'] * 0.05,2);
			else if(21 <= $val['厚度'] && $val['厚度'] <= 50) $ths[$key]['探伤加价'] = round($val['价格'] * 0.07,2);
			else if($val['厚度'] >= 51) $ths[$key]['探伤加价'] = round($val['价格'] * 0.10,2);
		}else if($val['探伤'] == '二级探伤'){
			if(8 <= $val['厚度'] && $val['厚度'] <= 20) $ths[$key]['探伤加价'] = round($val['价格'] * 0.03,2);
			else if(21 <= $val['厚度'] && $val['厚度'] <= 50) $ths[$key]['探伤加价'] = round($val['价格'] * 0.04,2);
			else if($val['厚度'] >= 51) $ths[$key]['探伤加价'] = round($val['价格'] * 0.06,2);
		}else if($val['探伤'] == '三级探伤'){
			if(8 <= $val['厚度'] && $val['厚度'] <= 20) $ths[$key]['探伤加价'] = round($val['价格'] * 0.01,2);
			else if(21 <= $val['厚度'] && $val['厚度'] <= 50) $ths[$key]['探伤加价'] = round($val['价格'] * 0.02,2);
			else if($val['厚度'] >= 51) $ths[$key]['探伤加价'] = round($val['价格'] * 0.03,2);
		}
	}else if(strstr($val['生产厂家'],'中板')){
		//宽度加价
		if(6 <= $val['厚度'] && $val['厚度'] < 7){
			if(!($val['宽度'] >= 1800 && $val['宽度'] < 2000 && ($val['长度'] == 6000 || $val['长度'] == 8000 || $val['长度'] == 12000)))
				$ths[$key]['宽度加价'] += 300.00;
		}
				
			
		//单规格小于10吨加价
//		if($val['清单重量'] <= 5) $ths[$key]['单规格小于10吨加价'] = round(100 * $val['清单重量'],2);
//		else if(5 < $val['清单重量'] && $val['清单重量'] < 10 ) $ths[$key]['单规格小于10吨加价'] = round(50 * $val['清单重量'],2);
		
		//火焰切割加价
		if((($val['材质'] == 'A' || $val['材质'] == 'B' || $val['材质'] == 'D') && $val['厚度'] > 25)
		|| ($val['材质'] != 'A' && $val['材质'] != 'B' && $val['材质'] != 'D' && $val['厚度'] > 20))
			$ths[$key]['火焰切割'] = 150;
	}
	
	//短途运费
	if(!empty($val['理货'])) $ths[$key]['短途运费'] = 15;
	
	
	//代理费
	$ths[$key]['代理费'] = 10;
	
	//平板加价
	if($transportMethod == '平板') $ths[$key]['平板加价'] = 25;
	
	//加价因素
	$jiajiayinsu = $ths[$key]['价格']+$ths[$key]['单规格小于10吨加价']
		+$ths[$key]['双船级加价']+$ths[$key]['宽度加价']+$ths[$key]['TMCP加价']
		+$ths[$key]['Z15/Z25/Z35']+$ths[$key]['火焰切割']
		+$ths[$key]['探伤加价']+$ths[$key]['抛丸加价']+$ths[$key]['平板加价'];
	
	if(!empty($val['理货'])){
		//到营口港单价,双船级加价	宽度加价	TMCP加价	Z15/Z25/Z35	火焰切割	探伤加价	抛丸加价
		//短途运费	基本港杂费	长12.5米上加50%	分票垫装费	代理费
		$ths[$key]['到营口港单价'] = round($jiajiayinsu*1.17
		+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']
		+$ths[$key]['分票垫装费']+$ths[$key]['代理费'],2);
		
		//到营口港金额
		$ths[$key]['到营口港金额'] = round(($jiajiayinsu*1.17
		+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']
		+$ths[$key]['分票垫装费']+$ths[$key]['代理费'])*$ths[$key]['清单重量'],2);
		
		//理货费用金额
		$ths[$key]['理货费用金额'] = round(($ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']+$ths[$key]['分票垫装费']+$ths[$key]['代理费'])*$ths[$key]['清单重量'],2);
	}
	
	//理货费用金额
//	if(!empty($val['理货']))
//	$ths[$key]['理货费用金额'] = round(($ths[$key]['出厂含税单价']+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']
//	+$ths[$key]['长12.5米上加50%']+$ths[$key]['分票垫装费']+$ths[$key]['代理费'])*$ths[$key]['清单重量'],2);
	
	//到营口港货款金额(含税货款)
//	if(!empty($val['理货']))
//	$ths[$key]['到营口港货款金额(含税货款)'] = round($ths[$key]['出厂含税金额'],2);
	
	//库存费用金额
//	if(!empty($val['理货']))
//	$ths[$key]['库存费用金额'] = round(($ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']
//	+$ths[$key]['分票垫装费']+$ths[$key]['代理费'])*$ths[$key]['清单重量'],2);
	
	if(($transportMethod == '平板' || $transportMethod == '车厢')){
		//出厂含税单价
		$ths[$key]['出厂含税单价'] = round($jiajiayinsu*1.17117,2);
		//出厂含税金额		
		$ths[$key]['出厂含税金额']  = round($jiajiayinsu*1.17117*$ths[$key]['清单重量'],2);
	}
	
	if($transportMethod == '销售' || $transportMethod == '出库'){
		//出厂含税单价
		$ths[$key]['出厂含税单价'] = round($jiajiayinsu*1.17,2);	
		//出厂含税金额
		$ths[$key]['出厂含税金额']  = round($jiajiayinsu*1.17*$ths[$key]['清单重量'],2);
	}
	
	//铁路运费(铁运)
	if(($transportMethod == '平板' || $transportMethod == '车厢' || $transportMethod == '销售') && isset($transportMethodArray[1])){
		$ths[$key]['铁路运费(铁运)'] = round($transportMethodArray[1],2);
		$ths[$key]['运费金额'] = round($ths[$key]['铁路运费(铁运)'] * $ths[$key]['清单重量'],2);
	}
	
	//到厂运费(汽运)
	if($transportMethod == '出库' && isset($transportMethodArray[1])) $ths[$key]['到厂运费(汽运)'] = round($transportMethodArray[1],2);
	
	//到厂运费(船运)
	if($transportMethod == '出库' && isset($transportMethodArray[2])) $ths[$key]['到厂运费(船运)'] = round($transportMethodArray[2],2);
	
	if($transportMethod == '出库'){
		//到厂含税单价(汽运)
		$ths[$key]['到厂含税单价(汽运)'] = round($jiajiayinsu*1.17
		+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']
		+$ths[$key]['分票垫装费']+$ths[$key]['代理费']+$ths[$key]['到厂运费(汽运)'],2); 
		
		//到厂含税金额(汽运)
		$ths[$key]['到厂含税金额(汽运)'] = round(($jiajiayinsu*1.17
		+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']
		+$ths[$key]['分票垫装费']+$ths[$key]['代理费']+$ths[$key]['到厂运费(汽运)']) * $ths[$key]['清单重量'],2); 
		
		//到厂运费金额(汽运)
		$ths[$key]['到厂运费金额(汽运)'] = round($ths[$key]['到厂运费(汽运)'] * $ths[$key]['清单重量'],2);

		//到厂含税单价(船运)
		$ths[$key]['到厂含税单价(船运)'] = round($jiajiayinsu*1.17+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']
		+$ths[$key]['长12.5米上加50%']+$ths[$key]['分票垫装费']+$ths[$key]['代理费']+$ths[$key]['到厂运费(船运)'],2); 
		
		//到厂含税金额(船运)
		$ths[$key]['到厂含税金额(船运)'] = round(($jiajiayinsu*1.17+$ths[$key]['短途运费']+$ths[$key]['基本港杂费']
		+$ths[$key]['长12.5米上加50%']+$ths[$key]['分票垫装费']+$ths[$key]['代理费']+$ths[$key]['到厂运费(船运)']) * $ths[$key]['清单重量'],2); 
		
		//到厂运费金额(船运)
		$ths[$key]['到厂运费金额(船运)'] = round($ths[$key]['到厂运费(船运)'] * $ths[$key]['清单重量'],2);
	}
	
	//铁运到厂价格(铁运)(含保险)
	/**
	 * XXX 宽1500厚6加价？
	 */ 
	if($transportMethod == '平板' || $transportMethod == '车厢'){
		$ths[$key]['铁运到厂价格(铁运)(含保险)'] = round($jiajiayinsu*1.17117+$ths[$key]['代理费']+$ths[$key]['铁路运费(铁运)'],2);
		
		$ths[$key]['铁运到厂费用金额(铁运)(含保险)'] 
		= round(($jiajiayinsu*1.17117+$ths[$key]['代理费']+$ths[$key]['铁路运费(铁运)']) * $ths[$key]['清单重量'],2);
		
		$ths[$key]['不含运费单价'] = round($jiajiayinsu*1.17117+$ths[$key]['代理费'],2);
		
		$ths[$key]['不含运费金额(含税)']
		= round(($jiajiayinsu*1.17117+$ths[$key]['代理费']) * $ths[$key]['清单重量'],2);
	}
	
	if($transportMethod == '销售'){
		$ths[$key]['铁运到厂价格(铁运)(含保险)'] = round($jiajiayinsu*1.17+$ths[$key]['代理费']+$ths[$key]['铁路运费(铁运)'],2);
		
		$ths[$key]['铁运到厂费用金额(铁运)(含保险)'] 
		= round(($jiajiayinsu*1.17+$ths[$key]['代理费']+$ths[$key]['铁路运费(铁运)']) * $ths[$key]['清单重量'],2);
		
		$ths[$key]['不含运费单价'] = round($jiajiayinsu*1.17+$ths[$key]['代理费'],2);
		
		$ths[$key]['不含运费金额(含税)']
		= round(($jiajiayinsu*1.17+$ths[$key]['代理费']) * $ths[$key]['清单重量'],2);
	}
	
	//出厂含税总金额 = (基价+宽度加价+TMCP加价+单规格小于10吨加价)*1.17*清单重量
//	if(!empty($val['理货']))
//		$ths[$key]['出厂含税总金额'] = round(($ths[$key]['基价']+$ths[$key]['宽度加价']+$ths[$key]['TMCP加价']
//		+$ths[$key]['单规格小于10吨加价'])*1.17*$ths[$key]['清单重量'],2);
	
	//到鲅鱼圈运杂费总金额(含税)= (短途运费+基本港杂费+长12.5米上加50%+分票垫装费+代理费)*清单重量
//	if(!empty($val['理货']))
//		$ths[$key]['到鲅鱼圈运杂费总金额(含税)'] = round(($ths[$key]['短途运费']+$ths[$key]['基本港杂费']+$ths[$key]['长12.5米上加50%']
//		+$ths[$key]['分票垫装费']+$ths[$key]['代理费'])*$ths[$key]['清单重量'],2);
	
	//鲅至葫(汽运费用金额) = 到厂运费(汽运)*清单重量
//	if($transportMethod == '销售' || $transportMethod == '出库')
//		$ths[$key]['鲅至葫(汽运费用金额)'] = round($ths[$key]['到厂运费(汽运)']*$ths[$key]['清单重量'],2);
	
	//鲅至葫(船运费用金额)= 到厂运费(船运)* 清单重量
//	if($transportMethod == '出库')
//		$ths[$key]['鲅至葫(船运费用金额)'] = round($ths[$key]['到厂运费(船运)']*$ths[$key]['清单重量'],2);
	
	//为求 汽运到厂协议单价	汽运到厂协议金额	船运到厂协议单价	船运到厂总金额 (含税)	协议单价(含税)	协议金额(含税)
	//这些列做准备
	$qiyunSum += $ths[$key]['到厂含税金额(汽运)'];
	$chuanyunSum += $ths[$key]['到厂含税金额(船运)'];
	$bayuquanSum += $ths[$key]['铁运到厂费用金额(铁运)(含保险)'];
	$yingkouganSum += $ths[$key]['到营口港金额'];
	$buhanyunfeiSum += $ths[$key]['不含运费金额(含税)'];
	$weightSum +=  $ths[$key]['清单重量'];
}

$qiyunAverage = ceil($qiyunSum/$weightSum);
$bayuquanAverage = ceil($bayuquanSum/$weightSum);
$chuanyunAverage = ceil($chuanyunSum/$weightSum);
$yingkouganAverage = ceil($yingkouganSum/$weightSum);
$buhanyunfeiAverage = ceil($buhanyunfeiSum/$weightSum);

foreach ($ths as $key => $val){
	$ths[$key]['汽运到厂协议单价'] = $qiyunAverage;
	$ths[$key]['汽运到厂协议金额'] = round($qiyunAverage * $val['清单重量'],2);

	$ths[$key]['船运到厂协议单价'] = $chuanyunAverage;
	$ths[$key]['船运到厂协议金额'] = round($chuanyunAverage * $val['清单重量'],2);

	$ths[$key]['协议单价(含税)'] = $bayuquanAverage;
	$ths[$key]['协议金额(含税)'] = round($bayuquanAverage * $val['清单重量'],2);
	
	$ths[$key]['到鲅鱼圈均单价(含税)'] = $yingkouganAverage;
	$ths[$key]['到鲅鱼圈总金额(含税)'] = round($yingkouganAverage * $val['清单重量'],2);
	
	$ths[$key]['不含运费平均单价'] = $buhanyunfeiAverage;
	$ths[$key]['不含运费总金额(含税)'] = round($buhanyunfeiAverage * $val['清单重量'],2);
}
//print_r(array_keys($ths[1]));
//echo "<hr />";
//print_r($firstRow);
//echo "<hr />";
//print_r(array_diff(array_keys($ths[1]),$firstRow));
//准备写入电子表格的对象
$workbook = new Spreadsheet_Excel_Writer();
$worksheet = $workbook->addWorksheet();
$filename = $file->getFilename();
$formatRed = &$workbook->addFormat(array('fgcolor' => 'red'));
$formatPurple = &$workbook->addFormat(array('fgcolor' => 'purple'));

//插入表头
$x = 0;
foreach($firstRow as $th){
	if($th == '运输方式' || $th == 'TMCP')
		$worksheet->write(0, $x, iconv("UTF-8", "gbk", $th),$formatRed);
	else if($th == 'MQ' || $th == '材料代码' || $th == '生产厂家'
	|| $th == '船级' || $th == '材质' || $th == '厚度' || $th == '宽度' 
	|| $th == '长度' || $th == '清单数量' || $th == '单重' || $th == '清单重量' 
	|| $th == '出厂含税金额' || $th == '理货费用金额' || $th == '到鲅鱼圈均单价(含税)' 
	|| $th == '到鲅鱼圈总金额(含税)' || $th == '汽运到厂协议单价' || $th == '汽运到厂协议金额' 
	|| $th == '船运到厂协议单价' || $th == '船运到厂协议金额'
	|| $th == '协议单价(含税)' || $th == '协议金额(含税)' || $th == '运费金额')
		$worksheet->write(0, $x, iconv("UTF-8", "gbk", $th),$formatPurple);
	else
		$worksheet->write(0, $x, iconv("UTF-8", "gbk", $th));
	$x++;
}

//添加数据,$y代表行,$x代表列
$y = 1;
foreach($ths as $col){
	$x = 0;
	foreach($col as $key => $td){
		if($key == '运输方式' || $key == 'TMCP')
			$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td),$formatRed);
		else if($key == 'MQ' || $key == '材料代码' || $key == '生产厂家'
		|| $key == '船级' || $key == '材质' || $key == '厚度' || $key == '宽度' 
		|| $key == '长度' || $key == '清单数量' || $key == '单重' || $key == '清单重量' 
		|| $key == '出厂含税金额' || $key == '理货费用金额' || $key == '到鲅鱼圈均单价(含税)' 
		|| $key == '到鲅鱼圈总金额(含税)' || $key == '汽运到厂协议单价' || $key == '汽运到厂协议金额' 
		|| $key == '船运到厂协议单价' || $key == '船运到厂协议金额'
		|| $key == '协议单价(含税)' || $key == '协议金额(含税)' || $key == '运费金额')
			$worksheet->write($y, $x, iconv("UTF-8", "gbk", $td),$formatPurple);
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