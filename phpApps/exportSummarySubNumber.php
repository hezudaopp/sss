<?php
require_once '../includes/Summary.class.php';

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$summary = new Summary($_POST); 

//获取船号
$shipNumber = $_POST['shipNumber_subNumber'];
//获取分段号
$subNumbers = $_POST['subNumbers'];
//如果汇总需要该船号含有汉字的材料代码的信息，则在$subNumber中压入特定字符来表明需要此操作
if($_POST['subNumbers_hanzi'])
	array_push($subNumbers,"汉字");
	
//首先插入头两列的信息
$summary->getExp()->insertHeadColumn(0,"分段号",$shipNumber,$subNumbers);

//添加判断是否有多余的生产厂家，库存地，目的地
//如果多余则按如下格式提示错误信息，并结束程序
//材料代码，汇总纵向因素，汇总横向因素，汇总错误因素，订单号，订单子项号
foreach($subNumbers as $subNumber){
	//判断总表是否有不在sss_summary_element表中出现过的生产厂家,目的地，库存地
	//对于生产厂家，目的地，库存地为空的没有做考虑
	$query = "select materialCode, orderNumber, orderSubitemNumber, manufactory, storePlace, destination
			  from sss_main
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
		  	  substring(materialCode,9,3) = '$subNumber' and (manufactory is not null and manufactory <> '')
		  	   and (storePlace is not null and storePlace <> '') and (destination is not null and destination <> '')
		  	  group by materialCode, orderNumber, orderSubitemNumber";
	$summary->checkRedundantInfomation($query,$subNumber);

}

