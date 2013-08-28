<?php
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SimpleTableExporter.class.php');
require_once('../includes/functions.inc.php');

class Summary{
	protected $filename;
	protected $exp;
	protected $smarty;
	
	protected $shipNumber;
	
	//以下几个属性用来接收POST方式传递来的数据
	protected $posts = array();
	protected $postMain;
	protected $postRuku;
	protected $postChuku;
	protected $postSale;
	protected $postUnRuku;
	protected $postKuzhong;
	
	//sss_summary_element表数据存放的属性
	protected $manufatories = array();//生产厂家
	protected $storePlaces = array();//库存地
	protected $destinations = array();//目的地

	protected $mainManufactories = array ();//存储用户选择的总表的生产厂家的数组
	protected $rukuManufactories = array ();//入库生产厂家
	protected $rukuStorePlaces = array();//入库库存地
	protected $chukuManufactories = array();//出库生产厂家
	protected $chukuStorePlaces = array();//出库库存地
	protected $chukuDestinations = array();//出库库存地
	protected $chukuMethods = array();//出库方式
	protected $saleManufactories = array();//发货生产厂家
	protected $saleDestinations = array();//发货目的地
	protected $kuzhongManufactories = array();//库中生产厂家
	protected $kuzhongStorePlaces = array();//库中库存地
	protected $unRukuManufactories = array();//未动生产厂家
	
	protected $i = 2;//当前执行到的行号
	protected $column = 2;////当前执行到的列号
	
	//需要写入电子表格中的属性
	protected $sum;	//总表总计
	protected $manufactory_columns;//该数组包含了各个生产厂家的数量和重量的信息
	protected $ruku;//入库总计
	protected $rukuManufactory_columns;//该数组包含了各个生产厂家的数量和重量的信息
	protected $rukuStorePlace_columns;//该数组包含了各个库存地的数量和重量的信息
	protected $chuku;//入库总计
	protected $chukuManufactory_columns;//该数组包含了各个生产厂家的数量和重量的信息
	protected $chukuStorePlace_columns;//该数组包含了各个库存地的数量和重量的信息
	protected $chukuDestination_columns;//出库目的地
	protected $chukuMethod_columns;//出库方式
	protected $sale;//发货总计
	protected $saleManufactory_columns;//发货生产厂家
	protected $saleDestination_columns;//发货目的地
	protected $kuzhong;	//库中总计
	protected $kuzhongStorePlace_columns;//库中库存地
	protected $kuzhongManufactory_columns;//库中生产厂家
	protected $unRuku;//欠交总计
	protected $unRukuManufactory_columns;//欠交生产厂家
	
	public function __construct($posts){
		$this->filename = '汇总'.'_'.date('YmdHis').'.xls';
		$this->exp = new SimpleTableExporter($this->filename);
		$this->exp->createNewSheet();
		$this->smarty = SmartyManager::getSmarty();
		$this->posts = $posts;
		
		//以下代码用来查找sss_summary_element表中的汇总因素
		$query = "select manufactory, storePlace, destination from sss_summary_element";
		DB::query($query);
		$result = DB::getResult();
		while(($row=$result->fetch_assoc())!=null){
		if($row['manufactory'])//不为NULL时方可push
			array_push($this->manufatories,$row['manufactory']);
		if($row['storePlace'])
			array_push($this->storePlaces,$row['storePlace']);
		if($row['destination'])
			array_push($this->destinations,$row['destination']);
		}
		
		//以下代码是将POST传递来的信息分组，这样如果以后添加新的汇总因素方便扩展
		foreach ( $this->posts as $key => $val ) {
			if (strstr ( $key, "main_manu" )) {//注意strstr（）和stristr函数的俄区别，strstr:Case sensitive
				array_push ( $this->mainManufactories, $val );
			} else if (strstr ( $key, "ruku_manu" )) {
				array_push ( $this->rukuManufactories, $val );
			} else if (strstr ( $key, "ruku_store" )) {
				array_push ( $this->rukuStorePlaces, $val );
			} else if (strstr ( $key, "chuku_manu" )) {
				array_push ( $this->chukuManufactories, $val );
			} else if (strstr ( $key, "chuku_store" )) {
				array_push ( $this->chukuStorePlaces, $val );
			} else if (strstr ( $key, "chuku_dest" )) {
				array_push ( $this->chukuDestinations, $val );
			} else if (strstr ( $key, "chuku_meth" )) {
				array_push ( $this->chukuMethods, $val );
			} else if (strstr ( $key, "sale_manu" )) {
				array_push ( $this->saleManufactories, $val );
			} else if (strstr ( $key, "sale_dest" )) {
				array_push ( $this->saleDestinations, $val );
			} else if (strstr ( $key, "kuzhong_manu" )) {
				array_push ( $this->kuzhongManufactories, $val );
			} else if (strstr ( $key, "kuzhong_store" )) {
				array_push ( $this->kuzhongStorePlaces, $val );
			} else if (strstr ( $key, "unRuku_manu" )) {
				array_push ( $this->unRukuManufactories, $val );
			}
		}
		$this->postMain =$this->posts['main'];
		$this->postRuku =$this->posts['ruku'];
		$this->postChuku =$this->posts['chuku'];
		$this->postSale =$this->posts['sale'];
		$this->postUnRuku =$this->posts['unRuku'];
		$this->postKuzhong =$this->posts['kuzhong'];
		
	}

