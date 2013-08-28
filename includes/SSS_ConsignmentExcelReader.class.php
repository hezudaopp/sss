<?php

require("SSS_ExcelReader.class.php");

class SSS_ConsignmentExcelReader extends SSS_ExcelReader {
	
	private $fileId = null;
	
	public function __construct(){
		$this->tableName = 'sss_consignment';
		$this->reader = new Spreadsheet_Excel_Reader();
		$this->reader->setUTFEncoder('iconv');
		$this->reader->setOutputEncoding('utf-8');
	}
	
	
	function saveFileInfo(&$record){
		global $file;
		$query = "insert into sss_consignment_file set filename = '{$file->getFilename()}', record = '$record', uploadTime = now()";
		DB::query($query);
		$this->fileId = DB::insert_id();
	}
	
	/**
	 * 判断文件是否已经上传过
	 * 如果已经上传过，返回上回上传的时间
	 *
	 * @return date or boolean
	 */
	public function myHaveUploaded(){
		$query = "select filename, uploadTime from sss_consignment_file where filename = '$this->filename'";
		$result = DB::query($query);
		if(mysqli_num_rows($result) >= 1){
			$row = $result->fetch_assoc();
			return $row['uploadTime'];
		}
		return false;
	}
	
	/**
	 * 检查第一行是否正确
	 * @throw LackSomeColumn
	 */
	public function checkMyFirstRow(){
		$lackedColumns = "";
		$unknownColumns = "";

		if(!isset($this->reader->sheets[0])){
			throw new ExcelMetaDataError();
		}
		
		$chineseColumns = array_keys($this->tableColumns);
		array_push($chineseColumns, '序号');
		foreach($this->reader->sheets[0]['cells'][1] as $col){
			if(!in_array($col, $chineseColumns)){
				$unknownColumns .= $col.',';
			}
		}
		
		if(!empty($unknownColumns)){
			throw new UnknownColumnIndex($unknownColumns);
		}
		
		foreach($this->obligatoColumns as $val){
			if(!in_array($val, $this->reader->sheets[0]['cells'][1]))
				$lackedColumns .= $val.',';
		}

		if(!empty($lackedColumns)){
			throw new LackSomeColumn($lackedColumns);
		}
	}
	
	
	/**
	 * 将电子表格中的信息读取数据库，读入之前必须先设置
	 * $tableColumns
	 *
	 * @throws DBConnectException DBQueryException CustomException
	 */
	public function myReadIntoDB(){
		
		try{
			date_default_timezone_set('Asia/Shanghai');
			$currentTime = date('Y/m/d H:i:s');
	
			$valuesStr = "";
			//dprint($this->reader->sheets[0]['cells']);
			for($i = 2; $i <= $this->reader->sheets[0]['numRows']; $i++){
				if(!isset($this->reader->sheets[0]['cells'][$i])){
					continue;
				}
				try{
				$formatedSheetRow = $this->getFormatedFromSheetRow($this->reader->sheets[0]['cells'][$i]);
				}catch(CustomException $e){
					//var_dump($formatedSheetRow);
					throw new CustomException($e.'Excel表格出错行数：'.$i);
				}
				if($formatedSheetRow == null){
					continue;
				}
	
				//下面对数据进行过滤
				foreach($formatedSheetRow as $key => $val){
					$formatedSheetRow[$key] = Filter::forDBInsertion($val);
				}
	
				//$value = ", ('".join("', '", $formatedSheetRow)."','{$this->fileId}')\n";
				
				$value = "";
				foreach($formatedSheetRow as $k => $v){
					if(empty($v)){
						$value .= 'null, ';
					}else{
						$value .= "'$v', ";
					}
				}
				$value = ", (".$value."'{$this->fileId}')\n";
				$valuesStr .= $value;
			}
	
			/*$tmp = trim($valuesStr);
			if(empty($tmp)){
				return;
			}*/
			$valuesStr = substr($valuesStr, 1);
	
			$cols = " (`".join("`, `", array_unique(array_values($this->tableColumns)))."`, `fileId`) ";
			$query = "insert into {$this->tableName}$cols values $valuesStr;";
	
				DB::query($query);
		}catch(Exception $e){
			throw $e;
			/*$query = 'delete from sss_consignment_file where id = '. $this->fileId;
			DB::query($query);
			throw $e;*/
		}
	}
	
