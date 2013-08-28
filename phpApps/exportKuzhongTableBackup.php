<?php

//apd_set_pprof_trace();
require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/SimpleTableExporter.class.php');

$filename = '库中_'.date('YmdHis').'.xls';

ini_set("max_execution_time",0);

try{
	$exp = new SimpleTableExporter($filename);

	/*$query = "
	select materialCode, shipsClassification, material, thickness, width, length, unitWeight,
	(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, orderNumber, orderSubitemNumber,
	unitPrice,filename,storePlace,sequenceNumber,manufactory,batchNumber,purchaseNumber,
    remark1,remark2,remark3,remark4,remark5
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length,
			 unitWeight, orderNumber, orderSubitemNumber,
			 unitPrice,filename,storePlace,sequenceNumber,manufactory,batchNumber,purchaseNumber,
   			remark1,remark2,remark3,remark4,remark5
			from sss_main
			where (materialCode, orderNumber, orderSubitemNumber) in 
				(select distinct materialCode, orderNumber, orderSubitemNumber from sss_fache where phase = '入库')
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as mcInfoTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库'
			group by materialCode, orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join
			(
			select materialCode, sum(`newCount`) as chukuCount, orderNumber, orderSubitemNumber
			from
				(
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					union all
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fachuan
					group by materialCode, orderNumber, orderSubitemNumber
					)
				) as newCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)
		)
		LIMIT 0,30;";*/

	$query = "
	select materialCode, mcInfoTable.shipsClassification, material,
	mcInfoTable.thickness as thi, mcInfoTable.width as wid, mcInfoTable.length as len,
	rukuCountTable.thickness as th, rukuCountTable.width as wi, rukuCountTable.length as le, unitWeight,
	(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, orderNumber, orderSubitemNumber,
	unitPrice,filename,storePlace,sequenceNumber,manufactory,batchNumber,purchaseNumber,
    remark1,remark2,remark3,remark4,remark5
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length,
			unitWeight, orderNumber, orderSubitemNumber,
			unitPrice,filename,storePlace,sequenceNumber,manufactory,batchNumber,purchaseNumber,
   			remark1,remark2,remark3,remark4,remark5
			from sss_main
			where (materialCode, orderNumber, orderSubitemNumber) in 
				(select distinct materialCode, orderNumber, orderSubitemNumber from sss_fache where phase = '入库')
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as mcInfoTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber,
			shipsClassification, width, thickness, length,
			sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库'
			group by materialCode, orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join
			(
			select materialCode, sum(`newCount`) as chukuCount, orderNumber, orderSubitemNumber
			from
				(
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					union all
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fachuan
					group by materialCode, orderNumber, orderSubitemNumber
					)
				) as newCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)
		)
		LIMIT 0,30;";
	DB::query($query);
	
	$mcs = array();
	$counts = array();
	$shipsClassifications = array();
	$materials = array();
	$thicknesses = array();
	$widths = array();
	$lengths = array();
	$unitWeights = array();
	$orderNumbers = array();
	$orderSubitemNumbers = array();
	$unitPrices = array();
	$filenames = array();
	$storePlaces = array();
	$sequenceNumbers = array();
	$manufactorys = array();
	$batchNumbers = array();
	$purchaseNumbers =array();
    $remarks = array();

	$result = DB::getResult();
	/*
	select materialCode, shipsClassification, material, thickness, width, length, unitWeight,
	(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount
	*/
	
		
		/*$worksheet = &$exp->getWorkbook()->addWorksheet(iconv('utf-8', 'gbk', $sheetname));
		$formatRed = &$exp->getWorkbook()->addFormat(array('color' => 'red'));
		$format = &$exp->getWorkbook()->addFormat(array('color' => 'black'));
		$x = 0;
		$ths = array('材料代码','船级', '材质', '厚', '宽', '长', '单重', '订单号', '订单子项号', '库中',
					 '上传文件名','批次','生产厂商','受订单价','库存地','批号','购单号','备注');
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		$y = 0;
		while($row = $result->fetch_assoc()){
			if($row['kuzhongCount']!=0){
				$x = 0;
				if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
				}
				if(!empty($row['remark3'])){
						$row['remark1'] .= '<br />3. '.$row['remark3'];
				}
				if(!empty($row['remark4'])){
						$row['remark1'] .= '<br />4. '.$row['remark4'];
				}
				if(!empty($row['remark5'])){
						$row['remark1'] .= '<br />5. '.$row['remark5'];
				}
				
				$col = array($row['materialCode'], $row['shipsClassification'], $row['material'], $row['th'], $row['wi'],$row['le'],
					         $row['unitWeight'], $row['orderNumber'], $row['orderSubitemNumber'], $row['kuzhongCount'], $row['filename'],
					         $row['sequenceNumber'], $row['manufactory'], $row['unitPrice'], $row['storePlace'], $row['batchNumber'], 
					         $row['purchaseNumber'],$row['remark1']);
				if($row['th']!=$row['thi'] || $row['wi']!=$row['wid'] || $row['le']!=$row['len']){
					foreach($col as $td){
						$td = iconv('utf-8', 'gbk', $td);
						$worksheet->write($y, $x, $td, $formatRed);
						$x++;
					}	         
				}
				else {
					foreach($col as $td){
						$td = iconv('utf-8', 'gbk', $td);
						$worksheet->write($y, $x, $td, $format);
						$x++;
					}	
				}
				$y++;
			}
		}
		$exp->export();*/
	while($row = $result->fetch_assoc()){
		if($row['kuzhongCount'] != 0){
			array_push($mcs, $row['materialCode']);
			array_push($shipsClassifications, $row['shipsClassification']);
			array_push($materials, $row['material']);
			array_push($thicknesses, $row['th']);
			array_push($widths, $row['wi']);
			array_push($lengths,$row['le']);
			array_push($unitWeights, $row['unitWeight']);
			array_push($counts, $row['kuzhongCount']);
			array_push($orderNumbers, $row['orderNumber']);
			array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($unitPrices, $row['unitPrice']);
			array_push($filenames, $row['filename']);
			array_push($storePlaces, $row['storePlace']);
			array_push($sequenceNumbers, $row['sequenceNumber']);
			array_push($manufactorys, $row['manufactory']);
			array_push($batchNumbers, $row['batchNumber']);
			array_push($purchaseNumbers, $row['purchaseNumber']);
			if(!empty($row['remark2'])){
				$row['remark1'] = '1. '.$row['remark1'].'<br />2. '.$row['remark2'];
			}
			if(!empty($row['remark3'])){
					$row['remark1'] .= '<br />3. '.$row['remark3'];
			}
			if(!empty($row['remark4'])){
					$row['remark1'] .= '<br />4. '.$row['remark4'];
			}
			if(!empty($row['remark5'])){
					$row['remark1'] .= '<br />5. '.$row['remark5'];
			}
			array_push($remarks, $row['remark1']);
		}
	}
	
//加入受订单价，上传文件名，规格最好按实际入库的尺寸计算，备注，库存地，批次，生产厂家，单重与重量要实际计算，批号购单号	
	$exp->insertCustomData(array($mcs, 
								 $shipsClassifications, 
								 $materials, 
								 $thicknesses, 
								 $widths, 
								 $lengths, 
								 $unitWeights, 
								 $orderNumbers, 
								 $orderSubitemNumbers, 
								 $counts,
								 $filenames,
								 $sequenceNumbers,
								 $manufactorys,
								 $unitPrices,
								 $storePlaces,
								 $batchNumbers,
								 $purchaseNumbers,
								 $remarks), 
						   array('材料代码', 
						   		 '船级', 
						   		 '材质', 
						   		 '厚', 
						   		 '宽', 
						   		 '长', 
						   		 '单重', 
						   		 '订单号', 
						   		 '订单子项号', 
						   		 '库中',						   	
						         '上传文件名',						   		 
						   		 '批次',
						   		 '生产厂商',
						   		 '受订单价',
						   		 '库存地',
						   		 '批号',
						   		 '购单号',
						   		 '备注'), 
						   	'sheet1');
	$exp->export();
	
}catch(Exception $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}
?>

