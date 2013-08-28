<?php
require_once '../includes/Summary.class.php';

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$summary = new Summary($_POST); 

//获取船号
$shipNumber = $_POST['shipNumber_consignmentBatch'];
//获取分段号
$consignmentBatchs = $_POST['consignmentBatchs'];
//如果汇总需要该船号含有汉字的材料代码的信息，则在$subNumber中压入特定字符来表明需要此操作
if($_POST['consignmentBatchs_hanzi'])
	array_push($consignmentBatchs,"汉字");
	
//首先插入头两列的信息
$summary->getExp()->insertHeadColumn(0,"发货批次",$shipNumber,$consignmentBatchs);

//添加判断是否有多余的生产厂家，库存地，目的地
//如果多余则按如下格式提示错误信息，并结束程序
//材料代码，汇总纵向因素，汇总横向因素，汇总错误因素，订单号，订单子项号
foreach($consignmentBatchs as $consignmentBatch){
	//判断总表是否有不在sss_summary_element表中出现过的生产厂家,目的地，库存地
	//对于生产厂家，目的地，库存地为空的没有做考虑
	$query = "select materialCode, orderNumber, orderSubitemNumber, manufactory, storePlace, destination
			  from sss_main
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
		  	  consignmentBatch = '$consignmentBatch' and (manufactory is not null and manufactory <> '')
		  	   and (storePlace is not null and storePlace <> '') and (destination is not null and destination <> '')
		  	  group by materialCode, orderNumber, orderSubitemNumber";
	$summary->checkRedundantInfomation($query,$consignmentBatch);

}

