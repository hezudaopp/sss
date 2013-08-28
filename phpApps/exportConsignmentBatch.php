<?php
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SimpleTableExporter.class.php');
require_once('../includes/functions.inc.php');

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$smarty = SmartyManager::getSmarty();

$filename = '汇总'.'_'.date('YmdHis').'.xls';
$exp = new SimpleTableExporter($filename);
$exp->createNewSheet();

//获取船号
$shipNumber_consignmentBatch = $_POST['shipNumber_consignmentBatch'];
//获取发货批次
$consignmentBatchs = $_POST['consignmentBatchs'];
//如果汇总需要该船号含有汉字的材料代码的信息，则在$consignmentBatchs中压入特定字符来表明需要此操作
if($_POST['consignmentBatchs_hanzi'])
	array_push($consignmentBatchs,"汉字");
	
//首先插入头两列的信息
$exp->insertHeadColumn(0,"发货批次",$shipNumber_consignmentBatch,$consignmentBatchs);

//以下代码用来查找sss_summary_element表中的汇总因素
$manufatories = array();//生产厂家
$storePlaces = array();//库存地
$destinations = array();//目的地
$query = "select manufactory, storePlace, destination from sss_summary_element";
DB::query($query);
$result = DB::getResult();
while(($row=$result->fetch_assoc())!=null){
	if($row['manufactory'])//不为NULL时方可push
		array_push($manufatories,$row['manufactory']);
	if($row['storePlace'])
		array_push($storePlaces,$row['storePlace']);
	if($row['destination'])
		array_push($destinations,$row['destination']);
}

//以下代码是将POST传递来的信息分组，这样如果以后添加新的汇总因素方便扩展
$mainManufactories = array ();//存储用户选择的总表的生产厂家的数组
$rukuManufactories = array ();//入库生产厂家
$rukuStorePlaces = array();//入库库存地
$saleStorePlaces = array();//发货库存地
$saleDestinations = array();//发货目的地
$saleMethods = array();//发货方式，这里“直接销售”也归并到发货方式中
$kuzhongManufactories = array();//库中生产厂家
$kuzhongStorePlaces = array();//库中库存地
$unRukuManufactories = array();//未动生产厂家
foreach ( $_POST as $key => $val ) {
	if (strstr ( $key, "main_manu" )) {//注意strstr（）和stristr函数的俄区别，strstr:Case sensitive
		array_push ( $mainManufactories, $val );
	} else if (strstr ( $key, "ruku_manu" )) {
		array_push ( $rukuManufactories, $val );
	} else if (strstr ( $key, "ruku_store" )) {
		array_push ( $rukuStorePlaces, $val );
	} else if (strstr ( $key, "sale_store" )) {
		array_push ( $saleStorePlaces, $val );
	} else if (strstr ( $key, "sale_dest" )) {
		array_push ( $saleDestinations, $val );
	} else if (strstr ( $key, "sale_meth" )) {
		array_push ( $saleMethods, $val );
	} else if (strstr ( $key, "kuzhong_manu" )) {
		array_push ( $kuzhongManufactories, $val );
	} else if (strstr ( $key, "kuzhong_store" )) {
		array_push ( $kuzhongStorePlaces, $val );
	} else if (strstr ( $key, "unRuku_manu" )) {
		array_push ( $unRukuManufactories, $val );
	}
}

