<?php
require_once('Spreadsheet/Excel/reader.php');
require_once('Verifier.class.php');
require_once("Filter.class.php");
require_once("Accountant.class.php");
//require_once("Debug.inc.php");

class SSS_ExcelReader{
	/**
	 * 使用的Spreadsheet_Excel_Reader
	 *
	 * @var Spreadsheet_Excel_Reader
	 */
	protected $reader;


	/**
	 * 当前的电子表格文件名称
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * 数据库中的表名
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * 数据库跟电子表格列的对应关系
	 *
	 * @var array
	 */
	protected $tableColumns;

	/**
	 * 比较的时候，只需比较这个数组中列出的那些值即可
	 *
	 * @var array
	 */
	protected $compareColumns;


	protected $widthIndex = null;
	protected $thicknessIndex = null;
	protected $lengthIndex = null;
	protected $shipIndex = null;
	protected $materialIndex = null;
	protected $mcIndex = null;
	protected $orderNumberIndex = null;
	protected $orderSubitemNumberIndex = null;
	protected $batchNumberIndex = null;
	protected $purchaseNumberIndex = null;
	
	/**
	 * 不能缺少的列的数组，如果电子表格中缺少了，那么就要提示错误
	 *
	 * @var array
	 * @throws InvalidArgumentsException
	 */
	protected $obligatoColumns;


	public function __construct(){
		$this->reader = new Spreadsheet_Excel_Reader();
		$this->reader->setUTFEncoder('iconv');
		$this->reader->setOutputEncoding('utf-8');
	}

	public function __set($name, $value){
		if($name == 'tableName' || $name == 'tableColumns'
		|| $name == 'compareColumns' || $name == 'obligatoColumns'){
			$this->$name = $value;
		}else{
			throw new InvalidArgumentsException('当前类中没有这个属性：'.$name);
		}
	}

	/**
	 * 读取某个电子表格文件，
	 *
	 * @param string $filename 电子表格文件名
	 * @throws FileNotReadableException
	 */
	public function read($filename){
		$this->filename = iconv('gbk', 'utf-8', basename($filename));
		$this->reader->read($filename);
	}


	/**
	 * 检查第一行是否正确
	 * @throw LackSomeColumn
	 */
	public function checkFirstRow($type){
		$lackedColumns = "";
		$redundantColumns = "";
		$remarks = array("备注1","备注2","备注3","备注4","备注5");
		
		if(!isset($this->reader->sheets[0])){
			throw new ExcelMetaDataError();
		}

		foreach($this->obligatoColumns as $val){
			if(!in_array($val, $this->reader->sheets[0]['cells'][1]))
				$lackedColumns .= $val.',';
		}

		if(!empty($lackedColumns)){
			throw new LackSomeColumn($lackedColumns);
		}
		
		foreach($this->reader->sheets[0]['cells'][1] as $val){
			if(!in_array($val,$this->obligatoColumns) && !in_array($val,$remarks))
				$redundantColumns .= $val.',';
		}
		
		if(!empty($redundantColumns)){
			throw new RedundantColumn($redundantColumns);
		}
		
		$sheet = array();
		foreach ($this->reader->sheets[0]['cells'][1] as $key => $val){
			$key--;
			$sheet[$key] = $val;
		}
		
		$result = array_diff_assoc($sheet,$this->obligatoColumns);
		
		if(count($result)!=0){
			foreach ($result as $value){
				if(!in_array($value,$remarks)){
					$result = join(",",$result);
					throw new ExistDifferentColumns($result,$type);
				}
			}
		}
	}

	/**
	 * excelOperator 检查是否缺少必要的列
	 * @return unknown_type
	 */
	public function checkLackedColumns(){
		$lackedColumns = "";
		if(!isset($this->reader->sheets[0])){
			throw new ExcelMetaDataError();
		}
		
		foreach ($this->reader->sheets[0]['cells'][1] as $key => $val){
			if(!is_utf8($val))
				$this->reader->sheets[0]['cells'][1][$key] = iconv("gbk","utf-8",$val);
		}
		
//		print_r($this->reader->sheets[0]['cells'][1]);
		
		foreach($this->obligatoColumns as $val){
			if(!in_array($val, $this->reader->sheets[0]['cells'][1]))
				$lackedColumns .= $val.',';
		}

		if(!empty($lackedColumns)){
			throw new LackSomeColumn($lackedColumns);
		}
	}
	
	
	/**
	 * 判断文件是否已经上传过
	 * 如果已经上传过，返回上回上传的时间
	 *
	 * @return date or boolean
	 */
	function haveUploaded(){
		$query = "select filename, uploadTime from {$this->tableName} where filename = '$this->filename'";
		$result = DB::query($query);
		if(mysqli_num_rows($result) >= 1){
			$row = $result->fetch_assoc();
			return $row['uploadTime'];
		}

		return false;
	}
	
	
	/**
	 * 这里的MaterialCharacter表示材料的特征，包括船级，材质，厚宽长，材料代码
	 * 这个函数只对“加片”和“汉字”材料代码有用，
	 * 他用来检查当前上传的这个文件中MaterialCharacter相同的情况下，
	 * 是不是有orderNumber和orderSubitemNumber不同的情况，
	 * 如果有的话，按照有错误进行处理
	 */
	public function checkMaterialCharacterAndOrderNumbersConflicts(){
		
		if($this->widthIndex == null){
			$this->getColsIndex();
		}
		
		$sheet = $this->reader->sheets[0]['cells'];
		$cnChars = array();
		
		//将“加片”也填上
		$charsArray = Accountant::getChineseCharsArray();
		array_push($charsArray, '加片');
		
		while(next($sheet)){
			$row = current($sheet);
			//如果是汉字材料代码
			if(isset($row[$this->mcIndex]) && in_array($row[$this->mcIndex], $charsArray)){
				//检查他是否有冲突
				if($this->object_array_walk($cnChars, 'chineseCharsConflict', $row)){
					throw new MaterialCharacterAndOrderNumbersConfilicts($row);
				}else{
					array_push($cnChars, $row);
				}
			}
		}
		
	}
	