	/**
	 * 添加判断是否有多余的生产厂家，库存地，目的地
	 * 如果多余则按如下格式提示错误信息，并结束程序
	 * 材料代码，汇总纵向因素，汇总横向因素，汇总错误因素，订单号，订单子项号
	 * @param $query 查询sql语句
	 * @param $condition 查询条件，如：发货批次，分段号，年份/月份
	 * @return unknown_type
	 */
	public function checkRedundantInfomation($query,$condition="空"){
		DB::query($query);
		$result = DB::getResult();
		try{
			while(($row=$result->fetch_assoc())!=null){
				if(!in_array($row['manufactory'],$this->manufatories)){
					$errMsg = "以下的材料代码，订单号，订单子项号出现了特殊的生产厂家,信息如下：<br>
					".$row['materialCode']."|".$this->shipNumber."|".$condition."
					|生产厂家|".$row['manufactory']."|".$row['orderNumber']."|".$row['orderSubitemNumber'];
					throw new CustomException($errMsg);
				}
				if(!in_array($row['storePlace'],$this->storePlaces)){
					$errMsg = "以下的材料代码，订单号，订单子项号出现了特殊的库存地,信息如下：<br>
					".$row['materialCode']."|".$this->shipNumber."|".$condition."
					|库存地|".$row['storePlace']."|".$row['orderNumber']."|".$row['orderSubitemNumber'];
					throw new CustomException($errMsg);
				}
				if(!in_array($row['destination'],$this->destinations)){
					$errMsg = "以下的材料代码，订单号，订单子项号出现了特殊的目的地,信息如下：<br>
					".$row['materialCode']."|".$this->shipNumber."|".$condition."
					|目的地|".$row['destination']."|".$row['orderNumber']."|".$row['orderSubitemNumber'];
					throw new CustomException($errMsg);
				}
			}
		}catch (Exception $e){
			$this->smarty->assign('errMsg', $e);
			$this->smarty->display('error.html');
			exit();
		}
	}
	
	/**
	 * 添加订货数量
	 * @param $sumQuery	订货数量总计查询的sql语句
	 * @param $manufactoryQuery 订货数量按生产厂家分类的sql语句
	 * @param $condition 第二个限定条件
	 * @return $this->column 执行结束之后的行号
	 */
	public function insertMainData($sumQuery,$manufactoryQuery){
		//订货数量总计
		$this->sum = array();
		if($this->postMain){//用户选择需要导出订货数量的信息
			DB::query($sumQuery);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$oneSum = array();
				if($row['sumCount']){
					array_push($oneSum,$row['sumCount']);
					array_push($oneSum,$row['weight']);
				}else{//如果该单元格没有数据，应不上0，使表格插入数据时能够对齐
					array_push($oneSum,0);
					array_push($oneSum,0);
				}
				array_push($this->sum,$oneSum);
			}else
				array_push($this->sum,array(0,0));
		}
		
