<?php
require_once('../includes/DB.class.php');
require_once('../includes/SmartyManager.class.php');
require_once('../includes/exceptions/AppExceptions.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/SimpleTableExporter.class.php');
require_once('../includes/functions.inc.php');

$filename = '未完成_'.date('YmdHis').'.xls';
ini_set('max_execution_time', 0);
try{
	$exp = new SimpleTableExporter($filename);
	beginTransaction();
	
	$query = "select materialCode, sequenceNumber, manufactory, shipsClassification, material, thickness, width, length, unitWeight,
	(sumCount - coalesce(chukuCount, 0) - coalesce(directCount, 0)) as unfinishedCount, orderNumber, orderSubitemNumber,
	unitPrice, batchNumber, materialNumber, consignmentBatch, purchaseNumber, destination, storePlace
from
		(
			(
			select materialCode, sequenceNumber, manufactory, sum(`count`) as sumCount, shipsClassification, material,
				 width, thickness, length, unitWeight, orderNumber, orderSubitemNumber,
				 unitPrice, batchNumber, materialNumber, consignmentBatch, purchaseNumber, destination, storePlace
				 from sss_main
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as sumCountTable

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
				)as nativeChukuCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join

			(
			select materialCode, sum(`count`) as directCount, orderNumber, orderSubitemNumber
			from sss_fache
			where phase='销售'
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		);";
	$mcs = array();
	$sequenceNumbers = array();
	$manufactorys = array();
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
	$batchNumbers = array();
	$materialNumbers = array();
	$consignmentBatchs = array();
	$purchaseNumbers = array();
	$destinations = array();
	$storePlaces = array();

	DB::query($query);
	$result = DB::getResult();
	while($row = $result->fetch_assoc()){
		if($row['unfinishedCount'] == 0){
			continue;
		}
		array_push($mcs, $row['materialCode']);
		array_push($sequenceNumbers, $row['sequenceNumber']);
		array_push($manufactorys, $row['manufactory']);
		array_push($shipsClassifications, $row['shipsClassification']);
		array_push($counts, $row['unfinishedCount']);
		array_push($materials, $row['material']);
		array_push($thicknesses, $row['thickness']);
		array_push($widths, $row['width']);
		array_push($lengths, $row['length']);
		array_push($unitWeights, $row['unitWeight']);
		array_push($orderNumbers, $row['orderNumber']);
		array_push($orderSubitemNumbers, $row['orderSubitemNumber']);
		array_push($unitPrices,$row['unitPrice']);
		array_push($batchNumbers,$row['batchNumber']);
		array_push($materialNumbers,$row['materialNumber']);
		array_push($consignmentBatchs,$row['consignmentBatch']);
		array_push($purchaseNumbers,$row['purchaseNumber']);
		array_push($destinations,$row['destination']);
		array_push($storePlaces,$row['storePlace']);
	}

	$exp->insertCustomData(array($mcs, $sequenceNumbers, $manufactorys, $shipsClassifications, $materials, $thicknesses, $widths, $lengths, $unitWeights,$orderNumbers, $orderSubitemNumbers, $unitPrices, $batchNumbers, $materialNumbers, $consignmentBatchs, $purchaseNumbers, $destinations, $storePlaces,  $counts), 
							array('材料代码', '批次', '生产厂家', '船级', '材质', '厚', '宽', '长', '单重','订单号', '订单子项号', '受订单价', '批号','物料号','发货批次', '购单号', '目的地', '库存地' , '未完成'), 'sheet1');
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