	/**
	 * 检查材料代码相同，但订单号和订单子项号不同这样的情况。
	 * 这个不仅要查在本文件中是否会出现这种情况，还要查本文件和数据库中是否会出现这种情况。
	 *
	 */
	public function checkMaterialAndOrderNumbersConflicts(){
		if($this->widthIndex == null){
			$this->getColsIndex();
		}
		
		$sheet = $this->reader->sheets[0]['cells'];
		
		while(next($sheet)){
			
		}
		
		$fileConflicts = array();
		$dbConflicts = array();
		
	}
	
	
	public function getColsIndex(){

		if(!isset($this->reader->sheets[0])){
			throw new ExcelMetaDataError();
		}
		$sheet = $this->reader->sheets[0]['cells'];
		$firstRow = current($sheet);
		
		foreach($firstRow as $key => $val){
			if($val == '船级'){
				$this->shipIndex = $key;
			}else if($val == '材料代码'){
				$this->mcIndex = $key;
			}else if($val == '材质'){
				$this->materialIndex = $key;
			}else if($val == '厚'){
				$this->thicknessIndex = $key;
			}else if($val == '宽'){
				$this->widthIndex = $key;
			}else if($val == '长'){
				$this->lengthIndex = $key;
			}else if($val == '订单号'){
				$this->orderNumberIndex = $key;
			}else if($val == '订单子项号'){
				$this->orderSubitemNumberIndex = $key;
			}else if($val == '批号'){
				$this->batchNumberIndex = $key;
			}else if($val == '购单号'){
				$this->purchaseNumberIndex = $key;
			}
		}
		
		//echo "the indexs is :" . $this->widthIndex."(宽)," . $this->thicknessIndex. "(厚)," . $this->lengthIndex ."(长),". $this->shipIndex ."(船级),". $this->materialIndex . "(材质)," . $this->mcIndex . "(材料代码);" 
		//	. $this->orderNumberIndex ."(订单号);". $this->orderSubitemNumberIndex."(订单子项号);";
			
	}
	
	
	/**
	 * 原因是系统提供的array_walk函数，其接受的函数名称不能是对象的成员函数，
	 * 而这个函数解决了这些问题
	 *
	 * @param array $aa
	 * @param string $fun
	 * @param array $comp
	 * @return bool
	 */
	protected function object_array_walk($aa, $fun, $comp){
		foreach($aa as $key => &$a){
			if($this->$fun($a, $key, $comp)){
				return true;
			}
		}
		return false;
	}
	/**
	 * 注意：虽然函数名称为chineseChars但是，这里应该包括“加片”
	 * 比较两列是否有冲突，这里的冲突这样定义：
	 * 如果两个材料代码的特质都相同，理应算作一个，但是他们的订单号和订单子项号不同，
	 * 而且这个不同时发生在同一个文件中，这样就会造成所谓的冲突
	 * 在使用这个之前，一定要首先调用getColsIndex()函数对那些indexs进行数据填充
	 *
	 * @param array $item
	 * @param string $key
	 * @param array $comp
	 * @return boolean
	 */
	private function chineseCharsConflict(&$item, $key, &$comp){
		/*if($this->widthIndex == null){
			$this->getColsIndex();
		}*/
		//避免因为两者都没有定义，而无法比较的情况
		//比如这两材料代码其实一样，但就是因为他们的“船级”都没有定义，因此无法比较，从而得出结论：没有冲突
		isset($comp[$this->widthIndex]) ? null : $comp[$this->widthIndex] = null;
		isset($comp[$this->lengthIndex]) ? null : $comp[$this->lengthIndex] = null;
		isset($comp[$this->thicknessIndex]) ? null : $comp[$this->thicknessIndex] = null;
		isset($comp[$this->shipIndex]) ? null : $comp[$this->shipIndex] = null;
		isset($comp[$this->materialIndex]) ? null : $comp[$this->materialIndex] = null;
		isset($comp[$this->mcIndex]) ? null : $comp[$this->mcIndex] = null;
		isset($comp[$this->orderNumberIndex]) ? null : $comp[$this->orderNumberIndex] = null;
		isset($comp[$this->orderSubitemNumberIndex]) ? null : $comp[$this->orderSubitemNumberIndex] = null;
		isset($comp[$this->batchNumberIndex]) ? null : $comp[$this->batchNumberIndex] = null;
		isset($comp[$this->purchaseNumberIndex]) ? null : $comp[$this->purchaseNumberIndex] = null;
		
		
		isset($item[$this->widthIndex]) ? null : $item[$this->widthIndex] = null;
		isset($item[$this->lengthIndex]) ? null : $item[$this->lengthIndex] = null;
		isset($item[$this->thicknessIndex]) ? null : $item[$this->thicknessIndex] = null;
		isset($item[$this->shipIndex]) ? null : $item[$this->shipIndex] = null;
		isset($item[$this->materialIndex]) ? null : $item[$this->materialIndex] = null;
		isset($item[$this->mcIndex]) ? null : $item[$this->mcIndex] = null;
		isset($item[$this->orderNumberIndex]) ? null : $item[$this->orderNumberIndex] = null;
		isset($item[$this->orderSubitemNumberIndex]) ? null : $item[$this->orderSubitemNumberIndex] = null;
		isset($comp[$this->batchNumberIndex]) ? null : $comp[$this->batchNumberIndex] = null;
		isset($comp[$this->purchaseNumberIndex]) ? null : $comp[$this->purchaseNumberIndex] = null;
		/**
		 * 这里的顺序不是随意的，什么随机性大，就应该放在前面，
		 * 这样能减少比较的次数，从而提高效率
		 */
		if($comp[$this->widthIndex] == $item[$this->widthIndex] &&
			$comp[$this->lengthIndex] == $item[$this->lengthIndex] &&
			$comp[$this->thicknessIndex] == $item[$this->thicknessIndex] &&
			$comp[$this->shipIndex] == $item[$this->shipIndex] &&
			$comp[$this->materialIndex] == $item[$this->materialIndex] &&
			$comp[$this->mcIndex] == $item[$this->mcIndex] &&
			$comp[$this->batchIndex] == $item[$this->batchIndex] &&
			$comp[$this->purchaseIndex] == $item[$this->purchaseIndex]){
				//如果特质一样，但是订单号某个不一样，那么有冲突
				if($comp[$this->orderNumberIndex] != $item[$this->orderNumberIndex] ||
				$comp[$this->orderSubitemNumberIndex] != $item[$this->orderSubitemNumberIndex]){
					//echo ('conflict<br />');
					return true;	
				}else{
					//echo ('no conflict<br />');
					return false;
				}
			}else{
				//echo ('no conflict<br />');
				return false;
			}
	}
	/**
	 * 检测这个材料代码是否已经存在于总表数据库中？
	 * 这个函数可以用来避免这样一个问题： 发车文件中的材料代码在以前上传的总表中不存在，这肯定是一个问题，
	 * 这个函数是要查找总表中这个材料代码是否已经存在。
	 *
	 * @param string $mc
	 * @return boolean or array
	 */
	private function isMaterialCodeUploaded($mc){
		$wheres = array();
		if(is_array($mc)){
			foreach($mc as $val){
				array_push($wheres, "materialCode = '$val'");
			}
			$wheres = join(", ", $wheres);
		}else{
			$wheres = "materialCode = '$mc'";
		}
		$query = "select materialCode from sss_main where $wheres";
		DB::query($query);
		if(mysqli_num_rows(DB::getResult()) > 0){
			$ret = array();
			$result = DB::getResult();
			while($row = $result->fetch_assoc()){
				array_push($ret, $row['materialCode']);
			}
		}else{
			return false;
		}
	}


