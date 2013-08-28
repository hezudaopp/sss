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
 * 检查这个excel表格的第一行是否正确
 *
 * @param Spreadsheet_Excel_Reader $reader
 * @param array $mustIndex
 * @return array
 * @throws UnknownColumnIndex LackSomeColumn
 */
function checkFirstRow($reader){
	global $mustIndex;
	$lackedColumns = "";
	foreach($mustIndex as $val){
		if(!in_array($val, $reader->sheets[0]['cells'][1]))
			$lackedColumns .= $val.',';
	}
	
	/*var_dump($reader->sheets[0]['cells'][1]);
	die();*/
	
	if(!empty($lackedColumns)){
		throw new LackSomeColumn($lackedColumns);
	}
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

/*********************************************************************************/
/**
 * 将从excel表格中获得的数据格式化
 *
 * @param array $sheetRow
 * @return array
 */
/*function getFormatedFromSheetRow($sheetRow){
	global $indexArray;
	global $file;
	global $reader;
	$ret = array();
	foreach($indexArray as $key => $val){
		$ret[$val] = null;
	}
	
	$ret['filename'] = $file->getFilename();
	$count = count($sheetRow);
	for($i = 0; $i < $count; $i++){
		if(isset($indexArray[$reader->sheets[0]['cells'][1][$i+1]])){
			if(!isset($sheetRow[$i+1])){
				$count++;
			}else{
				$ret[$indexArray[$reader->sheets[0]['cells'][1][$i+1]]] = $sheetRow[$i+1];
			}
		}
			
	}
	
	return $ret;
}*/

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
 * 执行数据库中存放的数据库语句，
 * $type有三种，分别是ok, ignore, cancel
 * 每个类型执行完后都会将那条记录删除
 *
 * @param int $id
 * @param String $type
 */
function executeSQL($id, $type = 'ok'){
	if($type === 'ok'){
		//执行id中的okSQL中的语句，然后删除这条信息
		$query = 'select okSQL from sss_queryStatementTable where id = '.$id;
		$result = DB::query($query);
		$row = $result->fetch_assoc();
		if(!get_magic_quotes_gpc()){
			$SQLs = stripslashes($row['okSQL']);
		}
		
		
		$SQLArray = explode(";;;", $SQLs);
		array_pop($SQLArray);
		beginTransaction();
		foreach($SQLArray as $SQL){
			DB::query($SQL);
		}
		commit();
	}else if ($type === 'ignore'){
		//执行id中的ignoreSQL中的语句，然后删除这条信息
		$query = 'select ignoreSQL from sss_queryStatementTable where id = '.$id;
		$result = DB::query($query);
		$row = $result->fetch_assoc();
		$SQLs = $row['ignoreSQL'];
		
		$SQLArray = explode(";;;", $SQLs);
		array_pop($SQLArray);
		beginTransaction();
		foreach($SQLArray as $SQL){
			DB::query($SQL);
		}
		commit();
	}else if($type === 'cancel'){
		
	}else {
		throw new CustomException('不支持的类型:'.$type.'，类型必须为 ok, ignore, cancel中的的一种');
	}
	//删除id中的数据
	$query = 'delete from sss_queryStatementTable where id = '.$id;
	DB::query($query);
}

/**
 * 插入计算出来的插入查询语句
 *
 * @param String $ok
 * @param String $ignore
 * @return int
 */
function insertSQL($ok, $ignore){
	if(!get_magic_quotes_gpc()){
		$okSQL = addslashes($ok);
		$ignoreSQL = addslashes($ignore);
	}
	$query = "insert into sss_queryStatementTable set okSQL = '$okSQL', ignoreSQL = '$ignoreSQL';";
	DB::query($query);
	return DB::insert_id();
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
function clearTempLog(){
	$query = 'delete from sss_queryStatementTable;';
	DB::query($query);
}

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


/**
 * 清除数据库中已完成的记录
 * 返回清楚的行数
 *
 * @return int
 */
function clearSuccessed(){
	$query = 'delete from sss_mainTable where count = 0';
	DB::query($query);
	return DB::affected_rows();
}
?>