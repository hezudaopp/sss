<?php

require('FileUploader.class.php');
require_once('SmartyManager.class.php');
require_once('DB.class.php');
require('SpreadSheet/Excel/writer.php');

require('exceptions'.DS.'AppExceptions.class.php');

/**
 * 用来测试
 */
function dprint($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	die();
}


/**
 * 判断这个文件是否已经上传
 *
 * @param String $filename
 * @return boolean
 */
function haveUploaded($filename){
	$query = "select filename, uploadTime from sss_mainTable where filename = '$filename'";
	$result = DB::query($query);
	if(mysqli_num_rows($result) >= 1){
		$row = $result->fetch_assoc();
		return $row['uploadTime'];
	}else{
		return false;
	}
}


/**
 * 查看这页中是否有相同的材料代码,有返回true,否则返回false
 *
 * @param Array $sheet
 * @return boolean
 */
function haveSameMaterialCode($sheet){
	//首先将所有materialCode组成一个array
	$mcArray = array();
	$firstRow = current($sheet);
	
	///dprint($sheet);
	//dprint($firstRow);
	
	while($row = next($sheet)){
		reset($firstRow);
		if(current($firstRow) == '材料代码'){
			array_push($mcArray, current($row));
			continue;
		}
		
		while($colIndex = next($firstRow)){
			$col = next($row);
			if($colIndex == '材料代码'){
				if($col != '加片'){
					array_push($mcArray, $col);
				}
				break;
			}
		}
	}
	
/*	echo "<pre>";
	var_dump($mcArray);
	die();*/
	
	//比较$mcArray中的材料代码是否相同
	for($i = 0; $i < count($mcArray); $i++){
		for($j = $i+1; $j < count($mcArray); $j++){
			if($mcArray[$i] == $mcArray[$j])
				return true;
		}
	}
	
	return false;
}


/**
 * 保存上传的文件，返回FLEA_Helper_UploadFile对象
 * 其中的$name 为表单中的这个文件的名称
 *
 * @param string $name
 * @return FLEA_Helper_UploadFile
 * @throws FileUploadError FileTypeForbiddenException ×UploadSameFileException×
 */
function saveUploadedFile($name){
	$allowExts = 'xls';
	$uploadDir = __SITE_ROOT.'upload';
	
	$uploader =& new FLEA_Helper_FileUploader();
	
	//只关心第一个（而且也只能有一个）
	$file = $uploader->getFile($name);
	if (!$file->check($allowExts)) {
	    // 上传的文件类型不符或者超过了大小限制。
	    throw new FileTypeForbiddenException($file->getFilename());
	}
	if(!$file->isSuccessed()){
		throw new FileUploadError($file->getError());
	}
	$filename = $file->getFilename();
	
	//因为保证不了文件名唯一，所以不再检查文件重名情况了
/*	if(($time = haveUploaded($filename)) !== false){
		throw new UploadSameFileException($filename, $time);
	}*/

	$filename = iconv('UTF-8', 'GBK', $filename);
	$file->move($uploadDir . DS . $filename);
	
	return $file;
}

/**
 * 开始事务
 *
 */
function beginTransaction(){
	DB::query('begin;');
	DB::query('set transaction isolation level serializable');
}

/**
 * 回滚事务
 *
 */
function rollback(){
	DB::query('rollback;');
}

/**
 * 提交事务
 *
 */
function commit(){
	DB::query('commit');
}

/**
 * 查询数据库中这个材料代码是否存在，如果存在，返回这一行，如果不存在，返回false
 *
 * @param String $materialCode
 * @return array or boolean
 */
function getRowByMaterialCode($materialCode){
	global $indexArray;
	$toQuery = join(',', array_values($indexArray));
	$query = "select $toQuery from sss_mainTable where materialCode = '$materialCode'";
	$result = DB::query($query);
	if(mysqli_num_rows($result) != 0){
		return mysqli_fetch_assoc($result);
	}
	return false;
}

/**
 * 比较两个数组中重要的信息是否相同
 *
 * @param array $dbRow
 * @param array $formatedSheetRow
 * @return boolean
 */
function CompareForPlanInsertion($dbRow, $formatedSheetRow){
	global $compareVIP;
	foreach($compareVIP as $vip){
		$dbRow[$vip] = trim($dbRow[$vip]);
		$formatedSheetRow[$vip] = trim($formatedSheetRow[$vip]);
		if($dbRow[$vip] != $formatedSheetRow[$vip]){
			return false;
		}
	}
	return true;
}



/**
 * 根据一定规格的数据生成一个excel文件，
 * 返回文件全名
 *
 * @param array $fa
 * @return string
 */
function generateExcel($fa){
	global $file;
	global $indexArray;
		
	$newFilename = __SITE_ROOT.DS.'upload'.DS.'sss_'.$file->getFilename();
	// Creating a workbook
	$workbook = new Spreadsheet_Excel_Writer(iconv('utf-8', 'gbk', $newFilename));
	
	// sending HTTP headers
	//$workbook->send($newFilename);
	
	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet('sheet1');
	
	// The actual data
	$x = 0;
	$y = 0;
	foreach($indexArray as $key => $val){
		$key = iconv('utf-8', 'gbk', $key);
		$worksheet->write($y, $x, $key);
		$x++;
	}
	
	$x = 0;
	$y = 1;
	foreach($fa as $row){
	
/*		if($row['color'] != 'black'){
			$formate = array(
				'bgColor' => $row['color'],
				'color' => 'white'
			);
		}else {
			$formate = array(
				'color' => 'black'
			);
		}*/
		$formate = array(
			'color' => $row['color']
			);
		$sformate = &$workbook->addFormat($formate);
		$x = 0;
		foreach($row['row'] as $td){
			$td = iconv('utf-8', 'gbk', $td);
			$worksheet->write($y, $x, $td, $sformate);
			$x++;
		}
		$y++;
	}
	
	// Let's send the file
	$workbook->close();
	return $newFilename;
}


/* *************************************************************** */

function clearDir($dirname, $ext = null){
	$files = scandir($dirname);
	foreach($files as $filename){
		$fn = strtolower(iconv('gbk', 'utf-8', $filename));
		//echo $fn;
		if($filename == '.' || $filename == '..'){
			continue;
		}else if(strstr($fn, $ext)){
			unlink($dirname.DS.$filename);
		}
	}
	return true;
}

function clearTempExcel(){
	$ext = 'xls';
	return clearDir(__SITE_ROOT.'upload', $ext);
}

?>