	/**
	 * 从数组$mcArray中获得一个还没有上传的材料代码
	 * 使用递归方式，因为只需要找到一个，所以可以使用二分查找法。
	 *
	 * @param array $mcArray
	 * @return string
	 */
	private function getOneHaveNotUploadedMaterialCode($mcArray){
		$inString = "'".join("', '", $mcArray)."'";
		$query = "select count(distinct materialCode) from sss_main where materialCode in($inString)";
		//echo "$query".'<br />';
		$result = DB::query($query);
		$row = $result->fetch_assoc();
		//echo "find row: ".$row['count(distinct materialCode)'].'<br />';
		//echo "array row: ".count($mcArray).'<br />';
		if($row['count(distinct materialCode)'] == count($mcArray)){
			return false;
		}else{
			if(count($mcArray) == 1){
				return $mcArray[0];
			}else{
				$mcArrayArray = array_chunk($mcArray, (count($mcArray)+1)/2);
				$firstHalf = $this->getOneHaveNotUploadedMaterialCode($mcArrayArray[0]);
				if($firstHalf == false){
					$secondHalf = $this->getOneHaveNotUploadedMaterialCode($mcArrayArray[1]);
					if($secondHalf == false){
						throw new CustomException("这里肯定有个错误， 因为这些mc中肯定有未上传的，但是分开计算后没有找到");
					}else{
						return $secondHalf;
					}
				}else{
					return $firstHalf;
				}
			}
		}
	}

