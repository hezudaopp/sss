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

	$query = "
	select materialCode, mcInfoTable.shipsClassification, material,
	mcInfoTable.thickness as thi, mcInfoTable.width as wid, mcInfoTable.length as len,
	rukuCountTable.thickness as th, rukuCountTable.width as wi, rukuCountTable.length as le, unitWeight,
	(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount, orderNumber, orderSubitemNumber,
	unitPrice,filename,storePlace,sequenceNumber,manufactory,batchNumber,purchaseNumber,materialNumber,consignmentBatch,
	facheDate, facheNumber, remark1,remark2,remark3,remark4,remark5
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length,
			unitWeight, orderNumber, orderSubitemNumber,
			unitPrice,filename,storePlace,sequenceNumber,manufactory,batchNumber,purchaseNumber,materialNumber,consignmentBatch,
   			remark1,remark2,remark3,remark4,remark5
			from sss_main
			where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
			and (materialCode, orderNumber, orderSubitemNumber) in 
				(select distinct materialCode, orderNumber, orderSubitemNumber from sss_fache where phase = '入库')
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as mcInfoTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber,
			shipsClassification, width, thickness, length, facheDate, facheNumber,
			sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
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
					where phase = '出库' and (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
					group by materialCode, orderNumber, orderSubitemNumber
					)
					union all
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fachuan
					where (certificateNumber is null or certificateNumber ='') and (checkoutBatch is null or checkoutBatch = '')
					group by materialCode, orderNumber, orderSubitemNumber
					)
				) as newCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)
		);";
	DB::query($query);

	$result = DB::getResult();
	/*
	select materialCode, shipsClassification, material, thickness, width, length, unitWeight,
	(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount
	*/
	
		
		$worksheet = &$exp->getWorkbook()->addWorksheet(iconv('utf-8', 'gbk', 'sheet1'));
		$formatRed = &$exp->getWorkbook()->addFormat(array('bgColor' => 'black','color' => 'red'));
		$format = &$exp->getWorkbook()->addFormat(array('color' => 'black'));
		$x = 0;
		$ths = array('入库日期','车号', '材料代码', '船级', '材质', '厚', '宽', '长','数量','单重', '重量', '订单号', '订单子项号',
		 '受订单价','购单号','批号','物料号','发货批次', '库存地', '批次', '生产厂商', '备注', '上传文件名');
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		$y = 1;
		while(($row = $result->fetch_assoc())!=null){
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
				
				if($row['th']!=$row['thi'] || $row['wi']!=$row['wid'] || $row['le']!=$row['len']){
					$row['unitWeight'] = round($row['th']*$row['wi']*$row['le']*7.85/1000000000,3);
				}
				$col = array($row['facheDate'],$row['facheNumber'],$row['materialCode'], $row['shipsClassification'], $row['material'], $row['th'], $row['wi'],$row['le'],
					         $row['kuzhongCount'],$row['unitWeight'],$row['unitWeight']*$row['kuzhongCount'], $row['orderNumber'], $row['orderSubitemNumber'], 
					          $row['unitPrice'],$row['purchaseNumber'],$row['batchNumber'],$row['materialNumber'],$row['consignmentBatch'],$row['storePlace'],$row['sequenceNumber'],$row['manufactory'],
					          $row['remark1'],$row['filename']);
				if($row['th']!=$row['thi'] || $row['wi']!=$row['wid'] || $row['le']!=$row['len'] || $row['kuzhongCount']<0){
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
		$exp->export();

//加入受订单价，上传文件名，规格最好按实际入库的尺寸计算，备注，库存地，批次，生产厂家，单重与重量要实际计算，批号购单号	
	
}catch(Exception $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}
?>