//TODO：添加判断是否有多余的生产厂家，库存地，目的地
//如果多余则按如下格式提示错误信息，并结束程序
//材料代码，汇总纵向因素，汇总横向因素，汇总错误因素，订单号，订单子项号
foreach($consignmentBatchs as $consignmentBatch){
	//判断总表是否有不在sss_summary_element表中出现过的生产厂家,目的地，库存地
	//对于生产厂家，目的地，库存地为空的没有做考虑
	$query = "select materialCode, orderNumber, orderSubitemNumber, manufactory, storePlace, destination
			  from sss_main
			  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  	  consignmentBatch = '$consignmentBatch' and (manufactory is not null and manufactory <> '')
		  	   and (storePlace is not null and storePlace <> '') and (destination is not null and destination <> '')
		  	  group by materialCode, orderNumber, orderSubitemNumber";
	DB::query($query);
	$result = DB::getResult();
	try{
		while(($row=$result->fetch_assoc())!=null){
			if(!in_array($row['manufactory'],$manufatories)){
				$errMsg = "以下的材料代码，订单号，订单子项号出现了特殊的生产厂家,信息如下：<br>
				".$row['materialCode']."|".$shipNumber_consignmentBatch."|".$consignmentBatch."
				|生产厂家|".$row['manufactory']."|".$row['orderNumber']."|".$row['orderSubitemNumber'];
				throw new CustomException($errMsg);
			}
			if(!in_array($row['storePlace'],$storePlaces)){
				$errMsg = "以下的材料代码，订单号，订单子项号出现了特殊的库存地,信息如下：<br>
				".$row['materialCode']."|".$shipNumber_consignmentBatch."|".$consignmentBatch."
				|库存地|".$row['storePlace']."|".$row['orderNumber']."|".$row['orderSubitemNumber'];
				throw new CustomException($errMsg);
			}
			if(!in_array($row['destination'],$destinations)){
				$errMsg = "以下的材料代码，订单号，订单子项号出现了特殊的目的地,信息如下：<br>
				".$row['materialCode']."|".$shipNumber_consignmentBatch."|".$consignmentBatch."
				|目的地|".$row['destination']."|".$row['orderNumber']."|".$row['orderSubitemNumber'];
				throw new CustomException($errMsg);
			}
		}
	}catch (Exception $e){
		$smarty->assign('errMsg', $e);
		$smarty->display('error.html');
		exit();
	}

}