	/**
	 * 这个函数用上传“发车”“发船”的文件时的检查部分。
	 * 用于检查这个上传的文件中是否有总表中不存在的材料代码
	 * 如果有的话，会报告错误，而且停止上传。
	 * 对于这个函数，如果没有的话,返回null, 否则返回
	 * 包含所有材料代码的数组
	 *
	 * 后来添加$count这个参数，为的是提高效率，因为这个函数实在无法在优化，
	 * 所以只能减少他返回的个数。因此添加$count变量，一般变量为10比较好。
	 * 但现在因为时间问题，只适用二分查找法找到一个,但仍然以数组方式返回，这样能得到很好的扩展性。
	 *
	 */
	public function getHaveNotUploadedMaterialCode($count = null){

/*		echo "<pre>";
		var_dump($this->reader->sheets[0]['cells']);
		echo "</pre>";*/
		$mcArray = $this->getMaterialCodeArray();

		$mcArray = array_unique($mcArray);
/*		echo "<pre>";
		var_dump($mcArray);
		echo "</pre>";*/
		$oneHaveNot = $this->getOneHaveNotUploadedMaterialCode($mcArray);


		if($oneHaveNot !== false){
			$haveNot = array($oneHaveNot);
			return $haveNot;
		}else{
			return null;
		}
	}


	/**
	 * 检查上传的材料代码，订单号，订单子项号在数据库中是否已经存在
	 * @return array
	 */
	public function getHaveUploadedMaterialCodeAndOrders(){

		$have = array();
		$wheres = array();
		$moArray = $this->getMaterialCodeAndOrdersArray();
		$moArray = array_unique($moArray);
		if(count($moArray) == 0){
			return null;
		}
		foreach($moArray as $val){
			array_push($wheres, "(materialCode = '{$val[0]}' and orderNumber = '{$val[1]}' and orderSubitemNumber = '{$val[2]}')");
		}
		$wheres = join(" or ", $wheres);
		$query = "select materialCode, orderNumber, orderSubitemNumber 
			from sss_main 
			where $wheres
			group by materialCode, orderNumber, orderSubitemNumber";

		DB::query($query);
		if(mysqli_num_rows(DB::getResult()) > 0){
			$result = DB::getResult();
			while(($row = $result->fetch_assoc())!=null){
				array_push($have, array($row['materialCode'],$row['orderNumber'], $row['orderSubitemNumber']));
			}
		}
		if(count($have) > 0){
			return $have;
		}else{
			return null;
		}
	}
	
	
	/**
	 * 获得表格中的所有材料代码（包括加片和所有汉字）
	 * 注意：没有排除重复
	 * 作为数组返回
	 *
	 * @return array
	 */
	function getMaterialCodeAndOrdersArray(){
		$sheet = $this->reader->sheets[0]['cells'];
		//首先将所有materialCode和orderNumber,orderSubitemNumber 组成一个array
		$mcArray = array();
		$firstRow = current($sheet);

		//查找"材料代码"在哪一列,将其值放到$mcKey中
		//查找订单号订单子项号在哪一列，将其值分别放到$orderKey和$subOrderKey中
		$mcKey = null;
		$orderKey = null;
		$subOrderKey = null;
		foreach($firstRow as $key => $val){
			if($val == '材料代码'){
				$mcKey = $key;
			}else if($val == '订单号'){
				$orderKey = $key;
			}else if($val == '订单子项号'){
				$subOrderKey = $key;
			}
		}
		//如果没有材料代码或者订单号，订单子项号这一项，抛出异常
		if($mcKey == null){
			throw new LackSomeColumn("材料代码");
		}else if($orderKey == null){
			throw new LackSomeColumn("订单号");
		}else if($subOrderKey == null){
			throw new LackSomeColumn("订单子项号");
		}

		//将“材料代码，订单号，订单子项号”放到数组中
		while($row = next($sheet)){
			if(!isset($row[$mcKey])){
				continue;
			}
			$col = $row[$mcKey] ;
			$col = trim($col);
			if(!empty($col)){
				array_push($mcArray, array($row[$mcKey], isset($row[$orderKey])?$row[$orderKey]:null, isset($row[$subOrderKey])?$row[$subOrderKey]:null));
			}
		}

		return $mcArray;
	}
	
	/**
	 * 获得表格中的所有材料代码（包括加片和所有汉字）和对应材料代码的数量
	 * 注意：没有排除重复
	 * 作为数组返回
	 *
	 * @return array
	 */
	function getMaterialCodeAndCountArray(){
		$sheet = $this->reader->sheets[0]['cells'];
		//首先将所有materialCode和orderNumber,orderSubitemNumber 组成一个array
		$mcArray = array();
		$firstRow = current($sheet);

		//查找"材料代码"在哪一列,将其值放到$mcKey中
		//查找订单号订单子项号在哪一列，将其值分别放到$orderKey和$subOrderKey中
		$mcKey = null;
		$orderKey = null;
		$subOrderKey = null;
		$countKey = null;
		foreach($firstRow as $key => $val){
			if($val == '材料代码'){
				$mcKey = $key;
			}else if($val == '订单号'){
				$orderKey = $key;
			}else if($val == '订单子项号'){
				$subOrderKey = $key;
			}else if($val == '数量'){
				$countKey = $key;
			}
		}
		//如果没有材料代码或者订单号，订单子项号这一项，抛出异常
		if($mcKey == null){
			throw new LackSomeColumn("材料代码");
		}else if($orderKey == null){
			throw new LackSomeColumn("订单号");
		}else if($subOrderKey == null){
			throw new LackSomeColumn("订单子项号");
		}else if($countKey == null){
			throw new LackSomeColumn("数量");
		}

		//将“材料代码，订单号，订单子项号”放到数组中
		while($row = next($sheet)){
			if(!isset($row[$mcKey])){
				continue;
			}
			$col = $row[$mcKey] ;
			$col = trim($col);
			if(!empty($col)){
				array_push($mcArray, array($row[$mcKey], isset($row[$orderKey])?$row[$orderKey]:null, isset($row[$subOrderKey])?$row[$subOrderKey]:null, isset($row[$countKey])?$row[$countKey]:null));
			}
		}

		return $mcArray;
	}
	