		//订货数量-产家
		$this->manufactory_columns = array();
		foreach($this->mainManufactories as $manufactory){
			if(in_array($manufactory,$this->manufatories)){//如果用户选择的生产厂家在sss_summary_element中出现
				//$manufactoryQuery需要一定的处理,将?用$manufactory替换
				$manufactorySQL = str_replace("?",$manufactory,$manufactoryQuery);
				DB::query($manufactorySQL);
				$result = DB::getResult();
				if(($row=$result->fetch_assoc())!=null){
					$this->manufactory_columns[$manufactory][0] = $manufactory;//如果用array_push的话不仅影响效率，而且不方便索引
					if($row['sumCount']){
						$this->manufactory_columns[$manufactory][1] = $row['sumCount'];
						$this->manufactory_columns[$manufactory][2] = $row['weight'];
					}else{
						$this->manufactory_columns[$manufactory][1] = 0;
						$this->manufactory_columns[$manufactory][2] = 0;
					}
				}else
					$this->manufactory_columns[$manufactory] = array($manufactory,0,0);
			}
		}
		$this->column = $this->exp->insertColumn($this->i,$this->column,"订货数量",$this->sum,$this->manufactory_columns);//插入“订货数量”信息
		return $this->column;
	}
	
	/**
	 * 添加入库信息
	 * @param $sumQuery 入库总计查询sql语句
	 * @param $manufactoryQuery 入库生产厂家查询sql语句
	 * @param $storePlaceQuery 入库库存地查询sql语句
	 * @param $condition 限制条件
	 * @return $this->column 执行结束之后的行号
	 */
	public function insertRukuData($sumQuery, $manufactoryQuery, $storePlaceQuery){
		$this->ruku = array();	
		if($this->postRuku){//用户选择需要导出入库的信息
			//发货批次-入库-总计
			//按船号和发货批次分组计数
			DB::query($sumQuery);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$rukuColumn = array();
				if($row['sumCount']){
					array_push($rukuColumn,$row['sumCount']);
					array_push($rukuColumn,$row['weight']);
				}else{
					array_push($rukuColumn,0);
					array_push($rukuColumn,0);
				}
				array_push($this->ruku,$rukuColumn);
			}else
				array_push($this->ruku,array(0,0));
		}
	
		//入库-生产厂家
		$this->rukuManufactory_columns = array();//该数组包含了各个生产厂家的数量和重量的信息
		foreach($this->rukuManufactories as $rukuManufactory){
			if(in_array($rukuManufactory,$this->manufatories)){//如果用户选择的生产厂家在sss_summary_element中出现
				//以下代码的"B4","25","32"对应的是订单号中倒数6，7两位数据
				$rukuManufactoryCode = "";
				if($rukuManufactory == "鲅鱼圈厚板")
					$rukuManufactoryCode = "B4";
				else if($rukuManufactory == "鞍钢中板")
					$rukuManufactoryCode = "25";
				else if($rukuManufactory == "鞍钢厚板")
					$rukuManufactoryCode = "32";
				$manufactorySQL = str_replace("?",$rukuManufactoryCode,$manufactoryQuery);
				DB::query($manufactorySQL);
				$result = DB::getResult();
				if(($row=$result->fetch_assoc())!=null){
					$this->rukuManufactory_columns[$rukuManufactory][0] = $rukuManufactory;
					if($row['sumCount']){
						$this->rukuManufactory_columns[$rukuManufactory][1] = $row['sumCount'];
						$this->rukuManufactory_columns[$rukuManufactory][2] = $row['weight'];
					}else{
						$this->rukuManufactory_columns[$rukuManufactory][1] = 0;
						$this->rukuManufactory_columns[$rukuManufactory][2] = 0;
					}
				}else
					$this->rukuManufactory_columns[$rukuManufactory] = array($rukuManufactory,0,0);
			}
		}
		
		//入库-库存地
		$this->rukuStorePlace_columns = array();//该数组包含了各个库存地的数量和重量的信息
		foreach($this->rukuStorePlaces as $rukuStorePlace){
			if(in_array($rukuStorePlace,$this->storePlaces)){//如果用户选择的生产厂家在sss_summary_element中出现
				$storePlaceSQL = str_replace("?",$rukuStorePlace,$storePlaceQuery);
				DB::query($storePlaceSQL);
				$result = DB::getResult();
				if(($row=$result->fetch_assoc())!=null){
					$this->rukuStorePlace_columns[$rukuStorePlace][0] = $rukuStorePlace;
					if($row['sumCount']){
						$this->rukuStorePlace_columns[$rukuStorePlace][1] = $row['sumCount'];
						$this->rukuStorePlace_columns[$rukuStorePlace][2] = $row['weight'];
					}else{
						$this->rukuStorePlace_columns[$rukuStorePlace][1] = 0;
						$this->rukuStorePlace_columns[$rukuStorePlace][2] = 0;
					}
				}else
					$this->rukuStorePlace_columns[$rukuStorePlace] = array($rukuStorePlace,0,0);
			}
		}
		
		$this->column = $this->exp->insertColumn($this->i,$this->column,"入库",$this->ruku,$this->rukuManufactory_columns,$this->rukuStorePlace_columns);//插入“入库”信息
		return $this->column;
	}
	
	/**
	 * 添加出库信息
	 * @param $sumQuery 出库总计查询sql语句
	 * @param $manufactoryQuery 出库生产厂家查询sql语句
	 * @param $storePlaceQuery 出库库存地查询sql语句
	 * @param $destinationQuery 出库目的地查询sql语句
	 * @param $methodQuery 出库方式查询sql语句
	 * @param $condition 限制条件
	 * @return $this->column 执行结束后的列号
	 */
	public function insertChukuData($sumQuery, $manufactoryQuery, $storePlaceQuery, $destinationQuery, $methodQuery){
		//出库-总计
		$this->chuku = array();
		if($this->postChuku){
			DB::query($sumQuery);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$oneChuku = array();
				if($row['sumCount']){
					array_push($oneChuku,$row['sumCount']);
					array_push($oneChuku,$row['weight']);
				}else{
					array_push($oneChuku,0);
					array_push($oneChuku,0);
				}
				array_push($this->chuku,$oneChuku);
			}else
				array_push($this->chuku,array(0,0));
		}
		
		//出库-生产厂家
		$this->chukuManufactory_columns = array();//该数组包含了各个生产厂家的数量和重量的信息
		foreach($this->chukuManufactories as $chukuManufactory){
			if(in_array($chukuManufactory,$this->manufatories)){//如果用户选择的生产厂家在sss_summary_element中出现
				//以下代码的"B4","25","32"对应的是订单号中倒数6，7两位数据
				$chukuManufactoryCode = "";
				if($chukuManufactory == "鲅鱼圈厚板")
					$chukuManufactoryCode = "B4";
				else if($chukuManufactory == "鞍钢中板")
					$chukuManufactoryCode = "25";
				else if($chukuManufactory == "鞍钢厚板")
					$chukuManufactoryCode = "32";
				$manufactorySQL = str_replace("?",$chukuManufactoryCode,$manufactoryQuery);
				DB::query($manufactorySQL);
				$result = DB::getResult();
				if(($row=$result->fetch_assoc())!=null){
					$this->chukuManufactory_columns[$chukuManufactory][0] = $chukuManufactory;
					if($row['sumCount']){
						$this->chukuManufactory_columns[$chukuManufactory][1] = $row['sumCount'];
						$this->chukuManufactory_columns[$chukuManufactory][2] = $row['weight'];
					}else{
						$this->chukuManufactory_columns[$chukuManufactory][1] = 0;
						$this->chukuManufactory_columns[$chukuManufactory][2] = 0;
					}
				}else
					$this->chukuManufactory_columns[$chukuManufactory] = array($chukuManufactory,0,0);
			}
		}
			
		//出库-库存地
		$this->chukuStorePlace_columns = array();
		foreach($this->chukuStorePlaces as $chukuStorePlace){
			$storePlaceSQL = str_replace("?",$chukuStorePlace,$storePlaceQuery);
			DB::query($storePlaceSQL);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$this->chukuStorePlace_columns[$chukuStorePlace][0] = $chukuStorePlace;
					if($row['sumCount']){
						$this->chukuStorePlace_columns[$chukuStorePlace][1] = $row['sumCount'];
						$this->chukuStorePlace_columns[$chukuStorePlace][2] = $row['weight'];
					}else{
						$this->chukuStorePlace_columns[$chukuStorePlace][1] = 0;
						$this->chukuStorePlace_columns[$chukuStorePlace][2] = 0;
					}
				}else
					$this->chukuStorePlace_columns[$chukuStorePlace] = array($chukuStorePlace,0,0);
		}
			
		//出库-目的地
		$this->chukuDestination_columns = array();
		foreach($this->chukuDestinations as $chukuDestination){
			$destinationSQL = str_replace("?",$chukuDestination,$destinationQuery);
			DB::query($destinationSQL);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$this->chukuDestination_columns[$chukuDestination][0] = $chukuDestination;
					if($row['sumCount']){
						$this->chukuDestination_columns[$chukuDestination][1] = $row['sumCount'];
						$this->chukuDestination_columns[$chukuDestination][2] = $row['weight'];
					}else{
						$this->chukuDestination_columns[$chukuDestination][1] = 0;
						$this->chukuDestination_columns[$chukuDestination][2] = 0;
					}
				}else
					$this->chukuDestination_columns[$chukuDestination] = array($chukuDestination,0,0);
		}
		
		//出库-出库方式
		$this->chukuMethod_columns = array();
		foreach($this->chukuMethods as $chukuMethod){
			$table = "sss_fachuan where";
			if($chukuMethod!="发船"){
				$phase = "出库";
				$table = "sss_fache where phase = '$phase' and";
			}
			$methodSQL = str_replace("?",$table,$methodQuery);
			DB::query($methodSQL);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$this->chukuMethod_columns[$chukuMethod][0] = $chukuMethod;
					if($row['sumCount']){
						$this->chukuMethod_columns[$chukuMethod][1] = $row['sumCount'];
						$this->chukuMethod_columns[$chukuMethod][2] = $row['weight'];
					}else{
						$this->chukuMethod_columns[$chukuMethod][1] = 0;
						$this->chukuMethod_columns[$chukuMethod][2] = 0;
					}
				}else
					$this->chukuMethod_columns[$chukuMethod] = array($chukuMethod,0,0);
		}