$i = 2;//当前执行到的行数
foreach ($consignmentBatchs as $consignmentBatch){
	
	$column = 2;
	
	//以下代码查找并往表格中添加对应于船号和发货批次的分段号
	$subNumbers = array();	
	$query = "select distinct(substring(materialCode,9,3)) as subNumber  from sss_main 
	where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  consignmentBatch = '$consignmentBatch' and length(materialCode) = CHARACTER_LENGTH(materialCode)";
	DB::query($query);
	$result = DB::getResult();
	while(($row=$result->fetch_assoc())!=null){
		array_push($subNumbers,$row['subNumber']);
	}
	$subNumberStr = join(',',$subNumbers);
	$column = $exp->insertSubNumberOrConsignmentBatchColumn($i,$column,"分段号集",$subNumberStr);
	
	
	if($_POST['main']){//用户选择需要导出订货数量的信息
		//发货批次-总表-总计
		//按船号和发货批次分组计数
		$sum = array();	//总表总计
		$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  consignmentBatch = '$consignmentBatch'
		group by substring(materialCode,1,5),consignmentBatch";
		if($consignmentBatch == "汉字")
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  length(materialCode) = CHARACTER_LENGTH(materialCode)
		group by substring(materialCode,1,5),consignmentBatch";
		DB::query($query);
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
			array_push($sum,$oneSum);
		}else
			array_push($sum,array(0,0));
	}
	//发货批次-总表-产家
	$manufactory_columns = array();//该数组包含了各个生产厂家的数量和重量的信息
	foreach($mainManufactories as $manufactory){
		if(in_array($manufactory,$manufatories)){//如果用户选择的生产厂家在sss_summary_element中出现
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_main 
			where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				  consignmentBatch = '$consignmentBatch' and manufactory = '$manufactory'
			group by substring(materialCode,1,5),consignmentBatch,manufactory";
			if($consignmentBatch == "汉字")
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_main 
			where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}'  
			and length(materialCode) = CHARACTER_LENGTH(materialCode) and manufactory = '$manufactory'
			group by substring(materialCode,1,5),consignmentBatch,manufactory";
			DB::query($query);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$manufactory_columns[$manufactory][0] = $manufactory;//如果用array_push的话不仅影响效率，而且不方便索引
				if($row['sumCount']){
					$manufactory_columns[$manufactory][1] = $row['sumCount'];
					$manufactory_columns[$manufactory][2] = $row['weight'];
				}else{
					$manufactory_columns[$manufactory][1] = 0;
					$manufactory_columns[$manufactory][2] = 0;
				}
			}else
				$manufactory_columns[$manufactory] = array($manufactory,0,0);
		}
	}
	
		
	$column = $exp->insertColumn($i,$column,"订货数量",$sum,$manufactory_columns);//插入“订货数量”信息
	
	if($_POST['ruku']){//用户选择需要导出入库的信息
		//发货批次-入库-总计
		//按船号和发货批次分组计数
		$ruku = array();	//入库总计
		$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_fache
		where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  consignmentBatch = '$consignmentBatch' and phase = '入库'
		group by substring(materialCode,1,5),consignmentBatch";
		if($consignmentBatch == "汉字")
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_fache
				where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  	length(materialCode) = CHARACTER_LENGTH(materialCode) and phase = '入库'
				group by substring(materialCode,1,5),consignmentBatch";
		DB::query($query);
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
			array_push($ruku,$rukuColumn);
		}else
			array_push($ruku,array(0,0));
	}
	
	//入库-生产厂家
	$rukuManufactory_columns = array();//该数组包含了各个生产厂家的数量和重量的信息
	foreach($rukuManufactories as $rukuManufactory){
		if(in_array($rukuManufactory,$manufatories)){//如果用户选择的生产厂家在sss_summary_element中出现
			//以下代码的"B4","25","32"对应的是订单号中倒数6，7两位数据
			$rukuManufactoryCode = "";
			if($rukuManufactory == "鲅鱼圈厚板")
				$rukuManufactoryCode = "B4";
			else if($rukuManufactory == "鞍钢中板")
				$rukuManufactoryCode = "25";
			else if($rukuManufactory == "鞍钢厚板")
				$rukuManufactoryCode = "32";
				
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight 
					from sss_fache
					where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' and phase = '入库' and substring(orderNumber,-7,2) = '$rukuManufactoryCode'
					group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)";
			if($consignmentBatch == "汉字")
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight 
					from sss_fache
					where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode) and phase = '入库' and substring(orderNumber,-7,2) = '$rukuManufactoryCode'
					group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)";
			DB::query($query);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$rukuManufactory_columns[$rukuManufactory][0] = $rukuManufactory;
				if($row['sumCount']){
					$rukuManufactory_columns[$rukuManufactory][1] = $row['sumCount'];
					$rukuManufactory_columns[$rukuManufactory][2] = $row['weight'];
				}else{
					$rukuManufactory_columns[$rukuManufactory][1] = 0;
					$rukuManufactory_columns[$rukuManufactory][2] = 0;
				}
			}else
				$rukuManufactory_columns[$rukuManufactory] = array($rukuManufactory,0,0);
		}
	}
	
	//入库-库存地
	$rukuStorePlace_columns = array();//该数组包含了各个库存地的数量和重量的信息
	foreach($rukuStorePlaces as $rukuStorePlace){
		if(in_array($rukuStorePlace,$storePlaces)){//如果用户选择的生产厂家在sss_summary_element中出现
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight 
					from sss_fache
					where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' and phase = '入库' and storePlace = '$rukuStorePlace'
					group by substring(materialCode,1,5),consignmentBatch, storePlace";
			if($consignmentBatch == "汉字")
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight 
					from sss_fache
					where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode) and phase = '入库' and storePlace = '$rukuStorePlace'
					group by substring(materialCode,1,5),consignmentBatch, storePlace";
			DB::query($query);
			$result = DB::getResult();
			if(($row=$result->fetch_assoc())!=null){
				$rukuStorePlace_columns[$rukuStorePlace][0] = $rukuStorePlace;
				if($row['sumCount']){
					$rukuStorePlace_columns[$rukuStorePlace][1] = $row['sumCount'];
					$rukuStorePlace_columns[$rukuStorePlace][2] = $row['weight'];
				}else{
					$rukuStorePlace_columns[$rukuStorePlace][1] = 0;
					$rukuStorePlace_columns[$rukuStorePlace][2] = 0;
				}
			}else
				$rukuStorePlace_columns[$rukuStorePlace] = array($rukuStorePlace,0,0);
		}
	}
	
	$column = $exp->insertColumn($i,$column,"入库",$ruku,$rukuManufactory_columns,$rukuStorePlace_columns);//插入“入库”信息
	
	
	if($_POST['sale']){
		//发货-总计-发货批次
		$sale = array();
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where (phase='出库' or phase='销售') and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' 
					group by substring(materialCode,1,5),consignmentBatch
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  consignmentBatch = '$consignmentBatch' 
				  group by substring(materialCode,1,5),consignmentBatch
				  )
		)as saleTable
				  ";
		if($consignmentBatch == "汉字")
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where (phase='出库' or phase='销售') and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode)
					group by substring(materialCode,1,5),consignmentBatch
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  length(materialCode) = CHARACTER_LENGTH(materialCode)
				  group by substring(materialCode,1,5),consignmentBatch
				  )
		)as saleTable
				  ";
		DB::query($query);
		$result = DB::getResult();
		if(($row=$result->fetch_assoc())!=null){
			$oneSale = array();
			if($row['sumCount']){
				array_push($oneSale,$row['sumCount']);
				array_push($oneSale,$row['weight']);
			}else{
				array_push($oneSale,0);
				array_push($oneSale,0);
			}
			array_push($sale,$oneSale);
		}else
			array_push($sale,array(0,0));
	}
		
	//发货-库存地
	$saleStorePlace_columns = array();
	foreach($saleStorePlaces as $saleStorePlace){
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' and storePlace = '$saleStorePlace'
					group by substring(materialCode,1,5),consignmentBatch,storePlace
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  consignmentBatch = '$consignmentBatch' and storePlace = '$saleStorePlace'
				  group by substring(materialCode,1,5),consignmentBatch,storePlace
				  )
		)as saleStorePlaceTable
				  ";
		
		if($consignmentBatch == "汉字")
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode) and storePlace = '$saleStorePlace'
					group by substring(materialCode,1,5),consignmentBatch,storePlace
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  length(materialCode) = CHARACTER_LENGTH(materialCode) and storePlace = '$saleStorePlace'
				  group by substring(materialCode,1,5),consignmentBatch,storePlace
				  )
		)as saleStorePlaceTable
				  ";
		
		DB::query($query);
		$result = DB::getResult();
		if(($row=$result->fetch_assoc())!=null){
			$saleStorePlace_columns[$saleStorePlace][0] = $saleStorePlace;
				if($row['sumCount']){
					$saleStorePlace_columns[$saleStorePlace][1] = $row['sumCount'];
					$saleStorePlace_columns[$saleStorePlace][2] = $row['weight'];
				}else{
					$saleStorePlace_columns[$saleStorePlace][1] = 0;
					$saleStorePlace_columns[$saleStorePlace][2] = 0;
				}
			}else
				$saleStorePlace_columns[$saleStorePlace] = array($saleStorePlace,0,0);
	}
		
	//发货-目的地
	$saleDestination_columns = array();
	foreach($saleDestinations as $saleDestination){
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' and destination = '$saleDestination'
					group by substring(materialCode,1,5),consignmentBatch,destination
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  consignmentBatch = '$consignmentBatch' and destination = '$saleDestination'
				  group by substring(materialCode,1,5),consignmentBatch,destination
				  )
		)as saleDestinationTable
				  ";
		
		if($consignmentBatch == "汉字")
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode) and destination = '$saleDestination'
					group by substring(materialCode,1,5),consignmentBatch,destination
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  length(materialCode) = CHARACTER_LENGTH(materialCode) and destination = '$saleDestination'
				  group by substring(materialCode,1,5),consignmentBatch,destination
				  )
		)as saleDestinationTable
				  ";
		DB::query($query);
		$result = DB::getResult();
		if(($row=$result->fetch_assoc())!=null){
			$saleDestination_columns[$saleDestination][0] = $saleDestination;
				if($row['sumCount']){
					$saleDestination_columns[$saleDestination][1] = $row['sumCount'];
					$saleDestination_columns[$saleDestination][2] = $row['weight'];
				}else{
					$saleDestination_columns[$saleDestination][1] = 0;
					$saleDestination_columns[$saleDestination][2] = 0;
				}
			}else
				$saleDestination_columns[$saleDestination] = array($saleDestination,0,0);
	}
	
	//发货-发货方式
	$saleMethod_columns = array();
	foreach($saleMethods as $saleMethod){
		//通过发货方式选择对应的数据库表和出库的方式
		$table = "sss_fachuan where";
		if($saleMethod!="发船"){
			if($saleMethod == "发车")
				$phase = "出库";
			else
				$phase = "销售";
			$table = "sss_fache where phase = '$phase' and";
		}
			
		$query = "select sum(count) as sumCount, sum(count)*unitWeight as weight from {$table}
				    substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' 
					group by substring(materialCode,1,5),consignmentBatch
				  ";
		
		if($consignmentBatch == "汉字")
		$query = "select sum(count) as sumCount, sum(count)*unitWeight as weight from {$table}
				    substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode) 
					group by substring(materialCode,1,5),consignmentBatch
				  ";
		DB::query($query);
		$result = DB::getResult();
		if(($row=$result->fetch_assoc())!=null){
			$saleMethod_columns[$saleMethod][0] = $saleMethod;
				if($row['sumCount']){
					$saleMethod_columns[$saleMethod][1] = $row['sumCount'];
					$saleMethod_columns[$saleMethod][2] = $row['weight'];
				}else{
					$saleMethod_columns[$saleMethod][1] = 0;
					$saleMethod_columns[$saleMethod][2] = 0;
				}
			}else
				$saleMethod_columns[$saleMethod] = array($saleMethod,0,0);
	}
	