	/**
	 * excelOperator
	 * 获取不可少的列的数据
	 * @return unknown_type
	 */
	public function getObligatoArray(){
		$sheet = $this->reader->sheets[0]['cells'];
		//首先将所有要比较的列组成一个array
		$obligatoArray = array();
		$firstRow = current($sheet);

		$mqKey = null;
		$changjiaKey = null;
		$chuanjiKey = null;
		$caizhiKey = null;
		$houduKey = null;
		$kuanduKey = null;
		$changduKey = null;
		$shuliangKey = null;
		
		//为保证只取到第一个值，为每个key加个flag
		$mqFlag = false;
		$changjiaFlag = false;
		$chuanjiFlag = false;
		$caizhiFlag = false;
		$houduFlag = false;
		$kuanduFlag = false;
		$changduFlag = false;
		$shuliangFlag = false;
		foreach($firstRow as $key => $val){
			if($val == '生产厂家' && $changjiaFlag == false){
				$changjiaKey = $key;
				$changjiaFlag = true;
			}else if($val == '船级' && $chuanjiFlag == false){
				$chuanjiKey = $key;
				$chuanjiFlag = true;
			}else if($val == '材质' && $caizhiFlag == false){
				$caizhiKey = $key;
				$caizhiFlag = true;
			}else if($val == '厚度' && $houduFlag == false){
				$houduKey = $key;
				$houduFlag = true;
			}else if($val == '宽度' && $kuanduFlag == false){
				$kuanduKey = $key;
				$kuanduFlag = true;
			}else if($val == '长度' && $changduFlag == false){
				$changduKey = $key;
				$changduFlag = true;
			}else if($val == '清单数量' && $shuliangFlag == false){
				$shuliangKey = $key;
				$shuliangFlag = true;
			}else if($val == 'MQ' && $mqFlag == false){
				$mqKey = $key;
				$mqFlag = true;
			}
		}
		//需要比较的列数据为空，抛出异常
		if($changjiaKey == null){
			throw new LackSomeColumn("生产厂家");
		}else if($chuanjiKey == null){
			throw new LackSomeColumn("船级");
		}else if($caizhiKey == null){
			throw new LackSomeColumn("材质");
		}else if($houduKey == null){
			throw new LackSomeColumn("厚度");
		}else if($kuanduKey == null){
			throw new LackSomeColumn("宽度");
		}else if($changduKey == null){
			throw new LackSomeColumn("长度");
		}else if($shuliangKey == null){
			throw new LackSomeColumn("清单数量");
		}else if($mqKey == null){
			throw new LackSomeColumn("MQ");
		}

		//将要比较的数据放到数组中
		while($row = next($sheet)){
			if(!isset($row[$chuanjiKey])){
				continue;
			}
			$col = $row[$chuanjiKey] ;
			$col = trim($col);
			if(!empty($col)){
				array_push($obligatoArray, array(join('_',array(isset($row[$changjiaKey])?$row[$changjiaKey]:null,$row[$chuanjiKey], isset($row[$caizhiKey])?$row[$caizhiKey]:null, 
				isset($row[$houduKey])?$row[$houduKey]:null, isset($row[$kuanduKey])?$row[$kuanduKey]:null,
				isset($row[$changduKey])?$row[$changduKey]:null)),isset($row[$mqKey])?$row[$mqKey]:null,isset($row[$shuliangKey])?$row[$shuliangKey]:null));
			}
		}

		return $obligatoArray;
	}
	
	/**
	 * excelOperator
	 * 通过$this->obligatoColumns获取excel表格中对应的数据
	 * @return array
	 */
	public function getObligatoColumn(){
		$sheet = $this->reader->sheets[0]['cells'];
		$obligatoCols = array();
		$obligatoMap = array();//列号=>列名
		$firstRow = current($sheet);
		
		//从firstRow中提取中必需的列
		foreach ($firstRow as $key => $val){
			foreach($this->obligatoColumns as $col){
				if($val == $col){
					$obligatoMap[$key] = $val;
				}
			}
		}
		
		//检查是否缺少这些列，如果缺少抛出异常
		foreach ($this->obligatoColumns as $col) {
			if(!in_array($col,$obligatoMap))
				throw new LackSomeColumn($col);
		}
		
		//将要比较的数据放到数组中
		while($row = next($sheet)){
			$tempArray = null;
			foreach ($obligatoMap as $key => $val){
				$tempArray[$val] = $row[$key];
			}
			array_push($obligatoCols,$tempArray);
		}
		return array_filter($obligatoCols);
	}
	/**
	 * excelOperator
	 * 获取全部列数据
	 * @return array
	 */
	public function getAllColumn(){
		$sheet = $this->reader->sheets[0]['cells'];
		$columnArray = array();
		$allArray = array();
		//首先将所有的列组成一个array
		$firstRow = current($sheet);
		while(($row = next($sheet))){
			for($i = 0;$i<count($firstRow);$i++){
				$columnArray[$firstRow[$i+1]] = $row[$i+1];
			}
			array_push($allArray,array_filter($columnArray));
		}
		return $allArray;
	}
	