foreach ($consignmentBatchs as $consignmentBatch){
	//每次循环开始将当前执行列号置2
	$summary->setColumn(2);
	//以下代码查找并往表格中添加对应于船号和发货批次的分段号
	$subNumbers = array();	
	$query = "select distinct(substring(materialCode,9,3)) as subNumber  from sss_main 
	where substring(materialCode,1,5) ='{$shipNumber}' and 
		  consignmentBatch = '$consignmentBatch' and length(materialCode) = CHARACTER_LENGTH(materialCode)";
	DB::query($query);
	$result = DB::getResult();
	while(($row=$result->fetch_assoc())!=null){
		array_push($subNumbers,$row['subNumber']);
	}
	$subNumberStr = join(',',$subNumbers);
	$summary->setColumn( $summary->getExp()->insertSubNumberOrConsignmentBatchColumn($summary->getI(),$summary->getColumn(),"分段号集",$subNumberStr));
	
	//添加订货数量信息
	$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  consignmentBatch = '$consignmentBatch'
		group by substring(materialCode,1,5),consignmentBatch";
	if($consignmentBatch == "汉字")
		$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  length(materialCode) != CHARACTER_LENGTH(materialCode)
			  and (consignmentBatch = '' or consignmentBatch is null)
		group by substring(materialCode,1,5)";
		
	$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  consignmentBatch = '$consignmentBatch' and manufactory = '?'
		group by substring(materialCode,1,5),consignmentBatch,manufactory";
	if($consignmentBatch == "汉字")
		$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber}'  
		and length(materialCode) != CHARACTER_LENGTH(materialCode) and manufactory = '?'
		and (consignmentBatch = '' or consignmentBatch is null)
		group by substring(materialCode,1,5),manufactory";
	$summary->insertMainData($sumQuery,$manufactoryQuery);//插入“订货数量”信息
	
	
	//添加入库信息
	$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  consignmentBatch = '$consignmentBatch' and phase = '入库'
		group by substring(materialCode,1,5),consignmentBatch";
	if($consignmentBatch == "汉字")
		$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
		  	length(materialCode) != CHARACTER_LENGTH(materialCode) and phase = '入库'
		  	and (consignmentBatch = '' or consignmentBatch is null)
			group by substring(materialCode,1,5)";
		
	$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		consignmentBatch = '$consignmentBatch' and phase = '入库' and substring(orderNumber,-7,2) = '?'
			group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)";
	if($consignmentBatch == "汉字")
		$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and phase = '入库' and substring(orderNumber,-7,2) = '?'
	  		and (consignmentBatch = '' or consignmentBatch is null)
			group by substring(materialCode,1,5),substring(orderNumber,-7,2)";
	
	$storePlaceQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		consignmentBatch = '$consignmentBatch' and phase = '入库' and storePlace = '?'
			group by substring(materialCode,1,5),consignmentBatch, storePlace";
	if($consignmentBatch == "汉字")
		$storePlaceQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and phase = '入库' and storePlace = '?'
	  		and (consignmentBatch = '' or consignmentBatch is null)
			group by substring(materialCode,1,5), storePlace";
	$summary->insertRukuData($sumQuery,$manufactoryQuery,$storePlaceQuery);
	
	
	//添加出库信息
	$sumQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		consignmentBatch = '$consignmentBatch' 
				group by substring(materialCode,1,5),consignmentBatch
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  consignmentBatch = '$consignmentBatch' 
			  group by substring(materialCode,1,5),consignmentBatch
			  )
	)as chukuTable
			  ";
	if($consignmentBatch == "汉字")
		$sumQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
		(
				  (
					select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
					where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
			  		length(materialCode) != CHARACTER_LENGTH(materialCode)
			  		and (consignmentBatch = '' or consignmentBatch is null)
					group by substring(materialCode,1,5)
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber}' and 
		  		  length(materialCode) != CHARACTER_LENGTH(materialCode)
		  		  and (consignmentBatch = '' or consignmentBatch is null)
				  group by substring(materialCode,1,5)
				  )
		)as chukuTable
				  ";

	//出库厂家查询语句
	$chukuManufactoryQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		consignmentBatch = '$consignmentBatch' and substring(orderNumber,-7,2) = '?'
				group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  consignmentBatch = '$consignmentBatch' and substring(orderNumber,-7,2) = '?'
			  group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)
			  )
	)as chukuManufactoryTable
			  ";
	
	if($consignmentBatch == "汉字")
	$chukuManufactoryQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode)  and substring(orderNumber,-7,2) = '?'
		  		and (consignmentBatch = '' or consignmentBatch is null)
				group by substring(materialCode,1,5),substring(orderNumber,-7,2)
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and substring(orderNumber,-7,2) = '?'
	  		  and (consignmentBatch = '' or consignmentBatch is null)
			  group by substring(materialCode,1,5),substring(orderNumber,-7,2)
			  )
	)as chukuManufactoryTable
			  ";

	//出库库存地查询语句
	$storePlaceQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		consignmentBatch = '$consignmentBatch' and storePlace = '?'
				group by substring(materialCode,1,5),consignmentBatch,storePlace
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  consignmentBatch = '$consignmentBatch' and storePlace = '?'
			  group by substring(materialCode,1,5),consignmentBatch,storePlace
			  )
	)as chukuStorePlaceTable
			  ";
	
	if($consignmentBatch == "汉字")
	$storePlaceQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) and storePlace = '?'
		  		and (consignmentBatch = '' or consignmentBatch is null)
				group by substring(materialCode,1,5),storePlace
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and storePlace = '?'
	  		  and (consignmentBatch = '' or consignmentBatch is null)
			  group by substring(materialCode,1,5),storePlace
			  )
	)as chukuStorePlaceTable
			  ";

	
	$destinationQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		consignmentBatch = '$consignmentBatch' and destination = '?'
				group by substring(materialCode,1,5),consignmentBatch,destination
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  consignmentBatch = '$consignmentBatch' and destination = '?'
			  group by substring(materialCode,1,5),consignmentBatch,destination
			  )
	)as chukuDestinationTable
			  ";
	
	if($consignmentBatch == "汉字")
	$destinationQuery = "select sum(newCount) as sumCount,sum(newWeight) as weight from 
	(
			  (
				select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fache 
				where phase='出库' and substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) and destination = '?'
		  		and (consignmentBatch = '' or consignmentBatch is null)
				group by substring(materialCode,1,5),destination
			  )
			  
			  union all
			  
			  (
			  select sum(count) as newCount, sum(unitWeight*count) as newWeight from sss_fachuan 
			  where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		  length(materialCode) != CHARACTER_LENGTH(materialCode) and destination = '?'
	  		  and (consignmentBatch = '' or consignmentBatch is null)
			  group by substring(materialCode,1,5),destination
			  )
	)as chukuDestinationTable
			  ";

	
		
	$methodQuery = "select sum(count) as sumCount, sum(count*unitWeight) as weight from ?
			    substring(materialCode,1,5) ='{$shipNumber}' and 
		  		consignmentBatch = '$consignmentBatch' 
				group by substring(materialCode,1,5),consignmentBatch
			  ";
	
	if($consignmentBatch == "汉字")
	$methodQuery = "select sum(count) as sumCount, sum(count*unitWeight) as weight from ?
			    substring(materialCode,1,5) ='{$shipNumber}' and 
		  		length(materialCode) != CHARACTER_LENGTH(materialCode) 
		  		and (consignmentBatch = '' or consignmentBatch is null)
				group by substring(materialCode,1,5)
			  ";
	$summary->insertChukuData($sumQuery,$chukuManufactoryQuery,$storePlaceQuery,$destinationQuery,$methodQuery);
		
	//添加销售信息
	$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
		where substring(materialCode,1,5) ='{$shipNumber}' and 
			  consignmentBatch = '$consignmentBatch' and phase = '销售'
		group by substring(materialCode,1,5),consignmentBatch";
	if($consignmentBatch == "汉字")
		$sumQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
		  	length(materialCode) != CHARACTER_LENGTH(materialCode) and phase = '销售'
		  	and (consignmentBatch = '' or consignmentBatch is null)
			group by substring(materialCode,1,5)";
		
	$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		consignmentBatch = '$consignmentBatch' and phase = '销售' and substring(orderNumber,-7,2) = '?'
			group by substring(materialCode,1,5),consignmentBatch,substring(orderNumber,-7,2)";
	if($consignmentBatch == "汉字")
		$manufactoryQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and phase = '销售' and substring(orderNumber,-7,2) = '?'
	  		and (consignmentBatch = '' or consignmentBatch is null)
			group by substring(materialCode,1,5),substring(orderNumber,-7,2)";
	
	$destinationQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		consignmentBatch = '$consignmentBatch' and phase = '销售' and destination = '?'
			group by substring(materialCode,1,5),consignmentBatch, destination";
	if($consignmentBatch == "汉字")
		$destinationQuery = "select sum(count) as sumCount,sum(count*unitWeight) as weight 
			from sss_fache
			where substring(materialCode,1,5) ='{$shipNumber}' and 
	  		length(materialCode) != CHARACTER_LENGTH(materialCode) and phase = '销售' and destination = '?'
	  		and (consignmentBatch = '' or consignmentBatch is null)
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