//	print_r($mainManufactories);
//	echo "<br>";
//	print_r($sum);
//	echo "<br>";
//	print_r($manufactory_columns);
//	echo "<hr>";
//	print_r($rukuStorePlaces);
//	echo "<br>";
//	print_r($rukuStorePlace_columns);
//	echo "<br>";
//	print_r($ruku);
//	echo "<br>";
//	print_r($rukuManufatory_columns);
//	echo "<hr>";
//	print_r($saleMethods);
//	echo "<br>";
//	print_r($saleDestination_columns);
//	echo "<br>";
//	print_r($saleStorePlace_columns);
//	echo "<br>";
//	print_r($saleMethod_columns);
//	echo "<hr>";
	
	
	//插入“发货”信息
	$column = $exp->insertColumn($i,$column,"发货",$sale,$saleStorePlace_columns,$saleDestination_columns,$saleMethod_columns);
	
	//库中-总计
	if($_POST['kuzhong']){//库中=入库-发车-发船
		$kuzhong = array();	//库中总计
		$kuzhongColumn = array();
		//库中的数量 = 入库的数量 - 发货[发车]的数量 - 发货[发船]的数量；
		$kuzhongColumn[0] = $ruku[0][0] - $saleMethod_columns['发车'][1] - $saleMethod_columns['发船'][1];
		//库中的重量 = 入库的重量 - 发货[发车]的重量 - 发货[发船]的重量；
		$kuzhongColumn[1] = $ruku[0][1] - $saleMethod_columns['发车'][2] - $saleMethod_columns['发船'][2];
		array_push($kuzhong,$kuzhongColumn);
	}
	
	//库中各个库存地的数据
	$kuzhongStorePlace_columns = array();
	foreach($kuzhongStorePlaces as $kuzhongStorePlace){
		$kuzhongStorePlace_columns[$kuzhongStorePlace][0] = $kuzhongStorePlace;
		//库中某个库存地数量的计算，$saleStorePlace_columns[$kuzhongStorePlace][1]包含了发车和发船出库中该库存地的数量和
		$kuzhongStorePlace_columns[$kuzhongStorePlace][1] = $rukuStorePlace_columns[$kuzhongStorePlace][1] - $saleStorePlace_columns[$kuzhongStorePlace][1];
		//库中某个库存地重量的计算
		$kuzhongStorePlace_columns[$kuzhongStorePlace][2] = $rukuStorePlace_columns[$kuzhongStorePlace][2] - $saleStorePlace_columns[$kuzhongStorePlace][2];
	}
	
	//在获取库中各个厂家的数据之前需要先获取“入库厂家数据“和“出库（发车和发船）厂家数据”
	//以下代码用来获取“出库（发车和发船）厂家数据"
	$saleManufactory_columns = array();
	foreach($manufatories as $manufactory){
		//以下代码的"B4","25","32"对应的是订单号中倒数6，7两位数据
		$manufactoryCode = "";
		if($manufactory == "鲅鱼圈厚板")
			$manufactoryCode = "B4";
		else if($manufactory == "鞍钢中板")
			$manufactoryCode = "25";
		else if($manufactory == "鞍钢厚板")
			$manufactoryCode = "32";
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		consignmentBatch = '$consignmentBatch' and substring(orderNumber,-7,2) = '$manufactoryCode'
					group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  consignmentBatch = '$consignmentBatch' and substring(orderNumber,-7,2) = '$manufactoryCode'
				  group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)
				  )
		)as saleManufactoryTable
				  ";
		
		if($consignmentBatch == "汉字")
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			  		length(materialCode) = CHARACTER_LENGTH(materialCode)  and substring(orderNumber,-7,2) = '$manufactoryCode'
					group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
		  		  length(materialCode) = CHARACTER_LENGTH(materialCode) and substring(orderNumber,-7,2) = '$manufactoryCode'
				  group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)
				  )
		)as saleManufactoryTable
				  ";
		DB::query($query);
		$result = DB::getResult();
		if(($row=$result->fetch_assoc())!=null){
			$saleManufactory_columns[$manufactory][0] = $manufactory;
				if($row['sumCount']){
					$saleManufactory_columns[$manufactory][1] = $row['sumCount'];
					$saleManufactory_columns[$manufactory][2] = $row['weight'];
				}else{
					$saleManufactory_columns[$manufactory][1] = 0;
					$saleManufactory_columns[$manufactory][2] = 0;
				}
			}else
				$saleManufactory_columns[$manufactory] = array($manufactory,0,0);
	}
			
	//各个厂家库中的数据
	$kuzhongManufactory_columns = array();
	foreach($kuzhongManufactories as $kuzhongManufactory){
		//库中某个库存地数量的计算，$saleManufactory_columns[$kuzhongManufactory][1]包含了发车和发船出库中该库存地的数量和
		$kuzhongManufactory_columns[$kuzhongManufactory][0] = $kuzhongManufactory;
		$kuzhongManufactory_columns[$kuzhongManufactory][1] =$rukuManufactory_columns[$kuzhongManufactory][1] - $saleManufactory_columns[$kuzhongManufactory][1];
		//库中某个库存地重量的计算
		$kuzhongManufactory_columns[$kuzhongManufactory][2] = $rukuManufactory_columns[$kuzhongManufactory][2] - $saleManufactory_columns[$kuzhongManufactory][2];
	}
	
	$column = $exp->insertColumn($i,$column,"库中",$kuzhong,$kuzhongManufactory_columns,$kuzhongStorePlace_columns);
	