//		print_r($this->column);
//		echo "<br>";
//		print_r($this->chuku);
//		echo "<br>";
//		print_r($this->chukuManufactory_columns);
//		echo "<br>";
//		print_r($this->chukuStorePlace_columns);
//		echo "<br>";
//		print_r($this->chukuDestination_columns);
//		echo "<br>";
//		print_r($this->chukuMethod_columns);
//		echo "<hr>";
		//插入“出库”信息
		$this->column = $this->exp->insertColumn($this->i,$this->column,"出库",$this->chuku,$this->chukuManufactory_columns,$this->chukuStorePlace_columns,$this->chukuDestination_columns,$this->chukuMethod_columns);
		return $this->column;
	}
	
	/**
	 * 添加销售信息
	 * @param $sumQuery
	 * @param $manufactoryQuery
	 * @param $destinationQuery
	 * @return $this->column
	 */
	public function insertSaleData($sumQuery, $manufactoryQuery, $destinationQuery){
		$this->sale = array();	
		if($this->postSale){//用户选择需要导出销售库的信息
			//发货批次-销售库-总计
			//按船号和发货批次分组计数
			DB::query($sumQuery);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$saleColumn = array();
				if($row['sumCount']){
					array_push($saleColumn,$row['sumCount']);
					array_push($saleColumn,$row['weight']);
				}else{
					array_push($saleColumn,0);
					array_push($saleColumn,0);
				}
				array_push($this->sale,$saleColumn);
			}else
				array_push($this->sale,array(0,0));
		}
	
		//销售-生产厂家
		$this->saleManufactory_columns = array();//该数组包含了各个生产厂家的数量和重量的信息
		foreach($this->saleManufactories as $saleManufactory){
			if(in_array($saleManufactory,$this->manufatories)){//如果用户选择的生产厂家在sss_summary_element中出现
				//以下代码的"B4","25","32"对应的是订单号中倒数6，7两位数据
				$saleManufactoryCode = "";
				if($saleManufactory == "鲅鱼圈厚板")
					$saleManufactoryCode = "B4";
				else if($saleManufactory == "鞍钢中板")
					$saleManufactoryCode = "25";
				else if($saleManufactory == "鞍钢厚板")
					$saleManufactoryCode = "32";
				$manufactorySQL = str_replace("?",$saleManufactoryCode,$manufactoryQuery);
				DB::query($manufactorySQL);
				$result = DB::getResult();
				if(($row=$result->fetch_assoc())!=null){
					$this->saleManufactory_columns[$saleManufactory][0] = $saleManufactory;
					if($row['sumCount']){
						$this->saleManufactory_columns[$saleManufactory][1] = $row['sumCount'];
						$this->saleManufactory_columns[$saleManufactory][2] = $row['weight'];
					}else{
						$this->saleManufactory_columns[$saleManufactory][1] = 0;
						$this->saleManufactory_columns[$saleManufactory][2] = 0;
					}
				}else
					$this->saleManufactory_columns[$saleManufactory] = array($saleManufactory,0,0);
			}
		}
		
		//销售-目的地
		$this->saleDestination_columns = array();//该数组包含了各个库存地的数量和重量的信息
		foreach($this->saleDestinations as $saleDestination){
			if(in_array($saleDestination,$this->destinations)){//如果用户选择的生产厂家在sss_summary_element中出现
				$destinationSQL = str_replace("?",$saleDestination,$destinationQuery);
				DB::query($destinationSQL);
				$result = DB::getResult();
				if(($row=$result->fetch_assoc())!=null){
					$this->saleDestination_columns[$saleDestination][0] = $saleDestination;
					if($row['sumCount']){
						$this->saleDestination_columns[$saleDestination][1] = $row['sumCount'];
						$this->saleDestination_columns[$saleDestination][2] = $row['weight'];
					}else{
						$this->saleDestination_columns[$saleDestination][1] = 0;
						$this->saleDestination_columns[$saleDestination][2] = 0;
					}
				}else
					$this->saleDestination_columns[$saleDestination] = array($saleDestination,0,0);
			}
		}
		
		$this->column = $this->exp->insertColumn($this->i,$this->column,"销售",$this->sale,$this->saleManufactory_columns,$this->saleDestination_columns);//插入“销售”信息
		return $this->column;
	}
	
	/**
	 * 添加库中数据
	 * 这里的库中数据不是直接从数据库中获取
	 * 而是通过前面已经得到的入库和发货的部分数据获取
	 * @return $this->column 执行结束之后的列号
	 */
	public function insertKuzhongData(){
		$this->kuzhong = array();	//库中总计
		if($this->postKuzhong){//库中=入库-发车-发船
			$kuzhongColumn = array();
			//库中的数量 = 入库的数量 - 出库的数量；
			$kuzhongColumn[0] = $this->ruku[0][0] - $this->chuku[0][0];
			//库中的重量 = 入库的重量 - 出库的重量；
			$kuzhongColumn[1] = $this->ruku[0][1] - $this->chuku[0][1];
			array_push($this->kuzhong,$kuzhongColumn);
		}
		
		//库中各个库存地的数据
		$this->kuzhongStorePlace_columns = array();
		foreach($this->kuzhongStorePlaces as $kuzhongStorePlace){
			$this->kuzhongStorePlace_columns[$kuzhongStorePlace][0] = $kuzhongStorePlace;
			//库中某个库存地数量的计算，$chukuStorePlace_columns[$kuzhongStorePlace][1]包含了发车和发船出库中该库存地的数量和
			$this->kuzhongStorePlace_columns[$kuzhongStorePlace][1] = $this->rukuStorePlace_columns[$kuzhongStorePlace][1] - $this->chukuStorePlace_columns[$kuzhongStorePlace][1];
			//库中某个库存地重量的计算
			$this->kuzhongStorePlace_columns[$kuzhongStorePlace][2] = $this->rukuStorePlace_columns[$kuzhongStorePlace][2] - $this->chukuStorePlace_columns[$kuzhongStorePlace][2];
		}
		
		//各个厂家库中的数据
		$this->kuzhongManufactory_columns = array();
		foreach($this->kuzhongManufactories as $kuzhongManufactory){
			//库中某个库存地数量的计算，$chukuManufactory_columns[$kuzhongManufactory][1]包含了发车和发船出库中该库存地的数量和
			$this->kuzhongManufactory_columns[$kuzhongManufactory][0] = $kuzhongManufactory;
			$this->kuzhongManufactory_columns[$kuzhongManufactory][1] =$this->rukuManufactory_columns[$kuzhongManufactory][1] - $this->chukuManufactory_columns[$kuzhongManufactory][1];
			//库中某个库存地重量的计算
			$this->kuzhongManufactory_columns[$kuzhongManufactory][2] = $this->rukuManufactory_columns[$kuzhongManufactory][2] - $this->chukuManufactory_columns[$kuzhongManufactory][2];
		}
		
		$this->column = $this->exp->insertColumn($this->i,$this->column,"库中",$this->kuzhong,$this->kuzhongManufactory_columns,$this->kuzhongStorePlace_columns);
		return $this->column;
	}
	
	/**
	 * 添加欠交信息
	 * @return $this->column 执行结束之后的列号
	 */
	public function insertUnRukuData(){
		$this->unRuku = array();	//未动总计
		if($this->postUnRuku){
			$unRukuColumn = array();
			//未动的数量 = 订货的数量 - 销售的数量 - 入库的数量；
			$unRukuColumn[0] = $this->sum[0][0] - $this->sale[0][0] - $this->ruku[0][0];
			//未动的重量 = 订货的重量 - 销售的重量 - 入库的重量；
			$unRukuColumn[1] = $this->sum[0][1] - $this->sale[0][1] - $this->ruku[0][1];
			array_push($this->unRuku,$unRukuColumn);
		}
		
		
		//各个厂家未动的数据
		$this->unRukuManufactory_columns = array();
		foreach($this->unRukuManufactories as $unRukuManufactory){
			//库中某个库存地数量的计算，$saleManufactory_columns[$unRukuManufactory][1]包含了发车和发船出库中该库存地的数量和
			$this->unRukuManufactory_columns[$unRukuManufactory][0] = $unRukuManufactory;
			$this->unRukuManufactory_columns[$unRukuManufactory][1] =$this->manufactory_columns[$unRukuManufactory][1] - $this->rukuManufactory_columns[$unRukuManufactory][1] - $this->saleManufactory_columns[$unRukuManufactory][1];
			//库中某个库存地重量的计算
			$this->unRukuManufactory_columns[$unRukuManufactory][2] = $this->manufactory_columns[$unRukuManufactory][2] - $this->rukuManufactory_columns[$unRukuManufactory][2] - $this->saleManufactory_columns[$unRukuManufactory][2];
		}
		
		//往电子表格中添加欠交数据
		$this->column = $this->exp->insertColumn($this->i,$this->column,"欠交",$this->unRuku,$this->unRukuManufactory_columns);
		return $this->column;
	}
	
	/**
	 * @param $unRukuManufactory_columns the $unRukuManufactory_columns to set
	 */
	public function setUnRukuManufactory_columns($unRukuManufactory_columns) {
		$this->unRukuManufactory_columns = $unRukuManufactory_columns;
	}

	/**
	 * @param $unRuku the $unRuku to set
	 */
	public function setUnRuku($unRuku) {
		$this->unRuku = $unRuku;
	}

	/**
	 * @param $kuzhongManufactory_columns the $kuzhongManufactory_columns to set
	 */
	public function setKuzhongManufactory_columns($kuzhongManufactory_columns) {
		$this->kuzhongManufactory_columns = $kuzhongManufactory_columns;
	}

	/**
	 * @param $kuzhongStorePlace_columns the $kuzhongStorePlace_columns to set
	 */
	public function setKuzhongStorePlace_columns($kuzhongStorePlace_columns) {
		$this->kuzhongStorePlace_columns = $kuzhongStorePlace_columns;
	}

	/**
	 * @param $kuzhong the $kuzhong to set
	 */
	public function setKuzhong($kuzhong) {
		$this->kuzhong = $kuzhong;
	}

	/**
	 * @param $saleManufactory_columns the $saleManufactory_columns to set
	 */
	public function setSaleManufactory_columns($saleManufactory_columns) {
		$this->saleManufactory_columns = $saleManufactory_columns;
	}

	/**
	 * @param $saleMethod_columns the $saleMethod_columns to set
	 */
	public function setSaleMethod_columns($saleMethod_columns) {
		$this->saleMethod_columns = $saleMethod_columns;
	}

	/**
	 * @param $saleDestination_columns the $saleDestination_columns to set
	 */
	public function setSaleDestination_columns($saleDestination_columns) {
		$this->saleDestination_columns = $saleDestination_columns;
	}

	/**
	 * @param $saleStorePlace_columns the $saleStorePlace_columns to set
	 */
	public function setSaleStorePlace_columns($saleStorePlace_columns) {
		$this->saleStorePlace_columns = $saleStorePlace_columns;
	}

	/**
	 * @param $sale the $sale to set
	 */
	public function setSale($sale) {
		$this->sale = $sale;
	}

	/**
	 * @param $rukuStorePlace_columns the $rukuStorePlace_columns to set
	 */
	public function setRukuStorePlace_columns($rukuStorePlace_columns) {
		$this->rukuStorePlace_columns = $rukuStorePlace_columns;
	}

	/**
	 * @param $rukuManufactory_columns the $rukuManufactory_columns to set
	 */
	public function setRukuManufactory_columns($rukuManufactory_columns) {
		$this->rukuManufactory_columns = $rukuManufactory_columns;
	}

	/**
	 * @param $ruku the $ruku to set
	 */
	public function setRuku($ruku) {
		$this->ruku = $ruku;
	}

	/**
	 * @param $manufactory_columns the $manufactory_columns to set
	 */
	public function setManufactory_columns($manufactory_columns) {
		$this->manufactory_columns = $manufactory_columns;
	}

	/**
	 * @param $sum the $sum to set
	 */
	public function setSum($sum) {
		$this->sum = $sum;
	}

	/**
	 * @param $column the $column to set
	 */
	public function setColumn($column) {
		$this->column = $column;
	}

	/**
	 * @param $i the $i to set
	 */
	public function setI($i) {
		$this->i = $i;
	}

	/**
	 * @param $unRukuManufactories the $unRukuManufactories to set
	 */
	public function setUnRukuManufactories($unRukuManufactories) {
		$this->unRukuManufactories = $unRukuManufactories;
	}

	/**
	 * @param $kuzhongStorePlaces the $kuzhongStorePlaces to set
	 */
	public function setKuzhongStorePlaces($kuzhongStorePlaces) {
		$this->kuzhongStorePlaces = $kuzhongStorePlaces;
	}

	/**
	 * @param $kuzhongManufactories the $kuzhongManufactories to set
	 */
	public function setKuzhongManufactories($kuzhongManufactories) {
		$this->kuzhongManufactories = $kuzhongManufactories;
	}

	/**
	 * @param $saleMethods the $saleMethods to set
	 */
	public function setSaleMethods($saleMethods) {
		$this->saleMethods = $saleMethods;
	}

	/**
	 * @param $saleDestinations the $saleDestinations to set
	 */
	public function setSaleDestinations($saleDestinations) {
		$this->saleDestinations = $saleDestinations;
	}

	/**
	 * @param $saleStorePlaces the $saleStorePlaces to set
	 */
	public function setSaleStorePlaces($saleStorePlaces) {
		$this->saleStorePlaces = $saleStorePlaces;
	}

	/**
	 * @param $rukuStorePlaces the $rukuStorePlaces to set
	 */
	public function setRukuStorePlaces($rukuStorePlaces) {
		$this->rukuStorePlaces = $rukuStorePlaces;
	}

	/**
	 * @param $rukuManufactories the $rukuManufactories to set
	 */
	public function setRukuManufactories($rukuManufactories) {
		$this->rukuManufactories = $rukuManufactories;
	}

	/**
	 * @param $mainManufactories the $mainManufactories to set
	 */
	public function setMainManufactories($mainManufactories) {
		$this->mainManufactories = $mainManufactories;
	}

	/**
	 * @param $destinations the $destinations to set
	 */
	public function setDestinations($destinations) {
		$this->destinations = $destinations;
	}

	/**
	 * @param $storePlaces the $storePlaces to set
	 */
	public function setStorePlaces($storePlaces) {
		$this->storePlaces = $storePlaces;
	}

	/**
	 * @param $manufatories the $manufatories to set
	 */
	public function setManufatories($manufatories) {
		$this->manufatories = $manufatories;
	}

	/**
	 * @param $postKuzhong the $postKuzhong to set
	 */
	public function setPostKuzhong($postKuzhong) {
		$this->postKuzhong = $postKuzhong;
	}

	/**
	 * @param $postUnRuku the $postUnRuku to set
	 */
	public function setPostUnRuku($postUnRuku) {
		$this->postUnRuku = $postUnRuku;
	}

	/**
	 * @param $postSale the $postSale to set
	 */
	public function setPostSale($postSale) {
		$this->postSale = $postSale;
	}

	/**
	 * @param $postRuku the $postRuku to set
	 */
	public function setPostRuku($postRuku) {
		$this->postRuku = $postRuku;
	}

	/**
	 * @param $postMain the $postMain to set
	 */
	public function setPostMain($postMain) {
		$this->postMain = $postMain;
	}

	/**
	 * @param $posts the $posts to set
	 */
	public function setPosts($posts) {
		$this->posts = $posts;
	}

	/**
	 * @param $shipNumber the $shipNumber to set
	 */
	public function setShipNumber($shipNumber) {
		$this->shipNumber = $shipNumber;
	}

	/**
	 * @param $smarty the $smarty to set
	 */
	public function setSmarty($smarty) {
		$this->smarty = $smarty;
	}

	/**
	 * @param $exp the $exp to set
	 */
	public function setExp($exp) {
		$this->exp = $exp;
	}

	/**
	 * @param $filename the $filename to set
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	/**
	 * @return the $unRukuManufactory_columns
	 */
	public function getUnRukuManufactory_columns() {
		return $this->unRukuManufactory_columns;
	}

	/**
	 * @return the $unRuku
	 */
	public function getUnRuku() {
		return $this->unRuku;
	}

	/**
	 * @return the $kuzhongManufactory_columns
	 */
	public function getKuzhongManufactory_columns() {
		return $this->kuzhongManufactory_columns;
	}

	/**
	 * @return the $kuzhongStorePlace_columns
	 */
	public function getKuzhongStorePlace_columns() {
		return $this->kuzhongStorePlace_columns;
	}

	/**
	 * @return the $kuzhong
	 */
	public function getKuzhong() {
		return $this->kuzhong;
	}

	/**
	 * @return the $saleManufactory_columns
	 */
	public function getSaleManufactory_columns() {
		return $this->saleManufactory_columns;
	}

	/**
	 * @return the $saleMethod_columns
	 */
	public function getSaleMethod_columns() {
		return $this->saleMethod_columns;
	}

	/**
	 * @return the $saleDestination_columns
	 */
	public function getSaleDestination_columns() {
		return $this->saleDestination_columns;
	}

	/**
	 * @return the $saleStorePlace_columns
	 */
	public function getSaleStorePlace_columns() {
		return $this->saleStorePlace_columns;
	}

	/**
	 * @return the $sale
	 */
	public function getSale() {
		return $this->sale;
	}

	/**
	 * @return the $rukuStorePlace_columns
	 */
	public function getRukuStorePlace_columns() {
		return $this->rukuStorePlace_columns;
	}

	/**
	 * @return the $rukuManufactory_columns
	 */
	public function getRukuManufactory_columns() {
		return $this->rukuManufactory_columns;
	}

	/**
	 * @return the $ruku
	 */
	public function getRuku() {
		return $this->ruku;
	}

	/**
	 * @return the $manufactory_columns
	 */
	public function getManufactory_columns() {
		return $this->manufactory_columns;
	}

	/**
	 * @return the $sum
	 */
	public function getSum() {
		return $this->sum;
	}

	/**
	 * @return the $column
	 */
	public function getColumn() {
		return $this->column;
	}

	/**
	 * @return the $i
	 */
	public function getI() {
		return $this->i;
	}

	/**
	 * @return the $unRukuManufactories
	 */
	public function getUnRukuManufactories() {
		return $this->unRukuManufactories;
	}

	/**
	 * @return the $kuzhongStorePlaces
	 */
	public function getKuzhongStorePlaces() {
		return $this->kuzhongStorePlaces;
	}

	/**
	 * @return the $kuzhongManufactories
	 */
	public function getKuzhongManufactories() {
		return $this->kuzhongManufactories;
	}

	/**
	 * @return the $saleMethods
	 */
	public function getSaleMethods() {
		return $this->saleMethods;
	}

	/**
	 * @return the $saleDestinations
	 */
	public function getSaleDestinations() {
		return $this->saleDestinations;
	}

	/**
	 * @return the $saleStorePlaces
	 */
	public function getSaleStorePlaces() {
		return $this->saleStorePlaces;
	}

	/**
	 * @return the $rukuStorePlaces
	 */
	public function getRukuStorePlaces() {
		return $this->rukuStorePlaces;
	}

	/**
	 * @return the $rukuManufactories
	 */
	public function getRukuManufactories() {
		return $this->rukuManufactories;
	}

	/**
	 * @return the $mainManufactories
	 */
	public function getMainManufactories() {
		return $this->mainManufactories;
	}

	/**
	 * @return the $destinations
	 */
	public function getDestinations() {
		return $this->destinations;
	}

	/**
	 * @return the $storePlaces
	 */
	public function getStorePlaces() {
		return $this->storePlaces;
	}

	/**
	 * @return the $manufatories
	 */
	public function getManufatories() {
		return $this->manufatories;
	}

	/**
	 * @return the $postKuzhong
	 */
	public function getPostKuzhong() {
		return $this->postKuzhong;
	}

	/**
	 * @return the $postUnRuku
	 */
	public function getPostUnRuku() {
		return $this->postUnRuku;
	}

	/**
	 * @return the $postSale
	 */
	public function getPostSale() {
		return $this->postSale;
	}

	/**
	 * @return the $postRuku
	 */
	public function getPostRuku() {
		return $this->postRuku;
	}

	/**
	 * @return the $postMain
	 */
	public function getPostMain() {
		return $this->postMain;
	}

	/**
	 * @return the $posts
	 */
	public function getPosts() {
		return $this->posts;
	}

	/**
	 * @return the $shipNumber
	 */
	public function getShipNumber() {
		return $this->shipNumber;
	}

	/**
	 * @return the $smarty
	 */
	public function getSmarty() {
		return $this->smarty;
	}

	/**
	 * @return the $exp
	 */
	public function getExp() {
		return $this->exp;
	}

	/**
	 * @return the $filename
	 */
	public function getFilename() {
		return $this->filename;
	}
}
?>