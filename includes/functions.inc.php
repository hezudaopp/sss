<?php
require_once 'FileUploader.class.php';
require_once('SmartyManager.class.php');
require_once('MainTableInstall.class.php');
require_once('DB.class.php');
require_once('Spreadsheet/Excel/Writer.php');
require_once('exceptions'.DS.'AppExceptions.class.php');
require_once('LogInserter.class.php');

//require_once('debug.inc.php');

/**
 * 用来测试
 */
function dprint($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	die();
}


//filename 是一个值-结果类型
/**
 * 保存上传的文件，返回FLEA_Helper_UploadFile对象
 * 其中的filename 是一个值-结果类型
 *
 * @param string $name
 * @return FLEA_Helper_UploadFile
 * @throws FileUploadError FileTypeForbiddenException
 */
function saveUploadedFile($name){
	$allowExts = 'xls,xlsx';
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
	/*if(($time = haveUploaded($filename)) !== false){
		throw new UploadSameFileException($filename, $time);
	}*/
	//只有在是utf-8编码的情况才进行转码
	if(is_utf8($filename)==1)
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
//	DB::query('set transaction isolation level serializable');
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
			'Color' => $row['color']
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

/**
 * 通过列生成excel（从左到右写）
 * @param $data
 * @param $filename
 * @return unknown_type
 */
function generateHttpExcelByCols($data, $filename = null){

	if($filename == null){
		$filename = date('Ymd')+'.xls';
	}
	$workbook = new Spreadsheet_Excel_Writer();

	// sending HTTP headers
	$workbook->send($filename);

	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet('sheet1');

	$x = 0;
	$y = 0;
	foreach($data as $key => $val){
		$y = 0;
		$td = iconv('utf-8', 'gbk', $key);
		$worksheet->write($y, $x, $td);

		$y = 1;
		foreach($val as $td){
			$td = iconv('utf-8', 'gbk', $td);
			$worksheet->write($y, $x, $td);
			$y++;
		}
		$x++;
	}

	// Let's send the file
	$workbook->close();
}

/**
 * 通过列生成excel（从左到右写），不过最后一行字体颜色是红色
 * @param $data
 * @param $filename
 * @return unknown_type
 */
function generateHttpExcelByColsSpecial($data, $filename = null){

	if($filename == null){
		$filename = date('Ymd')+'.xls';
	}
	$workbook = new Spreadsheet_Excel_Writer();

	// sending HTTP headers
	$workbook->send($filename);

	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet('sheet1');

	$x = 0;
	$y = 0;
	foreach($data as $key => $val){
		$y = 0;
		$td = iconv('utf-8', 'gbk', $key);
		$worksheet->write($y, $x, $td);

		$y = 1;
		$cols = count($val);
		foreach($val as $td){
			$td = iconv('utf-8', 'gbk', $td);
			if($cols != $y){
				$worksheet->write($y, $x, $td);
			}else{
				$formate = array('Color' => 'red','BgColor' => 'black');
				$sformate = &$workbook->addFormat($formate);
				$worksheet->write($y, $x, $td,$sformate);
			}
			$y++;
		}
		$x++;
	}

	// Let's send the file
	$workbook->close();
}

function generateLogHttpExcelByCols($data, $filename = null){

	if($filename == null){
		$filename = date('Ymd')+'.xls';
	}
	$workbook = new Spreadsheet_Excel_Writer();

	// sending HTTP headers
	$workbook->send($filename);

	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet('sheet1');

	$worksheet->setColumn(2,3,40);
	
	$x = 0;
	$y = 0;
	foreach($data as $key => $val){
		$y = 0;
		$td = iconv('utf-8', 'gbk', $key);
		$worksheet->write($y, $x, $td);

		$y = 1;
		foreach($val as $td){
			$td = iconv('utf-8', 'gbk', $td);
//			$worksheet->writeNote($y, $x, $td);
			$worksheet->write($y, $x, $td);
			$y++;
		}
		$x++;
	}

	// Let's send the file
	$workbook->close();
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


/****************************************************************************/

/**
 * 获得这个文件中所有的项的信息，包括加片
 * 信息包括：材料代码，船级，材质，厚宽长
 * 然后以二维数组的形式返回
 * @param  string $filename
 */
function getMcInfos($filename){

	$query = "select distinct materialCode, shipsClassification, material, thickness, width, length from sss_main
		where filename = '$filename'";
	DB::query($query);
	$mcs = array();
	$scs = array();
	$ms = array();
	$ts = array();
	$ws = array();
	$ls = array();

	while($row = DB::getResult()->fetch_assoc()){
		array_push($mcs, $row['materialCode']);
		array_push($scs, $row['shipsClassification']);
		array_push($ms, $row['material']);
		array_push($ts, $row['thickness']);
		array_push($ws, $row['width']);
		array_push($ls, $row['length']);
	}

	return array(
		'materialCode' => $mcs,
		'shipsClassification' => $scs,
		'material' => $ms,
		'thickness' => $ts,
		'width' => $ws,
		'length' => $ls);
}

/**
 * 获得这个材料代码的总数量
 *
 * @param string $mc
 * @return int
 */
function getSumCount($mc)
{
	$query = "select sum(`count`) as sumCount from sss_main where materialCode = '$mc' group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();

	return $row['sumCount'];
}

/**
 * 获得这个材料代码直接销售的数量
 *
 * @param string $mc
 * @return int
 */
function getDirectCount($mc){

	$query = "select `count` from sss_fache where phase = '销售' and materialCode = '$mc'";
	DB::query($query);
	$count = 0;
	while($row = DB::getResult()->fetch_assoc()){
		$count += $row['count'];
	}

	return $count;
}

/**
 * 获得这个材料代码中出库的数量
 *
 * @param string $mc
 * @return int
 */
function getChukuCount($mc){
	$query = "select sum(`count`) as sumCount from sss_fache where phase = '出库' and materialCode = '$mc' group by materialCode";
	DB::query($query);
	$count = 0;
	$row = DB::getResult()->fetch_assoc();
	$count = $row['sumCount'];
	$query = "select sum(`count`) as sumCount from sss_fachuan where materialCode = '$mc' group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();
	$count += $row['sumCount'];
	return $count;
}


/**
 * 获得这个材料代码中入库的数量
 *
 * @param string $mc
 * @return int
 */
function getRukuCount($mc){

	$query = "select sum(`count`) as sumCount from sss_fache where phase = '入库' and materialCode = '$mc' group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();

	return $row['sumCount'];
}

/**
 * 获得所有文件和文件上传时间的信息，
 * 返回一个二维数组
 * array('file' => xxx,
 * 		'uploadTime' => xxx);
 *
 * @return array
 */
function getAllFilesAndUploadTimes(){
	$query = 'select distinct filename, uploadTime from sss_main';
	DB::query($query);

	$files = array();
	$uts = array();
	while($row = DB::getResult()->fetch_assoc()){
		array_push($files, $row['filename']);
		array_push($uts, $row['uploadTime']);
	}

	return array(
		'file' => $files,
		'uploadTime' => $uts
	);
}


/**
 * 获得这个文件中的所有mc,这里获得的mcs不包括“加片”和其它汉字材料代码,是unique的吗？是
 *
 * @param string $file
 * @return array
 */
function getMaterialCodes($file){

	$query = "select distinct materialCode from sss_main where filename = '$file' and materialCode != '加片'
			and materialCode != '扁钢' and materialCode != '现货' and materialCode != '舾装' and materialCode != '大坞1'
			and materialCode != '大坞2' and materialCode != '大坞3' and materialCode != '大坞4' and materialCode != '大坞5'";
	DB::query($query);
	$mcs = array();
	while($row = DB::getResult()->fetch_assoc()){
		array_push($mcs, $row['materialCode']);
	}

	return $mcs;
}

/**
 * 获得这个文件中“加片”的数量
 *
 * @param string $file
 */
function getJiapianCount($file){

	$query = "select count(*) from sss_main where materialCode = '加片' and filename = '$file'";
	DB::query($query);

	$row = DB::getResult()->fetch_assoc();

	return $row['count(*)'];
}


/**
 * 删除数据库中这个材料代码的所有信息
 *
 * @param string $mc
 */
function deleteMaterialCode($mc){

	$query = "delete from sss_main where materialCode = '$mc'";
	DB::query($query);
	$query = "delete from sss_fache where materialCode = '$mc'";
	DB::query($query);
	$query = "delete from sss_fachuan where materialCode = '$mc'";
	DB::query($query);
}

function deleteByMaterialCodeAndOrders($mc, $order, $orderSub){
	$query = "delete from sss_main where materialCode = '$mc' and orderNumber = '$order' and orderSubitemNumber = '$orderSub'";
	DB::query($query);
	$query = "delete from sss_fache where materialCode = '$mc' and orderNumber = '$order' and orderSubitemNumber = '$orderSub'";
	DB::query($query);
	$query = "delete from sss_fachuan where materialCode = '$mc' and orderNumber = '$order' and orderSubitemNumber = '$orderSub'";
	DB::query($query);
}

function deleteOnlyByFilename($filename){
	$query = "delete from sss_main where filename = '$filename'";
	DB::query($query);
}


/**
 * 删除在这个文件中所有材料代码的信息
 * 注意：这里没有删除任何为“加片”和其它汉字材料代码的信息
 *
 * @param string $filename
 */
function deleteAllMaterialCodesInFile($filename){

	beginTransaction();
	$mcs = getMaterialCodes($filename);

	foreach($mcs as $mc){
		deleteMaterialCode($mc);
	}
	commit();

}


/**
 * 更新在这个文件中所有批号，物料号，目的地，库存地，结算批号，证书号的信息
 *
 * @param string $filename
 */
function updateInFile($filename){

	beginTransaction();
	//已入库的信息需要更新产品质量证明书编号
	$mainSql = "select materialCode, orderNumber, orderSubitemNumber from sss_main
								where filename = '{$filename}'";
	$mainResult = DB::query($mainSql);
	while(($mainRow = $mainResult->fetch_assoc())!=null){
		$rukuSql = "select batchNumber, materialNumber, storePlace, destination, checkoutBatch, certificateNumber from sss_fache
			 		where materialCode = '{$mainRow['materialCode']}' and orderNumber = '{$mainRow['orderNumber']}' and orderSubitemNumber = '{$mainRow['orderSubitemNumber']}' and phase = '入库' limit 1";
			$rukuResult = DB::query($rukuSql);
			$rukuRow = $rukuResult->fetch_assoc();
			if(count($rukuRow)>0){
				$updateSql = "update sss_main set batchNumber = '{$rukuRow['batchNumber']}', materialNumber = '{$rukuRow['materialNumber']}', 
						storePlace = '{$rukuRow['storePlace']}', destination = '{$rukuRow['destination']}', checkoutBatch = '{$rukuRow['checkoutBatch']}',  
						certificateNumber = '{$rukuRow['certificateNumber']}'
						where materialCode = '{$mainRow['materialCode']}' and orderNumber = '{$mainRow['orderNumber']}' and orderSubitemNumber = '{$mainRow['orderSubitemNumber']}'";
				DB::query($updateSql);
			}
	}
	commit();

}


/**
 * 删除在这个文件中所有材料代码+订单号订单子项号的信息
 * 注意：这里没有删除也会删除加片和其他汉字材料代码的信息。
 *
 * @param string $filename
 */
function deleteAllMaterialCodesAndOrdersInFile($filename){

	beginTransaction();
	$query = "delete from sss_fache
		where exists (select 1 
					from sss_main
					where sss_fache.materialCode = sss_main.materialCode
						and sss_fache.orderNumber = sss_main.orderNumber
						and sss_fache.orderSubitemNumber = sss_main.orderSubitemNumber
						and sss_main.filename = '$filename')";
	DB::query($query);
	//echo "delete from fache table:".DB::affected_rows()."<br />";
	
	$query = "delete from sss_fachuan
		where exists (select 1 
					from sss_main
					where sss_fachuan.materialCode = sss_main.materialCode
						and sss_fachuan.orderNumber = sss_main.orderNumber
						and sss_fachuan.orderSubitemNumber = sss_main.orderSubitemNumber
						and sss_main.filename = '$filename')";
	DB::query($query);
	//echo "delete from fachuan table:".DB::affected_rows()."<br />";
	
	$query = "delete from sss_main
			where filename = '$filename'";
	DB::query($query);
	//echo "delete from main table:".DB::affected_rows()."<br />";
	
	commit();

}

/////////////////////对加片的一些函数//////////////////////////////
/**
 * 获得所有“加片”的船级材质、厚、宽、长、count的信息,
 * 返回的是数据库的查询结果，没有整理
 *
 * @return mysqli_result
 */
function getJiapianInfos(){

	$query = 'select shipsClassification, material, thickness, width, length, sum(`count`) as sumCount
			from sss_main
			where materialCode = "加片"
			group by shipsClassification, material, thickness, width, length;';
	DB::query($query);

	return DB::getResult();
}

/**
 * 获得某个加片的入库的个数,
 * 需要传入的参数要足以确定某个加片，
 * 参数信息： 船级，材质，厚，宽，长
 *
 * @param string $shipsClassi
 * @param string $material
 * @param double $thickness
 * @param double $width
 * @param double $length
 * @return int
 */
function getJiapianRukuCount($shipsClassi, $material, $thickness, $width, $length){

	$query = "select sum(`count`) as sumCount
			from sss_fache
			where shipsClassification = '{$shipsClassi}'
				and material = '$material'
				and thickness = '$thickness'
				and width = '$width'
				and length = '$length'
				and materialCode = '加片'
				and phase = '入库'
			group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();

	return $row['sumCount'];
}


/**
 * 获得某个加片的出库的个数,
 *
 * @param string $shipsClassi
 * @param string $material
 * @param double $thickness
 * @param double $width
 * @param double $length
 * @return int
 */
function getJiapianChukuCount($shipsClassi, $material, $thickness, $width, $length){

	$query = "select sum(`count`) as sumCount
			from sss_fache
			where shipsClassification = '{$shipsClassi}'
				and material = '$material'
				and thickness = '$thickness'
				and width = '$width'
				and length = '$length'
				and materialCode = '加片'
				and phase = '出库'
			group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();
	$count = $row['sumCount'];

	$query = "select sum(`count`) as sumCount
			from sss_fachuan
			where shipsClassification = '{$shipsClassi}'
				and material = '$material'
				and thickness = '$thickness'
				and width = '$width'
				and length = '$length'
				and materialCode = '加片'
			group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();
	$count += $row['sumCount'];
	return $count;
}


/**
 * 获得某个加片的直接销售的个数,
 *
 * @param string $shipsClassi
 * @param string $material
 * @param double $thickness
 * @param double $width
 * @param double $length
 * @return int
 */
function getJiapianDirectCount($shipsClassi, $material, $thickness, $width, $length){

	$query = "select sum(`count`) as sumCount
			from sss_fache
			where shipsClassification = '{$shipsClassi}'
				and material = '$material'
				and thickness = '$thickness'
				and width = '$width'
				and length = '$length'
				and materialCode = '加片'
				and phase = '销售'
			group by materialCode";
	DB::query($query);
	$row = DB::getResult()->fetch_assoc();

	return $row['sumCount'];
}

function findAndLog($query, $type){
	DB::query($query);
	$result = DB::getResult();
	$rows = array();
	while($row = $result->fetch_assoc()){
		array_push($rows, $row);
	}
	if($type == 'delete'){
		LogInserter::logForDelete($rows);
	}else if($type == 'edit'){
		LogInserter::logForEdit($rows);
	}else if($type == 'insert'){
		LogInserter::logForInsert($rows);
	}
}

function findAndLogEdit($query, $last){
	DB::query($query);
	$result = DB::getResult();
	$rows = array();
	while($row = $result->fetch_assoc()){
		array_push($rows, $row);
	}
	array_push($rows, $last);
	LogInserter::logForEdit($rows);
}

/**
 * 判断$str是否是utf-8编码，是回传1，否则回传0
 * @param $str
 * @return unknown_type
 */
function is_utf8($str){
return preg_match('%^(?:
[\x09\x0A\x0D\x20-\x7E] # ASCII
| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
)*$%xs', $str);
}

?>