foreach ($subNumbers as $subNumber){
	//每次循环开始将当前执行列号置2
	$summary->setColumn(2);
	//以下代码查找并往表格中添加对应于船号和发货批次的分段号
	$consignmentBatchs = array();	
	$query = "select distinct(consignmentBatch) as consignmentBatch  from sss_main 
	where substring(materialCode,1,5) ='{$shipNumber}' and 
		  substring(materialCode,9,3) = '$subNumber' and length(materialCode) = CHARACTER_LENGTH(materialCode)";
	DB::query($query);
	$result = DB::getResult();
	while(($row=$result->fetch_assoc())!=null){
		array_push($consignmentBatchs,$row['consignmentBatch']);
	}
	$consignmentBatchStr = join(',',$consignmentBatchs);
	$summary->setColumn( $summary->getExp()->insertSubNumberOrConsignmentBatchColumn($summary->getI(),$summary->getColumn(),"发货批次集",$consignmentBatchStr));
	
	//添加订货数量信息
	$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  substring(materialCode,9,3) = '$subNumber'
		group by substring(materialCode,1,5),substring(materialCode,9,3)";
	if($subNumber == "汉字")
		$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3
		group by substring(materialCode,1,5)";
		
	$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  substring(materialCode,9,3) = '$subNumber' and manufactory = '?'
		group by substring(materialCode,1,5),substring(materialCode,9,3),manufactory";
	if($subNumber == "汉字")
		$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}'  
		and length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and manufactory = '?'
		group by substring(materialCode,1,5),manufactory";
	$summary->insertMainData($sumQuery,$manufactoryQuery);//插入“订货数量”信息
	
	
	//添加入库信息
	$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  substring(materialCode,9,3) = '$subNumber' and phase = '入库'
		group by substring(materialCode,1,5),substring(materialCode,9,3)";
	if($subNumber == "汉字")
		$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
		  	length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and phase = '入库'
			group by substring(materialCode,1,5)";
		
	$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		substring(materialCode,9,3) = '$subNumber' and phase = '入库' and substring(orderNumber,-7,2) = '?'
			group by substring(materialCode,1,5),substring(materialCode,9,3),substring(orderNumber,-7,2)";
	if($subNumber == "汉字")
		$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and phase = '入库' and substring(orderNumber,-7,2) = '?'
			group by substring(materialCode,1,5),substring(orderNumber,-7,2)";
	
	$storePlaceQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		substring(materialCode,9,3) = '$subNumber' and phase = '入库' and storePlace = '?'
			group by substring(materialCode,1,5),substring(materialCode,9,3), storePlace";
	if($subNumber == "汉字")
		$storePlaceQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and phase = '入库' and storePlace = '?'
			group by substring(materialCode,1,5), storePlace";
	$summary->insertRukuData($sumQuery,$manufactoryQuery,$storePlaceQuery);
	
	
	//添加出库信息
	$sumQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, unitWeight*count as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		substring(materialCode,9,3) = '$subNumber' 
				group by substring(materialCode,1,5),substring(materialCode,9,3)
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  substring(materialCode,9,3) = '$subNumber' 
			  group by substring(materialCode,1,5),substring(materialCode,9,3)
			  )
	)as chukuTable
			  ";
	if($subNumber == "汉字")
		$sumQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
		(
				  (
					select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
			  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3
					group by substring(materialCode,1,5)
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber}' and 
		  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3
				  group by substring(materialCode,1,5)
				  )
		)as chukuTable
				  ";

	$chukuManufactoryQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		substring(materialCode,9,3) = '$subNumber' and substring(orderNumber,-7,2) = '?'
				group by substring(materialCode,1,5),substring(materialCode,9,3),substring(orderNumber,-7,2)
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  substring(materialCode,9,3) = '$subNumber' and substring(orderNumber,-7,2) = '?'
			  group by substring(materialCode,1,5),substring(materialCode,9,3),substring(orderNumber,-7,2)
			  )
	)as chukuManufactoryTable
			  ";
	
	if($subNumber == "汉字")
	$chukuManufactoryQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3  and substring(orderNumber,-7,2) = '?'
				group by substring(materialCode,1,5),substring(orderNumber,-7,2)
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and substring(orderNumber,-7,2) = '?'
			  group by substring(materialCode,1,5),substring(orderNumber,-7,2)
			  )
	)as chukuManufactoryTable
			  ";
	
	$storePlaceQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		substring(materialCode,9,3) = '$subNumber' and storePlace = '?'
				group by substring(materialCode,1,5),substring(materialCode,9,3),storePlace
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  substring(materialCode,9,3) = '$subNumber' and storePlace = '?'
			  group by substring(materialCode,1,5),substring(materialCode,9,3),storePlace
			  )
	)as chukuStorePlaceTable
			  ";
	
	if($subNumber == "汉字")
	$storePlaceQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and storePlace = '?'
				group by substring(materialCode,1,5),storePlace
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and storePlace = '?'
			  group by substring(materialCode,1,5),storePlace
			  )
	)as chukuStorePlaceTable
			  ";

	
	$destinationQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		substring(materialCode,9,3) = '$subNumber' and destination = '?'
				group by substring(materialCode,1,5),substring(materialCode,9,3),destination
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  substring(materialCode,9,3) = '$subNumber' and destination = '?'
			  group by substring(materialCode,1,5),substring(materialCode,9,3),destination
			  )
	)as chukuDestinationTable
			  ";
	
	if($subNumber == "汉字")
	$destinationQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and destination = '?'
				group by substring(materialCode,1,5),destination
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and destination = '?'
			  group by substring(materialCode,1,5),destination
			  )
	)as chukuDestinationTable
			  ";

	
		
	$methodQuery = "select sum(count) as sumCount, sum(count*unitWeight) as weight from ?
			    substring(materialCode,1,5) ='{$shipNumber}' and 
		  		substring(materialCode,9,3) = '$subNumber' 
				group by substring(materialCode,1,5),substring(materialCode,9,3)
			  ";
	
	if($subNumber == "汉字")
	$methodQuery = "select sum(count) as sumCount, sum(count*unitWeight) as weight from ?
			    substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 
				group by substring(materialCode,1,5)
			  ";
	$summary->insertChukuData($sumQuery,$manufactoryQuery,$storePlaceQuery,$destinationQuery,$methodQuery);
		
	//添加销售信息
	$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  substring(materialCode,9,3) = '$subNumber' and phase = '销售'
		group by substring(materialCode,1,5),substring(materialCode,9,3)";
	if($subNumber == "汉字")
		$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
		  	length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and phase = '销售'
			group by substring(materialCode,1,5)";
		
	$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		substring(materialCode,9,3) = '$subNumber' and phase = '销售' and substring(orderNumber,-7,2) = '?'
			group by substring(materialCode,1,5),substring(materialCode,9,3),substring(orderNumber,-7,2)";
	if($subNumber == "汉字")
		$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and phase = '销售' and substring(orderNumber,-7,2) = '?'
			group by substring(materialCode,1,5),substring(orderNumber,-7,2)";
	
	$storePlaceQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		substring(materialCode,9,3) = '$subNumber' and phase = '销售' and storePlace = '?'
			group by substring(materialCode,1,5),substring(materialCode,9,3), storePlace";
	if($subNumber == "汉字")
		$destinationQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and CHARACTER_LENGTH(substring(materialCode,9,3)) !=3 and phase = '销售' and destination = '?'
			group by substring(materialCode,1,5), destination";
	$summary->insertSaleData($sumQuery,$manufactoryQuery,$destinationQuery);
		
	//添加库中信息
	$summary->insertKuzhongData();
	
	//添加欠交信息
	$summary->insertUnRukuData();
	
	//每次循环结束，当前执行到行号递增
	$summary->setI($summary->getI()+1);
}

$summary->getExp()->export();
?>