	/**
	 * excelOperator
	 * 获取表头
	 * @return array
	 */
	public function getFirstRow(){
		return current($this->reader->sheets[0]['cells']);
	}
	
	/**
	 * excelOperator
	 * 获取需要比较的列的数据
	 * @return array
	 */
	public function getComparedArray(){
		$sheet = $this->reader->sheets[0]['cells'];
		//首先将所有要比较的列组成一个array
		$comparedArray = array();
		$firstRow = current($sheet);

		$changjiaKey = null;
		$chuanjiKey = null;
		$caizhiKey = null;
		$houduKey = null;
		$kuanduKey = null;
		$changduKey = null;
		foreach($firstRow as $key => $val){
			if($val == '生产厂家'){
				$changjiaKey = $key;
			}else if($val == '船级'){
				$chuanjiKey = $key;
			}else if($val == '材质'){
				$caizhiKey = $key;
			}else if($val == '厚度'){
				$houduKey = $key;
			}else if($val == '宽度'){
				$kuanduKey = $key;
			}else if($val == '长度'){
				$changduKey = $key;
			}
		}
		//需要比较的列数据为空，抛出异常
		if($changjiaKey == null){
			throw new LackSomeColumn("厂家");
		}else if($chuanjiKey == null){
			throw new LackSomeColumn("船级");
		}else if($caizhiKey == null){
			throw new LackSomeColumn("材质");
		}else if($houduKey == null){
			throw new LackSomeColumn("厚度");
		}else if($kuanduKey == null){
			throw new LackSomeColumn("宽度");
		}else if($changduKey == null){
			throw new LackSomeColumn("长度");
		}

		//将要比较的数据放到数组中
		while($row = next($sheet)){
			if(!isset($row[$chuanjiKey])){
				continue;
			}
			$col = $row[$chuanjiKey] ;
			$col = trim($col);
			if(!empty($col)){
				array_push($comparedArray, join('_',array(isset($row[$changjiaKey])?$row[$changjiaKey]:null,$row[$chuanjiKey], isset($row[$caizhiKey])?$row[$caizhiKey]:null, 
				isset($row[$houduKey])?$row[$houduKey]:null, isset($row[$kuanduKey])?$row[$kuanduKey]:null,
				isset($row[$changduKey])?$row[$changduKey]:null)));
			}
		}

		return $comparedArray;
	}
	
	public function getHaveUploadedMaterialCode(){

		$have = array();
		$wheres = array();
		$mcArray = $this->getMaterialCodeArray();
		$mcArray = array_unique($mcArray);
		if(count($mcArray) == 0){
			return null;
		}
		foreach($mcArray as $val){
			array_push($wheres, "materialCode = '$val'");
		}
		$wheres = join(" or ", $wheres);
		$query = "select distinct materialCode from sss_main where $wheres";

		DB::query($query);
		if(mysqli_num_rows(DB::getResult()) > 0){
			$result = DB::getResult();
			while($row = $result->fetch_assoc()){
				array_push($have, $row['materialCode']);
			}
		}
		if(count($have) > 0){
			return $have;
		}else{
			return null;
		}
	}
	
	/**
	 * 获得表格中的所有材料代码（这里的材料代码不包括加片）
	 * 注意：没有排除重复
	 * 作为数组返回
	 *
	 * @return array
	 */
	function getMaterialCodeArray(){
		$sheet = $this->reader->sheets[0]['cells'];
		//首先将所有materialCode组成一个array
		$mcArray = array();
		$firstRow = current($sheet);

		///dprint($sheet);
		//dprint($firstRow);

		//查找"材料代码"在哪一列,将其值放到$mcKey中
		$mcKey = null;
		foreach($firstRow as $key => $val){
			if($val == '材料代码'){
				$mcKey = $key;
				break;
			}
		}
		//如果没有材料代码这一项，抛出异常
		if($mcKey == null){
			throw new LackSomeColumn("材料代码");
		}

		//将“材料代码”放到数组中
		while($row = next($sheet)){
			if(!isset($row[$mcKey])){
				continue;
			}
			$col = $row[$mcKey] ;
			$col = trim($col);
			if($col != '加片' && !empty($col)){
				array_push($mcArray, $col);
			}
		}

		return $mcArray;
	}
	
