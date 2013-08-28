<?php
require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/SimpleTableExporter.class.php');
require_once('../includes/functions.inc.php');

$filename = '未动_'.date('YmdHis').'.xls';
ini_set('max_execution_time', 0);
try{
	$exp = new SimpleTableExporter($filename);
	beginTransaction();
	$query = "select materialCode, sequenceNumber, manufactory, shipsClassification, material, thickness, width, length, unitWeight,
	sumCount,rukuCount,directCount,(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
	orderNumber, orderSubitemNumber,unitPrice, batchNumber, purchaseNumber, destination, storePlace,filename,uploadTime,
	materialNumber,consignmentBatch
from
		(
			(
			select materialCode, sequenceNumber, manufactory, sum(`count`) as sumCount, shipsClassification, material,
				 width, thickness, length, unitWeight, orderNumber, orderSubitemNumber,uploadTime,
				 unitPrice, batchNumber, purchaseNumber, destination, storePlace,filename,materialNumber,consignmentBatch
			from sss_main
			where certificateNumber is null or certificateNumber = ''
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as sumCountTable

		left join

			(
			select materialCode, sum(`count`) as rukuCount, orderNumber, orderSubitemNumber
			from sss_fache
			where phase = '入库' and (certificateNumber is null or certificateNumber = '')
			group by materialCode, orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)
		left join

			(
			select materialCode, sum(`count`) as directCount, orderNumber, orderSubitemNumber
			from sss_fache
			where phase='销售' and (certificateNumber is null or certificateNumber = '')
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		);";

	DB::query($query);
	$result = DB::getResult();
	
	$worksheet = &$exp->getWorkbook()->addWorksheet(iconv('utf-8', 'gbk', 'sheet1'));
	$formatRed = &$exp->getWorkbook()->addFormat(array('bgColor' => 'black','color' => 'red'));
	$format = &$exp->getWorkbook()->addFormat(array('color' => 'black'));
	$x = 0;
	$ths = array('材料代码', '文件名','上传时间', '批次', '生产厂家', '船级', '材质', '厚', '宽', '长','总数量','入库数量','销售数量','未动数量', '单重', '订单号', '订单子项号','受订单价', '批号', '购单号', '目的地', '库存地', '物料号','发货批次');
	foreach($ths as $th){
		$th = iconv('utf-8', 'gbk', $th);
		$worksheet->write(0, $x, $th, $format);
		$x++;
	}
	$x = 0;
	$y = 1;
	while(($row = $result->fetch_assoc())!=null){
		if($row['unRukuCount'] == 0){
			continue;
		}
		$x = 0;
		$col = array($row['materialCode'], $row['filename'],$row['uploadTime'], $row['sequenceNumber'],
		 $row['manufactory'], $row['shipsClassification'], $row['material'], $row['thickness'],
		 $row['width'], $row['length'], $row['sumCount'], $row['rukuCount'], $row['directCount'],
		 $row['unRukuCount'], $row['unitWeight'], $row['orderNumber'], $row['orderSubitemNumber'], 
		 $row['unitPrice'], $row['batchNumber'], $row['purchaseNumber'], $row['destination'], 
		 $row['storePlace'], $row['materialNumber'],$row['consignmentBatch']);

		foreach($col as $td){
			$td = iconv('utf-8', 'gbk', $td);
			if($row['unRukuCount']<0){
				$worksheet->write($y, $x, $td, $formatRed);
			}else{
				$worksheet->write($y, $x, $td, $format);
			}
			$x++;
		}	
		$y++;
	}

	$exp->export();
	commit();
}catch(Exception $e){
	$smarty = SmartyManager::getSmarty();
	$smarty->assign('errMsg', $e);
	$smarty->assign('errTitle', '查询数据库时出现错误');
	$smarty->display('error.html');
	die();
}

?>