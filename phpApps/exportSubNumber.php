<?php
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/SimpleTableExporter.class.php');

ini_set("max_execution_time",0);
ini_set( "memory_limit" , "128M");

$filename = '汇总'.'_'.date('YmdHis').'.xls';
$exp = new SimpleTableExporter($filename);
$exp->createNewSheet();

$shipNumber_subNumber = $_POST['shipNumber_subNumber'];
$subNumbers = $_POST['subNumbers'];

$exp->insertSubNumber(0,$shipNumber_subNumber,$subNumbers);
$i = 2;
foreach ($subNumbers as $subNumber){
	
	$column = 2;
	
	$sum = array();	//总表总计
	$manufatories = array(); //总表产家
	$kuzhong = array();//库中总计
	$kuzhongStorePlaces = array();//库中库存地
	$kuzhongManufactories = array();//库中产家
	$weidong = array();//欠交
	$weidongManufactories = array();//欠交产家
	$chuku = array();//发货总计
	$chukuDestinations = array();//发货目的地
	$chukuStorePlaces = array();//发货库存地
	$chukuMethods = array();//发货方式

	//分段号-总表-总计
	if($_POST['main']){
		$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight from sss_main 
		where substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			  substring(materialCode,9,3) = '$subNumber'
		group by substring(materialCode,1,5),substring(materialCode,9,3)";
		DB::query($query);
		$result = DB::getResult();
		while(($row=$result->fetch_assoc())!=null){
			$sumWeight = array();
			array_push($sumWeight,$row['sumCount']);
			array_push($sumWeight,$row['weight']);
			array_push($sum,$sumWeight);
		}
		
		//分段号-总表-产家
		if($_POST['main_manufactory']){
			$query = "select sum(count) as sumCount,sum(count)*unitWeight as weight,manufactory from sss_main 
			where substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				  substring(materialCode,9,3) = '$subNumber' 
			group by substring(materialCode,1,5),substring(materialCode,9,3),manufactory";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$oneManufactory = array();
				array_push($oneManufactory,$row['manufactory']);
				array_push($oneManufactory,$row['sumCount']);
				array_push($oneManufactory,$row['weight']);
				array_push($manufatories,$oneManufactory);
			}
		}
		$column = $exp->insertDinghuo($i,$column,$sum,$manufatories);
	}
	
	if($_POST['ruku']){
	//分段号-库中-总计
		
		$query = "
			select (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))*unitWeight as weight
			from
				(
					(
					select sum(`count`) as rukuCount, unitWeight, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
					from sss_fache
					where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			 		      substring(materialCode,9,3) = '$subNumber' 
					group by substring(materialCode,1,5),substring(materialCode,9,3)
					) as rukuCountTable
		
				left join
					(
					select sum(`newCount`) as chukuCount, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
					from
						(
							(select sum(`count`) as newCount,substring(materialCode,1,5) as shipNumber, materialCode, substring(materialCode,9,3)
							from sss_fache
							where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
								  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			 		     	      substring(materialCode,9,3) = '$subNumber' 
							group by substring(materialCode,1,5),substring(materialCode,9,3)
							)
							union all
							(select sum(`count`) as newCount, substring(materialCode,1,5) as shipNumber, materialCode, substring(materialCode,9,3) as subNumber
							from sss_fachuan
							where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							      and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			 		      		  substring(materialCode,9,3) = '$subNumber' 
							group by substring(materialCode,1,5),substring(materialCode,9,3)
							)
						) as newCountTable
					group by substring(materialCode,1,5),substring(materialCode,9,3)
					)
					as chukuCountTable
				using (shipNumber, subNumber)
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
			//分段号-库中-库存地
			$query = "
				select (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))*unitWeight as weight, storePlace
				from
					(
						(
						select sum(`count`) as rukuCount, unitWeight, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber, storePlace
						from sss_fache
						where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3), storePlace
						) as rukuCountTable
			
					left join
						(
						select sum(`newCount`) as chukuCount, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber, storePlace
						from
							(
								(select sum(`count`) as newCount,substring(materialCode,1,5) as shipNumber, materialCode, substring(materialCode,9,3) as subNumber, storePlace
								from sss_fache
								where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
									  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		     	      substring(materialCode,9,3) = '$subNumber' 
								group by substring(materialCode,1,5),substring(materialCode,9,3), storePlace
								)
								union all
								(select sum(`count`) as newCount, substring(materialCode,1,5) as shipNumber, materialCode, substring(materialCode,9,3) as subNumber, storePlace
								from sss_fachuan
								where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
								      and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      		  substring(materialCode,9,3) = '$subNumber' 
								group by substring(materialCode,1,5),substring(materialCode,9,3), storePlace
								)
							) as newCountTable
						group by substring(materialCode,1,5),substring(materialCode,9,3), storePlace
						)
						as chukuCountTable
					using (shipNumber,subNumber, storePlace)
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
		//库中-产家-分段号
			$query = "
				select (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0))*unitWeight as weight, manufactory
				from
					(
						(
							select unitWeight, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber, manufactory,materialCode
							from sss_main
							where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '') and
							substring(materialCode,1,5) ='{$shipNumber_subNumber}' and substring(materialCode,9,3) = '$subNumber' 
							group by substring(materialCode,1,5),substring(materialCode,9,3),manufactory
						)as mainCountTable
						
					left join
								
						(
						select sum(`count`) as rukuCount,materialCode,substring(materialCode,1,5) as shipNumber,substring(materialCode,9,3) as subNumber
						from sss_fache
						where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3)
						) as rukuCountTable
					using (shipNumber,subNumber)
						
					left join
					
						(
						select sum(`newCount`) as chukuCount,substring(materialCode,1,5) as shipNumber,substring(materialCode,9,3) as subNumber
						from
							(
								(select sum(`count`) as newCount,substring(materialCode,1,5) as shipNumber,materialCode, substring(materialCode,9,3) as subNumber
								from sss_fache
								where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
									  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		     	      substring(materialCode,9,3) = '$subNumber' 
								group by substring(materialCode,1,5),substring(materialCode,9,3)
								)
								union all
								(select sum(`count`) as newCount, substring(materialCode,1,5) as shipNumber,materialCode, substring(materialCode,9,3) as subNumber
								from sss_fachuan
								where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
								      and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      		  substring(materialCode,9,3) = '$subNumber' 
								group by substring(materialCode,1,5),substring(materialCode,9,3)
								)
							) as newCountTable
						group by substring(materialCode,1,5),substring(materialCode,9,3)
						)
						as chukuCountTable
					using (shipNumber,subNumber)
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
		//欠交-总计-分段号
		$query = "select (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
				(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0))*unitWeight as weight
		from
				(
					(
					select sum(`count`) as sumCount, unitWeight, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
					from sss_main
					where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			 		      substring(materialCode,9,3) = '$subNumber' 
					group by substring(materialCode,1,5),substring(materialCode,9,3)
					) as mainCountTable
		
				left join
		
					(
					select sum(`count`) as rukuCount, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
					from sss_fache
					where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			 		      substring(materialCode,9,3) = '$subNumber' 
					group by substring(materialCode,1,5),substring(materialCode,9,3)
					) as rukuCountTable
					using (shipNumber,subNumber)
		
				left join
		
					(
					select sum(`count`) as directCount, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
					from sss_fache
					where phase = '销售' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
						  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			 		      substring(materialCode,9,3) = '$subNumber' 
					group by substring(materialCode,1,5),substring(materialCode,9,3)
					)
					as directCountTable
		
				using (shipNumber,subNumber)
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
			//欠交-产家-分段号
			$query = "select (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
					(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0))*unitWeight as weight, manufactory
			from
					(
						(
						select sum(`count`) as sumCount, unitWeight, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber, manufactory
						from sss_main
						where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3), manufactory
						) as mainCountTable
			
					left join
			
						(
						select sum(`count`) as rukuCount, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
						from sss_fache
						where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3)
						) as rukuCountTable
						using (shipNumber,subNumber)
			
					left join
			
						(
						select sum(`count`) as directCount, substring(materialCode,1,5) as shipNumber, substring(materialCode,9,3) as subNumber
						from sss_fache
						where phase = '销售' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
							  and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				 		      substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3)
						)
						as directCountTable
			
					using (shipNumber,subNumber)
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
	
	if($_POST['sale']){
		//发货-总计-分段号
		$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight from 
		(
				  (
					select sum(count) as newCount, unitWeight from sss_fache 
					where (phase='出库' or phase='销售') and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			  		substring(materialCode,9,3) = '$subNumber' 
					group by substring(materialCode,1,5),substring(materialCode,9,3)
				  )
				  
				  union all
				  
				  (
				  select sum(count) as newCount, unitWeight from sss_fachuan 
				  where substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
		  		  substring(materialCode,9,3) = '$subNumber' 
				  group by substring(materialCode,1,5),substring(materialCode,9,3)
				  )
		)as chukuTable
				  ";
		DB::query($query);
		$result = DB::getResult();
		while(($row=$result->fetch_assoc())!=null){
			$oneChuku = array();
			array_push($oneChuku,$row['sumCount']);
			array_push($oneChuku,$row['weight']);
			array_push($chuku,$oneChuku);
		}
		
		if($_POST['sale_storePlace']){
			//发货-库存地-分段号
			$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight, storePlace from 
			(
					  (
						select sum(count) as newCount, unitWeight, storePlace from sss_fache 
						where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				  		substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3),storePlace
					  )
					  
					  union all
					  
					  (
					  select sum(count) as newCount, unitWeight, storePlace from sss_fachuan 
					  where substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			  		  substring(materialCode,9,3) = '$subNumber' 
					  group by substring(materialCode,1,5),substring(materialCode,9,3),storePlace
					  )
			)as chukuStorePlaceTable
					  ";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$oneChukuStorePlace = array();
				array_push($oneChukuStorePlace,$row['storePlace']);
				array_push($oneChukuStorePlace,$row['sumCount']);
				array_push($oneChukuStorePlace,$row['weight']);
				array_push($chukuStorePlaces,$oneChukuStorePlace);
			}
		}
		
		if($_POST['sale_destination']){
			//发货-目的地-分段号
			$query = "select sum(newCount) as sumCount,sum(newCount)*unitWeight as weight, destination from 
			(
					  (
						select sum(count) as newCount, unitWeight, destination from sss_fache 
						where (phase='出库' or phase='销售') and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				  		substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3),destination
					  )
					  
					  union all
					  
					  (
					  select sum(count) as newCount, unitWeight, destination from sss_fachuan 
					  where substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
			  		  substring(materialCode,9,3) = '$subNumber' 
					  group by substring(materialCode,1,5),substring(materialCode,9,3),destination
					  )
			)as chukuStorePlaceTable
					  ";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$oneDestination = array();
				array_push($oneDestination,$row['destination']);
				array_push($oneDestination,$row['sumCount']);
				array_push($oneDestination,$row['weight']);
				array_push($chukuDestinations,$oneDestination);
			}
		}
		
		if($_POST['sale_saleType']){
			//发货-发货方式-分段号
			$query = "select sum(count) as sumCount, sum(count)*unitWeight as weight from sss_fache 
						where phase='出库' and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				  		substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3)
					  ";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$facheMethod = array();
				array_push($facheMethod,'发车');
				array_push($facheMethod,$row['sumCount']);
				array_push($facheMethod,$row['weight']);
				array_push($chukuMethods,$facheMethod);
			}
			$query = "select sum(count) as sumCount, sum(count)*unitWeight as weight from sss_fache 
						where phase='销售' and substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				  		substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3)
					  ";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$directMethod = array();
				array_push($directMethod,'销售');
				array_push($directMethod,$row['sumCount']);
				array_push($directMethod,$row['weight']);
				array_push($chukuMethods,$directMethod);
			}
			$query = "select sum(count) as sumCount, sum(count)*unitWeight as weight from sss_fachuan 
						where substring(materialCode,1,5) ='{$shipNumber_subNumber}' and 
				  		substring(materialCode,9,3) = '$subNumber' 
						group by substring(materialCode,1,5),substring(materialCode,9,3)
					  ";
			DB::query($query);
			$result = DB::getResult();
			while(($row=$result->fetch_assoc())!=null){
				$fachuanMethod = array();
				array_push($fachuanMethod,'发船');
				array_push($fachuanMethod,$row['sumCount']);
				array_push($fachuanMethod,$row['weight']);
				array_push($chukuMethods,$fachuanMethod);
			}
		}
		$column = $exp->insertChuku($i,$column,$chuku,$chukuStorePlaces,$chukuDestinations,$chukuMethods);
	}
	$i++;
	
//	print_r($sum);
//	echo "<hr>";
//	print_r($manufatories);
//	echo "<hr>";
//	print_r($kuzhong);
//	echo "<hr>";
//	print_r($kuzhongStorePlaces);
//	echo "<hr>";
//	print_r($kuzhongManufactories);
//	echo "<hr>";
//	print_r($weidong);
//	echo "<hr>";
//	print_r($weidongManufactories);
//	echo "<hr>";
//	print_r($chuku);
//	echo "<hr>";
//	print_r($chukuStorePlaces);
//	echo "<hr>";
//	print_r($chukuDestinations);
//	echo "<hr>";
//	print_r($chukuMethods);
//	echo "<hr>";
}

$exp->export();
?>