	/**
	 * 获得汉字在表格数据的数组中的index，
	 * 返回的是一个数组
	 *
	 */
	function getChineseCharsIndex(){
		
		$chars = Accountant::getChineseCharsArray();
		$index = array();
		
		$sheet = $this->reader->sheets[0]['cells'];
		//首先将所有materialCode组成一个array
		$mcArray = array();
		$firstRow = current($sheet);

		///dprint($sheet);
		//dprint($firstRow);

		//查找"材料代码"在哪一列,将其值放到$mcKey中
		$mcKey = null;
		foreach($firstRow as $key => $val){
			if($val == '材料代码'){
				$mcKey = $key;
				break;
			}
		}
		//如果没有材料代码这一项，抛出异常
		if($mcKey == null){
			throw new LackSomeColumn("材料代码");
		}

		//将“材料代码”放到数组中
		$i = 0;
		foreach($sheet as $key => $row){
			if($i = 0){
				$i++;
			}else{
				if(in_array($col, $chars)){
					array_push($index, $key);
				}
			}
		}
		return $index;
	}
	
	
	/**
	 * 查看这页中是否有相同的材料代码,有返回所有相同的材料代码,
	 * 否则返回false
	 *
	 * @return array or boolean
	 */
	function haveSameMaterialCode(){
		$mcArray = $this->getMaterialCodeArray();

	/*	echo "<pre>";
		var_dump($mcArray);
		die();*/

		//比较$mcArray中的材料代码是否相同
/*		$sameMcs = array();
		for($i = 0; $i < count($mcArray); $i++){
			for($j = $i+1; $j < count($mcArray); $j++){
				if($mcArray[$i] == $mcArray[$j])
					array_push($sameMcs, $mcArray[$i]);
			}
		}*/
		$sameMcs = array();
		$mcsIndex = array();
		foreach($mcArray as $key => $val){
			if(!isset($mcsIndex[$val])){
				$mcsIndex[$val] = false;
			}else{
				$mcsIndex[$val] = true;
			}
		}

		foreach($mcsIndex as $key => $val){
			$val == true?array_push($sameMcs, $key):null;
		}

		if(empty($sameMcs)){
			return false;
		}else{
			return $sameMcs;
		}
	}

	/**
	 * 查看这页中是否有相同的材料代码+订单号+订单子项号,有返回所有信息，放在一个数组中，各个元素结构是这样的：“材料代码, 订单号, 订单子项号”,
	 * 否则返回false
	 *
	 * @return array or boolean
	 */
	function haveSameMaterialCodeAndOrders(){
		$moArray = $this->getMaterialCodeAndOrdersArray();
		
		$sameMos = array();
		$mosIndex = array();
		foreach($moArray as $key => $val){
			$strVal = join(', ',$val);
			if(!isset($mosIndex[$strVal])){
				$mosIndex[$strVal] = false;
			}else{
				$mosIndex[$strVal] = true;
			}
		}

		foreach($mosIndex as $key => $val){
			$val == true?array_push($sameMos, $key):null;
		}

		if(empty($sameMos)){
			return false;
		}else{
			return $sameMos;
		}
	}
	
	
	/**
	 * 将电子表格中的信息读取数据库，读入之前必须先设置
	 * $tableName 和 $tableColumns
	 *
	 * @throws DBConnectException DBQueryException CustomException
	 */
	public function readIntoDB(){


		date_default_timezone_set('Asia/Shanghai');
		$currentTime = date('Y/m/d H:i:s');

		$phase = '';
		if(isset($_POST['phase'])){
			$i = intval($_POST['phase']);
			$phaseArray = array('入库','出库','销售');
			$phase = $phaseArray[$i];
		}

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
			if($formatedSheetRow == null or empty($formatedSheetRow['materialCode'])){
				continue;
			}

			//下面对数据进行过滤
			foreach($formatedSheetRow as $key => $val){
				$formatedSheetRow[$key] = Filter::forDBInsertion($val);
			}

			$value = "";
			if(!empty($phase)){
				$value = ", ('".join("', '", $formatedSheetRow)."', '$currentTime', '{$this->filename}', '$phase')\n";
			}else{
				$value = ", ('".join("', '", $formatedSheetRow)."', '$currentTime', '{$this->filename}')\n";
			}

			$valuesStr .= $value;
		}

		$valuesStr = substr($valuesStr, 1);
		$cols = "";
		if(!empty($phase)){
			$cols = " (`".join("`, `", array_unique(array_values($this->tableColumns)))."`, `uploadTime`, `filename`, `phase`) ";
		}else{
			$cols = " (`".join("`, `", array_unique(array_values($this->tableColumns)))."`, `uploadTime`, `filename`) ";
		}

		$query = "insert into {$this->tableName}$cols values $valuesStr;";