//	print_r($rukuManufactory_columns);
//	echo "<br>";
//	print_r($saleManufactory_columns);
//	echo "<br>";
//	print_r($kuzhongManufactory_columns);
//	echo "<br>";
//	print_r($kuzhongStorePlace_columns);
//	echo "<br>";
//	print_r($saleStorePlace_columns);
//	echo "<br>";
//	print_r($rukuStorePlace_columns);
//	echo "<hr>";
//	print_r($kuzhong);
//	echo "<br>";
//	print_r($ruku);
//	echo "<br>";
//	print_r($saleMethod_columns['发车']);
//	echo "<br>";
//	print_r($saleMethod_columns['发船']);
//	echo "<hr>";

	//添加欠交信息
	if($_POST['unRuku']){
		$unRuku = array();	//未动总计
		$unRukuColumn = array();
		//未动的数量 = 订货的数量 - 销售的数量 - 入库的数量；
		$unRukuColumn[0] = $sum[0][0] - $sale[0][0] - $ruku[0][0];
		//未动的重量 = 订货的重量 - 销售的重量 - 入库的重量；
		$unRukuColumn[1] = $sum[0][1] - $sale[0][1] - $ruku[0][1];
		array_push($unRuku,$unRukuColumn);
	}
	
	
	//各个厂家未动的数据
	$unRukuManufactory_columns = array();
	foreach($unRukuManufactories as $unRukuManufactory){
		//库中某个库存地数量的计算，$saleManufactory_columns[$unRukuManufactory][1]包含了发车和发船出库中该库存地的数量和
		$unRukuManufactory_columns[$unRukuManufactory][0] = $unRukuManufactory;
		$unRukuManufactory_columns[$unRukuManufactory][1] =$manufactory_columns[$unRukuManufactory][1] - $rukuManufactory_columns[$unRukuManufactory][1] - $saleManufactory_columns[$unRukuManufactory][1];
		//库中某个库存地重量的计算
		$unRukuManufactory_columns[$unRukuManufactory][2] = $manufactory_columns[$unRukuManufactory][2] - $rukuManufactory_columns[$unRukuManufactory][2] - $saleManufactory_columns[$unRukuManufactory][2];
	}
	
	//往电子表格中添加欠交数据
	$column = $exp->insertColumn($i,$column,"欠交",$unRuku,$unRukuManufactory_columns);
	