		/**
	 * 将从excel表格中获得的数据格式化,丢弃了“重量”这一列，
	 * 因为可以用数量和单重相乘计算出来，如果某一行的材料代码列没有数据，那么返回null
	 *
	 * @param array $sheetRow
	 * @return array
	 * @throws CustomException
	 */
	private function getFormatedFromSheetRow($sheetRow){
		$ret = array();
		foreach($this->tableColumns as $key => $val){
			$ret[$val] = null;
		}
		$count = count($sheetRow);
		for($i = 0; $i < $count; $i++){

			if(isset($this->tableColumns[$this->reader->sheets[0]['cells'][1][$i+1]])){

				$key = $this->tableColumns[$this->reader->sheets[0]['cells'][1][$i+1]];
				
				if(!isset($sheetRow[$i+1])){
					$count++;
				}else{
					//如果这一行材料代码为空，那么返回null
					if($key == 'materialCode'){
						$sheetRow[$i+1] = trim($sheetRow[$i+1]);
						if(iconv_strlen($sheetRow[$i + 1], 'utf-8') > 32){
							throw new DataTooLongForDBInsertion($sheetRow[$i+1], 32);
						}
					}
					$ret[$key] = $sheetRow[$i+1];
				}
			}
		}

		return $ret;
	}

	/**
	 * 检查表格中的船号和分段号是否正确,并检查该行数据在数据库中是否存在
	 * 注意：没有排除重复
	 * 作为数组返回
	 *
	 * @return array
	 */
	function checkShipNumberAndSubsectionNumber(){
		$sheet = $this->reader->sheets[0]['cells'];
		$firstRow = current($sheet);

		$shipKey = null;
		$subsectionKey = null;
		$materialKey = null;
		$orderKey = null;
		$orderSubitemKey = null;
		$purchaseKey = null;
		$collisionMaterials = null;
		$notInDBMaterials = null;
		foreach($firstRow as $key => $val){
			if($val=='材料代码'){
				$materialKey = $key;
			}else if($val == '船号'){
				$shipKey = $key;
			}else if($val == '分段号'){
				$subsectionKey = $key;
			}else if($val == '订单号'){
				$orderKey = $key;
			}else if($val == '订单子项号'){
				$orderSubitemKey = $key;
			}else if($val == '购单号'){
				$purchaseKey = $key;
			}
		}
		/*if($shipKey == null){
			throw new LackSomeColumn("船号");
		}else if($subsectionKey == null){
			throw new LackSomeColumn("分段号");
		}*/

		while($row = next($sheet)){
			if(empty($row[$subsectionKey])&& empty($row[$materialKey]) && empty($row[$shipKey])&& empty($row[$orderKey])&& empty($row[$orderSubitemKey])&& empty($row[$purchaseKey])){
				throw new existNullColumn("材料代码,船号,分段号,订单号,订单子项号,购单号");
			}
			
			if(!empty($row[$materialKey]) && ((!empty($row[$shipKey]) && substr_compare($row[$materialKey],$row[$shipKey],0,5)!=0) || (!empty($row[$subsectionKey]) && substr_compare($row[$materialKey],$row[$subsectionKey],8,3)) != 0)){
				$collisionMaterials .= $row[$materialKey].', ';
			}
		}
		if(!empty($collisionMaterials))
			throw new existCollisionColumn($collisionMaterials);
	}

	/**
	 * 获得表格中的所有列的数据(船号和分段号的数据没有必要获取，可以从材料代码中提取)
	 */
	function getConsignmentColumn(){
		$sheet = $this->reader->sheets[0]['cells'];
		$mcArray = array();
		$firstRow = current($sheet);
		
		$serialKey = null;
		$materialKey = null;
		$shipKey = null;
		$subsectionKey = null;
		$orderKey = null;
		$orderSubitemKey = null;
		$purchaseKey = null;
		$consignmentKey = null;
		foreach($firstRow as $key => $val){
			if($val=='材料代码'){
				$materialKey = $key;
			}else if($val == '序号'){
				$serialKey = $key;
			}else if($val == '船号'){
				$shipKey = $key;
			}else if($val == '分段号'){
				$subsectionKey = $key;
			}else if($val == '订单号'){
				$orderKey = $key;
			}else if($val == '订单子项号'){
				$orderSubitemKey = $key;
			}else if($val == '购单号'){
				$purchaseKey = $key;
			}else if($val == '发货批次'){
				$consignmentKey = $key;
			}
		}
		
		while($row = next($sheet)){
			$col = $row[$serialKey] ;
			$col = trim($col);
			if(!empty($col)){
				array_push($mcArray, array(isset($row[$materialKey])?$row[$materialKey]:null,isset($row[$shipKey])?$row[$shipKey]:null,isset($row[$subsectionKey])?$row[$subsectionKey]:null, isset($row[$orderKey])?$row[$orderKey]:null, isset($row[$orderSubitemKey])?$row[$orderSubitemKey]:null, isset($row[$purchaseKey])?$row[$purchaseKey]:null, isset($row[$consignmentKey])?$row[$consignmentKey]:null));
			}
		}
		
		return $mcArray;
	}
}

?>