			DB::query($query);
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
						if(empty($sheetRow[$i+1])){
							return null;
						}else if(iconv_strlen($sheetRow[$i + 1], 'utf-8') > 32){
							throw new DataTooLongForDBInsertion($sheetRow[$i+1], 32);
						}
					}else if($key == 'facheDate' || $key == 'fachuanDate'){
						//需要修正一下时间格式的不统一
						$this->correctDateFormate($sheetRow[$i+1]);
						 if(Verifier::getInstance()->isDATE($sheetRow[$i+1])){
						 	$sheetRow[$i+1] = date('Y/m/d', strtotime($sheetRow[$i+1]));

						 }else{
						 	throw new InvalidDateFormat($sheetRow[$i+1]);
						 }
					}
					$ret[$key] = $sheetRow[$i+1];
				}
			}
		}

	/*	echo('<pre>');
		var_dump($sheetRow);
		echo '<br /> count is :'.$count;
		die();*/
		return $ret;
	}


	/**
	 * 修正时间格式的不同统一性
	 * 因为Excel表中为d/m/Y
	 * 而strtotime时以m/d/Y这样解析
	 *
	 * @param string $dateVal
	 * @return string
	 * @throws  InvalidDateFormat
	 */
	private function correctDateFormate(&$dateVal){

		$dateArray = explode('/', $dateVal);
		if(count($dateArray) !== 3){
			throw new InvalidDateFormat($dateVal);
		}
		$temp = $dateArray[0];
		$dateArray[0] = $dateArray[1];
		$dateArray[1] = $temp;
		$dateVal = join('/', $dateArray);

		return $dateVal;
	}
	
	
	public function getMcAndOrdersConflictedWithDb(){
		$conflictArrays = array();
		$moArray = $this->getMaterialCodeAndOrdersArray();
		foreach($moArray as $mo){
			$query = "select * from sss_main
				where not exists(select * from sss_main where materialCode = '{$mo[0]}' and orderNumber = '{$mo[1]}' and orderSubitemNumber = '{$mo[2]}')
					and materialCode = '{$mo[0]}' and (orderNumber != '{$mo[1]}' or orderSubitemNumber != '$mo[2]')
				limit 1";
			DB::query($query);
			if(DB::num_rows() > 0){
				$row = DB::getResult()->fetch_assoc();
				array_push(
					$conflictArrays, 
					array( join(', ', $mo), 
							$row['materialCode'].", ".$row['orderNumber'].", ".$row['orderSubitemNumber'])
					);
			}
		}
		
		if(empty($conflictArrays)){
			return null;
		}else{
			return $conflictArrays;
		}
	}
	
	/**
	 * 找出在总表中不存在的材料代码，订单号，订单子项号
	 *
	 * @return unknown
	 */
	public function getMcAndOrdersNotInDb(){
		$notInArrays = array();
		$moArray = $this->getMaterialCodeAndOrdersArray();
		foreach($moArray as $mo){
			$query = "select * from sss_main
				where materialCode = '{$mo[0]}' and orderNumber = '{$mo[1]}' and orderSubitemNumber = '$mo[2]'";
			DB::query($query);
			if(DB::num_rows() == 0){
				$row = DB::getResult()->fetch_assoc();
				array_push($notInArrays, join(', ', $mo));
			}
		}
		
		if(empty($notInArrays)){
			return null;
		}else{
			return $notInArrays;
		}
	}
	
	/**
	 * 找出表格中数量大于总表数量总和的材料代码，订单号，订单子项号
	 *
	 * @return unknown
	 */
	public function getMcAndOrdersRukuCountExceed(){
		$exceedArrays = array();
		$moArray = $this->getMaterialCodeAndCountArray();
		foreach($moArray as $mo){
			$query = "select * from sss_main
				where materialCode = '{$mo[0]}' and orderNumber = '{$mo[1]}' and orderSubitemNumber = '$mo[2]'
				group by materialCode, orderNumber, orderSubitemNumber
				having sum(count) < '$mo[3]'";
			DB::query($query);
			if(DB::num_rows() > 0){
				$row = DB::getResult()->fetch_assoc();
				array_push($exceedArrays, join(', ', $mo));
			}
		}
		if(empty($exceedArrays)){
			return null;
		}else{
			return $exceedArrays;
		}
	}
	
	/**
	 * 找出表格中数量大于库中数量总和的材料代码，订单号，订单子项号
	 *
	 * @return unknown
	 */
	public function getMcAndOrdersChukuCountExceed(){
		$exceedArrays = array();
		$moArray = $this->getMaterialCodeAndCountArray();
		foreach($moArray as $mo){
			$query = "select * from sss_fache
				where materialCode = '{$mo[0]}' and orderNumber = '{$mo[1]}' and orderSubitemNumber = '$mo[2]' and phase = '入库'
				group by materialCode, orderNumber, orderSubitemNumber
				having sum(count) < '$mo[3]'";
			DB::query($query);
			if(DB::num_rows() > 0){
				$row = DB::getResult()->fetch_assoc();
				array_push($exceedArrays, join(', ', $mo));
			}
		}
		if(empty($exceedArrays)){
			return null;
		}else{
			return $exceedArrays;
		}
	}
	
	/**
	 * 从上传文件中获取有冲突的材料代码，订单号，订单子项号
	 * 材料代码一样，但是订单号，订单子项号不一样
	 * @return unkownType
	 */
	public function getConflictMcsInFile(){
		$moArray = $this->getMaterialCodeAndOrdersArray();
		
		$conflictMcs = array();
		$mosIndex = array();
		foreach($moArray as $val){
			if(!isset($mosIndex[$val[0]])){
				$mosIndex[$val[0]] = $val[1].', '.$val[2];
			}else if($mosIndex[$val[0]] != $val[1].', '.$val[2] ){
				array_push($conflictMcs, $val[0]);
			}
		}

		if(empty($conflictMcs)){
			return false;
		}else{
			//$conflictMcs = array_unique($conflictMcs);
			return array_unique($conflictMcs);
		}
	}
}

?>