//	print_r($unRuku);
//	echo "<br>";
//	print_r($sum);
//	echo "<br>";
//	print_r($ruku);
//	echo "<br>";
//	print_r($sale);
//	echo "<br>";
//	print_r($unRukuManufactory_columns);
//	echo "<br>";
//	print_r($manufactory_columns);
//	echo "<br>";
//	print_r($rukuManufactory_columns);
//	echo "<br>";
//	print_r($saleManufactory_columns);
//	echo "<hr>";
	
	/*
	if($_POST['kuzhong']){
	//发货批次-库中-总计
		
		$query = "
			select (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))*unitWeight as weight
			from
				(
					(
					select sum(`count`) as rukuCount, unitWeight, substring(materialCode,1,5) as shipNumber, consignmentBatch
					from sss_fache
					where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			 		      consignmentBatch = '$consignmentBatch' 
					group by substring(materialCode,1,5),consignmentBatch
					) as rukuCountTable
		
				left join
					(
					select sum(`newCount`) as chukuCount, substring(materialCode,1,5) as shipNumber, consignmentBatch
					from
						(
							(select sum(`count`) as newCount,substring(materialCode,1,5) as shipNumber, materialCode, consignmentBatch
							from sss_fache
							where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
								  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			 		     	      consignmentBatch = '$consignmentBatch' 
							group by substring(materialCode,1,5),consignmentBatch
							)
							union all
							(select sum(`count`) as newCount, substring(materialCode,1,5) as shipNumber, materialCode, consignmentBatch
							from sss_fachuan
							where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							      and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			 		      		  consignmentBatch = '$consignmentBatch' 
							group by substring(materialCode,1,5),consignmentBatch
							)
						) as newCountTable
					group by substring(materialCode,1,5),consignmentBatch
					)
					as chukuCountTable
				using (shipNumber,consignmentBatch)
				);";
		DB::query($query);
		$result = DB::getResult();
		while(($row=$result->fetch_assoc())!=null){
			$oneKuzhong = array();
			array_push($oneKuzhong,$row['kuzhongCount']);
			array_push($oneKuzhong,$row['weight']);
			array_push($kuzhong,$oneKuzhong);
		}
		
		if($_POST['ruku_storePlace']){
			//发货批次-库中-库存地
			$query = "
				select (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))*unitWeight as weight, storePlace
				from
					(
						(
						select sum(`count`) as rukuCount, unitWeight, substring(materialCode,1,5) as shipNumber, consignmentBatch, storePlace
						from sss_fache
						where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      consignmentBatch = '$consignmentBatch' 
						group by substring(materialCode,1,5),consignmentBatch, storePlace
						) as rukuCountTable
			
					left join
						(
						select sum(`newCount`) as chukuCount, substring(materialCode,1,5) as shipNumber, consignmentBatch, storePlace
						from
							(
								(select sum(`count`) as newCount,substring(materialCode,1,5) as shipNumber, materialCode, consignmentBatch, storePlace
								from sss_fache
								where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
									  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		     	      consignmentBatch = '$consignmentBatch' 
								group by substring(materialCode,1,5),consignmentBatch, storePlace
								)
								union all
								(select sum(`count`) as newCount, substring(materialCode,1,5) as shipNumber, materialCode, consignmentBatch, storePlace
								from sss_fachuan
								where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
								      and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      		  consignmentBatch = '$consignmentBatch' 
								group by substring(materialCode,1,5),consignmentBatch, storePlace
								)
							) as newCountTable
						group by substring(materialCode,1,5),consignmentBatch, storePlace
						)
						as chukuCountTable
					using (shipNumber,consignmentBatch, storePlace)
					);";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$oneStorePlace = array();
				array_push($oneStorePlace,$row['storePlace']);
				array_push($oneStorePlace,$row['kuzhongCount']);
				array_push($oneStorePlace,$row['weight']);
				array_push($kuzhongStorePlaces,$oneStorePlace);
			}
		}
		
		if($_POST['ruku_manufactory']){
		//库中-产家-发货批次
			$query = "
				select (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))*unitWeight as weight, manufactory
				from
					(
						(
							select unitWeight, substring(materialCode,1,5) as shipNumber, consignmentBatch, manufactory,materialCode
							from sss_main
							where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '') and
							substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and consignmentBatch = '$consignmentBatch' 
							group by substring(materialCode,1,5),consignmentBatch,manufactory
						)as mainCountTable
						
					left join
								
						(
						select sum(`count`) as rukuCount,materialCode,substring(materialCode,1,5) as shipNumber,consignmentBatch
						from sss_fache
						where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      consignmentBatch = '$consignmentBatch' 
						group by substring(materialCode,1,5),consignmentBatch
						) as rukuCountTable
					using (shipNumber,consignmentBatch)
						
					left join
					
						(
						select sum(`newCount`) as chukuCount,substring(materialCode,1,5) as shipNumber,consignmentBatch
						from
							(
								(select sum(`count`) as newCount,substring(materialCode,1,5) as shipNumber,materialCode, consignmentBatch
								from sss_fache
								where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
									  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		     	      consignmentBatch = '$consignmentBatch' 
								group by substring(materialCode,1,5),consignmentBatch
								)
								union all
								(select sum(`count`) as newCount, substring(materialCode,1,5) as shipNumber,materialCode, consignmentBatch
								from sss_fachuan
								where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
								      and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      		  consignmentBatch = '$consignmentBatch' 
								group by substring(materialCode,1,5),consignmentBatch
								)
							) as newCountTable
						group by substring(materialCode,1,5),consignmentBatch
						)
						as chukuCountTable
					using (shipNumber,consignmentBatch)
					)
					where (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))>0;";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$oneManufactory = array();
				array_push($oneManufactory,$row['manufactory']);
				array_push($oneManufactory,$row['kuzhongCount']);
				array_push($oneManufactory,$row['weight']);
				array_push($kuzhongManufactories,$oneManufactory);
			}
		}
		$column = $exp->insertKuzhong($i,$column,$kuzhong,$kuzhongStorePlaces,$kuzhongManufactories);
	}
	
	if($_POST['unRuku']){
		//欠交-总计-发货批次
		$query = "select (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
				(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0))*unitWeight as weight
		from
				(
					(
					select sum(`count`) as sumCount, unitWeight, substring(materialCode,1,5) as shipNumber, consignmentBatch
					from sss_main
					where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			 		      consignmentBatch = '$consignmentBatch' 
					group by substring(materialCode,1,5),consignmentBatch
					) as mainCountTable
		
				left join
		
					(
					select sum(`count`) as rukuCount, substring(materialCode,1,5) as shipNumber, consignmentBatch
					from sss_fache
					where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			 		      consignmentBatch = '$consignmentBatch' 
					group by substring(materialCode,1,5),consignmentBatch
					) as rukuCountTable
					using (shipNumber,consignmentBatch)
		
				left join
		
					(
					select sum(`count`) as directCount, substring(materialCode,1,5) as shipNumber, consignmentBatch
					from sss_fache
					where phase = '销售' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
			 		      consignmentBatch = '$consignmentBatch' 
					group by substring(materialCode,1,5),consignmentBatch
					)
					as directCountTable
		
				using (shipNumber,consignmentBatch)
				);";
		DB::query($query);
		$result = DB::getResult();
		while(($row=$result->fetch_assoc())!=null){
			$oneWeidong = array();
			array_push($oneWeidong,$row['unRukuCount']);
			array_push($oneWeidong,$row['weight']);
			array_push($weidong,$oneWeidong);
		}
		
		if($_POST['unRuku_manufactory']){
			//欠交-产家-发货批次
			$query = "select (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
					(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0))*unitWeight as weight, manufactory
			from
					(
						(
						select sum(`count`) as sumCount, unitWeight, substring(materialCode,1,5) as shipNumber, consignmentBatch, manufactory
						from sss_main
						where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      consignmentBatch = '$consignmentBatch' 
						group by substring(materialCode,1,5),consignmentBatch, manufactory
						) as mainCountTable
			
					left join
			
						(
						select sum(`count`) as rukuCount, substring(materialCode,1,5) as shipNumber, consignmentBatch
						from sss_fache
						where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      consignmentBatch = '$consignmentBatch' 
						group by substring(materialCode,1,5),consignmentBatch
						) as rukuCountTable
						using (shipNumber,consignmentBatch)
			
					left join
			
						(
						select sum(`count`) as directCount, substring(materialCode,1,5) as shipNumber, consignmentBatch
						from sss_fache
						where phase = '销售' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_consignmentBatch}' and 
				 		      consignmentBatch = '$consignmentBatch' 
						group by substring(materialCode,1,5),consignmentBatch
						)
						as directCountTable
			
					using (shipNumber,consignmentBatch)
					);";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$oneWeidongManufactory = array();
				array_push($oneWeidongManufactory,$row['manufactory']);
				array_push($oneWeidongManufactory,$row['unRukuCount']);
				array_push($oneWeidongManufactory,$row['weight']);
				array_push($weidongManufactories,$oneWeidongManufactory);
			}
		}
		$column = $exp->insertWeidong($i,$column,$weidong,$weidongManufactories);
	}
	
	
		$column = $exp->insertChuku($i,$column,$chuku,$chukuStorePlaces,$chukuDestinations,$chukuMethods);
	}*/
	$i++;
}

$exp->export();
?>