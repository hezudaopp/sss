<?php

require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/DB.class.php');
require_once ('../includes/Verifier.class.php');
require_once ('../includes/Filter.class.php');

ini_set ( "max_execution_time", 0 );
ini_set ( "memory_limit", "128M" );

$smarty = SmartyManager::getSmarty ();
$smarty->assign ( 'advancedSearch', true );

if (isset ( $_GET ['materialCode'] )) {
	$smarty->assign ( 'materialCode', $_GET ['materialCode'] );
	$_GET ['materialCode'] = Filter::forDBInsertion ( trim ( $_GET ['materialCode'] ) );
}
if (isset ( $_GET ['thicknessFrom'] )) {
	$smarty->assign ( 'thicknessFrom', $_GET ['thicknessFrom'] );
	$_GET ['thicknessFrom'] = trim ( $_GET ['thicknessFrom'] );
}
if (isset ( $_GET ['thicknessTo'] )) {
	$smarty->assign ( 'thicknessTo', $_GET ['thicknessTo'] );
	$_GET ['thicknessTo'] = trim ( $_GET ['thicknessTo'] );
}
if (isset ( $_GET ['widthFrom'] )) {
	$smarty->assign ( 'widthFrom', $_GET ['widthFrom'] );
	$_GET ['widthFrom'] = trim ( $_GET ['widthFrom'] );
}
if (isset ( $_GET ['widthTo'] )) {
	$smarty->assign ( 'widthTo', $_GET ['widthTo'] );
	$_GET ['widthTo'] = trim ( $_GET ['widthTo'] );
}
if (isset ( $_GET ['lengthFrom'] )) {
	$smarty->assign ( 'lengthFrom', $_GET ['lengthFrom'] );
	$_GET ['lengthFrom'] = trim ( $_GET ['lengthFrom'] );
}
if (isset ( $_GET ['lengthTo'] )) {
	$smarty->assign ( 'lengthTo', $_GET ['lengthTo'] );
	$_GET ['lengthTo'] = trim ( $_GET ['lengthTo'] );
}
if (isset ( $_GET ['uploadTimeFrom'] )) {
	$smarty->assign ( 'uploadTimeFrom', $_GET ['uploadTimeFrom'] );
	$_GET ['uploadTimeFrom'] = trim ( $_GET ['uploadTimeFrom'] );
}
if (isset ( $_GET ['uploadTimeTo'] )) {
	$smarty->assign ( 'uploadTimeTo', $_GET ['uploadTimeTo'] );
	$_GET ['uploadTimeTo'] = trim ( $_GET ['uploadTimeTo'] );
}

if (isset ( $_GET ['faDateFrom'] )) {
	$smarty->assign ( 'faDateFrom', $_GET ['faDateFrom'] );
	$_GET ['faDateFrom'] = trim ( $_GET ['faDateFrom'] );
}
if (isset ( $_GET ['faDateTo'] )) {
	$smarty->assign ( 'faDateTo', $_GET ['faDateTo'] );
	$_GET ['faDateTo'] = trim ( $_GET ['faDateTo'] );
}

if (isset ( $_GET ['faNumber'] )) {
	$smarty->assign ( 'faNumber', $_GET ['faNumber'] );
	$_GET ['faNumber'] = trim ( $_GET ['faNumber'] );
}

if (isset ( $_GET ['shipsClassification'] )) {
	$smarty->assign ( 'shipsClassification', $_GET ['shipsClassification'] );
	$_GET ['shipsClassification'] = Filter::forDBInsertion ( trim ( $_GET ['shipsClassification'] ) );
}
if (isset ( $_GET ['material'] )) {
	$smarty->assign ( 'material', $_GET ['material'] );
	$_GET ['material'] = Filter::forDBInsertion ( trim ( $_GET ['material'] ) );
}

if (isset ( $_GET ['orderNumber'] )) {
	$smarty->assign ( 'orderNumber', $_GET ['orderNumber'] );
	$_GET ['orderNumber'] = Filter::forDBInsertion ( trim ( $_GET ['orderNumber'] ) );
}

if (isset ( $_GET ['orderSubitemNumber'] )) {
	$smarty->assign ( 'orderSubitemNumber', $_GET ['orderSubitemNumber'] );
	$_GET ['orderSubitemNumber'] = trim ( $_GET ['orderSubitemNumber'] );
}

if (isset ( $_GET ['filename'] )) {
	$smarty->assign ( 'filename', $_GET ['filename'] );
	$_GET ['filename'] = Filter::forDBInsertion ( trim ( $_GET ['filename'] ) );
}
if (isset ( $_GET ['remark'] )) {
	$smarty->assign ( 'remark', $_GET ['remark'] );
	$_GET ['remark'] = Filter::forDBInsertion ( trim ( $_GET ['remark'] ) );
}

if (isset ( $_GET ['unitPrice'] )) {
	$smarty->assign ( 'unitPrice', $_GET ['unitPrice'] );
	$_GET ['unitPrice'] = trim ( $_GET ['unitPrice'] );
}
if (isset ( $_GET ['batchNumber'] )) {
	$smarty->assign ( 'batchNumber', $_GET ['batchNumber'] );
	$_GET ['batchNumber'] = Filter::forDBInsertion ( trim ( $_GET ['batchNumber'] ) );
}
if (isset ( $_GET ['purchaseNumber'] )) {
	$smarty->assign ( 'purchaseNumber', $_GET ['purchaseNumber'] );
	$_GET ['purchaseNumber'] = Filter::forDBInsertion ( trim ( $_GET ['purchaseNumber'] ) );
}
if (isset ( $_GET ['destination'] )) {
	$smarty->assign ( 'destination', $_GET ['destination'] );
	$_GET ['destination'] = Filter::forDBInsertion ( trim ( $_GET ['destination'] ) );
}
if (isset ( $_GET ['storePlace'] )) {
	$smarty->assign ( 'storePlace', $_GET ['storePlace'] );
	$_GET ['storePlace'] = Filter::forDBInsertion ( trim ( $_GET ['storePlace'] ) );
}
if(isset($_GET['certificateNumber'])){
	$smarty->assign('certificateNumber', $_GET['certificateNumber']);
	$_GET['certificateNumber'] = Filter::forDBInsertion(trim($_GET['certificateNumber']));
}
if(isset($_GET['checkoutBatch'])){
	$smarty->assign('checkoutBatch', $_GET['checkoutBatch']);
	$_GET['checkoutBatch'] = Filter::forDBInsertion(trim($_GET['checkoutBatch']));
}
if(isset($_GET['materialNumber'])){
	$smarty->assign('materialNumber', $_GET['materialNumber']);
	$_GET['materialNumber'] = Filter::forDBInsertion(trim($_GET['materialNumber']));
}
if(isset($_GET['consignmentBatch'])){
	$smarty->assign('consignmentBatch', $_GET['consignmentBatch']);
	$_GET['consignmentBatch'] = Filter::forDBInsertion(trim($_GET['consignmentBatch']));
}
if (isset ( $_GET ['sequenceNumber'] )) {
	$smarty->assign ( 'sequenceNumber', $_GET ['sequenceNumber'] );
	$_GET ['sequenceNumber'] = Filter::forDBInsertion ( trim ( $_GET ['sequenceNumber'] ) );
}
if (isset ( $_GET ['manufactory'] )) {
	$smarty->assign ( 'manufactory', $_GET ['manufactory'] );
	$_GET ['manufactory'] = Filter::forDBInsertion ( trim ( $_GET ['manufactory'] ) );
}
if (isset ( $_GET ['ruku'] )) {
	$smarty->assign ( 'ruku', $_GET ['ruku'] );
	$_GET ['ruku'] = Filter::forDBInsertion ( trim ( $_GET ['ruku'] ) );
}
if (isset ( $_GET ['chuku'] )) {
	$smarty->assign ( 'chuku', $_GET ['chuku'] );
	$_GET ['chuku'] = Filter::forDBInsertion ( trim ( $_GET ['chuku'] ) );
}
if (isset ( $_GET ['sale'] )) {
	$smarty->assign ( 'sale', $_GET ['sale'] );
	$_GET ['sale'] = Filter::forDBInsertion ( trim ( $_GET ['sale'] ) );
}
if (isset ( $_GET ['main'] )) {
	$smarty->assign ( 'main', $_GET ['main'] );
	$_GET ['main'] = Filter::forDBInsertion ( trim ( $_GET ['main'] ) );
}
if(isset($_GET['checkout'])){
	$smarty->assign('checkout', $_GET['checkout']);
	$_GET['checkout'] = Filter::forDBInsertion(trim($_GET['checkout']));
}

if(isset($_GET['year'])){
	$_GET['year'] = Filter::forDBInsertion(trim($_GET['year']));
}

$wheres = array ();

if (! empty ( $_GET ['materialCode'] )) {
	array_push ( $wheres, "materialCode like '%{$_GET['materialCode']}%'" );
}

if (! empty ( $_GET ['thicknessFrom'] ) && Verifier::isNUMBER ( $_GET ['thicknessFrom'] )) {
	$thicknessFrom = doubleval ( $_GET ['thicknessFrom'] );
	array_push ( $wheres, "thickness >= {$thicknessFrom}" );
}

if (! empty ( $_GET ['thicknessTo'] ) && Verifier::isNUMBER ( $_GET ['thicknessTo'] )) {
	$thicknessTo = doubleval ( $_GET ['thicknessTo'] );
	array_push ( $wheres, "thickness <= {$thicknessTo}" );
}

if (! empty ( $_GET ['widthFrom'] ) && Verifier::isNUMBER ( $_GET ['widthFrom'] )) {
	$widthFrom = doubleval ( $_GET ['widthFrom'] );
	array_push ( $wheres, "width >= {$widthFrom}" );
}

if (! empty ( $_GET ['widthTo'] ) && Verifier::isNUMBER ( $_GET ['widthTo'] )) {
	$widthTo = doubleval ( $_GET ['widthTo'] );
	array_push ( $wheres, "width <= {$widthTo}" );
}

if (! empty ( $_GET ['lengthFrom'] ) && Verifier::isNUMBER ( $_GET ['lengthFrom'] )) {
	$lengthFrom = doubleval ( $_GET ['lengthFrom'] );
	array_push ( $wheres, "length >= {$lengthFrom}" );
}

if (! empty ( $_GET ['lengthTo'] ) && Verifier::isNUMBER ( $_GET ['lengthTo'] )) {
	$lengthTo = doubleval ( $_GET ['lengthTo'] );
	array_push ( $wheres, "length <= {$lengthTo}" );
}

if (! empty ( $_GET ['uploadTimeFrom'] ) && Verifier::isTIME ( $_GET ['uploadTimeFrom'] )) {
	$time = date ( 'Y/m/d H:i:s', strtotime ( $_GET ['uploadTimeFrom'] ) );
	array_push ( $wheres, "uploadTime >= '$time'" );
}

if (! empty ( $_GET ['uploadTimeTo'] ) && Verifier::isTIME ( $_GET ['uploadTimeTo'] )) {
	$time = date ( 'Y/m/d H:i:s', strtotime ( $_GET ['uploadTimeTo'] ) );
	array_push ( $wheres, "uploadTime <= '$time'" );
}

if (! empty ( $_GET ['shipsClassification'] )) {
	array_push ( $wheres, "shipsClassification = '{$_GET['shipsClassification']}'" );
}

if (! empty ( $_GET ['material'] )) {
	array_push ( $wheres, "material = '{$_GET['material']}'" );
}

if (! empty ( $_GET ['filename'] )) {
	array_push ( $wheres, "filename like '%{$_GET['filename']}%'" );
}

if (! empty ( $_GET ['remark'] )) {
	array_push ( $wheres, "(remark1 like '%{$_GET['remark']}%' or remark2 like '%{$_GET['remark']}%'
						  or remark3 like '%{$_GET['remark']}%' or remark4 like '%{$_GET['remark']}%' or remark5 like '%{$_GET['remark']}%')" );
}

if (! empty ( $_GET ['orderNumber'] )) {
	array_push ( $wheres, "orderNumber like '%{$_GET['orderNumber']}%'" );
}

if (! empty ( $_GET ['orderSubitemNumber'] )) {
	array_push ( $wheres, "orderSubitemNumber = '{$_GET['orderSubitemNumber']}'" );
}

if (! empty ( $_GET ['unitPrice'] )) {
	array_push ( $wheres, "unitPrice = '{$_GET['unitPrice']}'" );
}

if (! empty ( $_GET ['batchNumber'] )) {
	array_push ( $wheres, "batchNumber like '%{$_GET['batchNumber']}%'" );
}

if (! empty ( $_GET ['purchaseNumber'] )) {
	array_push ( $wheres, "purchaseNumber like '%{$_GET['purchaseNumber']}%'" );
}

if (! empty ( $_GET ['destination'] )) {
	array_push ( $wheres, "destination = '{$_GET['destination']}'" );
}

if (! empty ( $_GET ['storePlace'] )) {
	array_push ( $wheres, "storePlace = '{$_GET['storePlace']}'" );
}

if(!empty($_GET['certificateNumber'])){
	array_push($wheres, "certificateNumber like '%{$_GET['certificateNumber']}%'");
}

if(!empty($_GET['checkoutBatch'])){
	array_push($wheres, "checkoutBatch like '%{$_GET['checkoutBatch']}%'");
}

if(!empty($_GET['materialNumber'])){
	array_push($wheres, "materialNumber like '%{$_GET['materialNumber']}%'");
}

if(!empty($_GET['consignmentBatch'])){
	array_push($wheres, "consignmentBatch like '%{$_GET['consignmentBatch']}%'");
}
if (empty ( $_GET ['ruku'] ) and empty ( $_GET ['chuku'] ) and empty ( $_GET ['sale'] ) and empty ( $_GET ['main'] )) {
	header ( 'Location: advancedSearch.html' );
}

if(!empty($_GET['checkout'])){
	array_push($wheres,"certificateNumber is not null");
}

if(!empty($_GET['year'])){
	array_push($wheres,"filename like '%{$_GET['year']}%'");
}


$mainTable = true;

if (! empty ( $_GET ['faDateFrom'] ) or ! empty ( $_GET ['faDateTo'] ) or ! empty ( $_GET ['faNumber'] )) {
	$mainTable = false;
}

if ($mainTable) {
	if (isset ( $_GET ['main'] )) {
		$mainWheres = $wheres;
		
		if (! empty ( $_GET ['sequenceNumber'] )) {
			array_push ( $mainWheres, "sequenceNumber like '%{$_GET['sequenceNumber']}%'" );
		}
		
		if (! empty ( $_GET ['manufactory'] )) {
			array_push ( $mainWheres, "manufactory like '%{$_GET['manufactory']}%'" );
		}
		
		$whereStr = join ( ' and ', $mainWheres );
		//	var_dump($whereStr);
		

		if (count ( $mainWheres ) == 0) {
			$query = "select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, thickness, width, length, 
		sumCount, coalesce(rukuCount, 0) as rukuCount, coalesce(directCount, 0) as directCount, coalesce(chukuCount, 0) as chukuCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount,
		unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename, 
		unitPrice, batchNumber, purchaseNumber, destination, storePlace, checkoutBatch, certificateNumber, materialNumber, consignmentBatch
from
		(
			(
			select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, width, thickness, length, 
			unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount, checkoutBatch, certificateNumber, materialNumber, consignmentBatch
			from sss_main";
		} else {
			$query = "select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, thickness, width, length, 
		sumCount, coalesce(rukuCount, 0) as rukuCount, coalesce(directCount, 0) as directCount, coalesce(chukuCount, 0) as chukuCount, (coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount,
		unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename,
		unitPrice, batchNumber, purchaseNumber, destination, storePlace, checkoutBatch, certificateNumber, materialNumber, consignmentBatch
from
		(
			(
			select id, sequenceNumber, materialCode, manufactory, shipsClassification, material, width, thickness, length, 
			unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5, uploadTime, filename,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount, checkoutBatch, certificateNumber, materialNumber, consignmentBatch
			from sss_main where $whereStr";
		}
		$query .= " group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

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
			select materialCode, orderNumber, orderSubitemNumber, sum(halfChukuCount) as chukuCount
			from
				(
					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
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
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as directCount
			from sss_fache
			where phase='销售'
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		order by kuzhongCount,materialCode, orderNumber, orderSubitemNumber, thickness, width, length;";
		DB::query ( $query );
		
		$ids = array ();
		$sequenceNumbers = array ();
		$manufactorys = array ();
		$materialCodes = array ();
		$shipsClassifications = array ();
		$materials = array ();
		$thicknesses = array ();
		$widths = array ();
		$lengths = array ();
		$sumCounts = array ();
		$unRukuCounts = array ();
		$kuzhongCounts = array ();
		$soldCounts = array ();
		$unitWeights = array ();
		$weights = array ();
		$remarks = array ();
		$uploadTimes = array ();
		$filenames = array ();
		$orderNumbers = array ();
		$orderSubitemNumbers = array ();
		$unitPrices = array ();
		$batchNumbers = array ();
		$purchaseNumbers = array ();
		$destinations = array ();
		$storePlaces = array ();
		$dates = array ();
		$faNumbers = array ();
		$checkoutBatchs = array();
		$certificateNumbers = array();
		$materialNumbers = array();
		$consignmentBatchs = array();
		
		$result = DB::getResult ();
		
		$i = 0;
		$dates2 = array ();
		$faNumbers2 = array ();
		while ( $row = $result->fetch_assoc () ) {
			
			$sold = $row ['chukuCount'] + $row ['directCount'];
			$kuzhong = $row['kuzhongCount'];
			$unRuku = $row ['sumCount'] - $row ['directCount'] - $row ['rukuCount'];
			
			array_push ( $sequenceNumbers, $row ['sequenceNumber'] );
			array_push ( $manufactorys, $row ['manufactory'] );
			array_push ( $materialCodes, $row ['materialCode'] );
			array_push ( $shipsClassifications, $row ['shipsClassification'] );
			array_push ( $materials, $row ['material'] );
			array_push ( $thicknesses, $row ['thickness'] );
			array_push ( $widths, $row ['width'] );
			array_push ( $lengths, $row ['length'] );
			array_push ( $sumCounts, intval ( $row ['sumCount'] ) );
			array_push ( $unRukuCounts, $unRuku );
			array_push ( $kuzhongCounts, $kuzhong );
			array_push ( $soldCounts, $sold );
			array_push ( $unitWeights, $row ['unitWeight'] );
			array_push ( $weights, $row ['weight'] );
			if (! empty ( $row ['remark2'] )) {
				$row ['remark1'] = '1. ' . $row ['remark1'] . '<br />2. ' . $row ['remark2'];
			}
			if (! empty ( $row ['remark3'] )) {
				$row ['remark1'] .= '<br />3. ' . $row ['remark3'];
			}
			if (! empty ( $row ['remark4'] )) {
				$row ['remark1'] .= '<br />4. ' . $row ['remark4'];
			}
			if (! empty ( $row ['remark5'] )) {
				$row ['remark1'] .= '<br />5. ' . $row ['remark5'];
			}
			array_push ( $remarks, $row ['remark1'] );
			array_push ( $uploadTimes, $row ['uploadTime'] );
			array_push ( $filenames, $row ['filename'] );
			array_push ( $orderNumbers, $row ['orderNumber'] );
			array_push ( $orderSubitemNumbers, $row ['orderSubitemNumber'] );
			array_push ( $unitPrices, $row ['unitPrice'] );
			array_push ( $batchNumbers, $row ['batchNumber'] );
			array_push ( $purchaseNumbers, $row ['purchaseNumber'] );
			array_push ( $destinations, $row ['destination'] );
			array_push ( $storePlaces, $row ['storePlace'] );
			array_push ( $certificateNumbers, $row['certificateNumber']);
			array_push ( $checkoutBatchs, $row['checkoutBatch']);
			array_push ( $materialNumbers, $row['materialNumber']);
			array_push ( $consignmentBatchs, $row['consignmentBatch']);
			array_push ( $ids, $row ['id'] );
			
			$dates1 = array ();
			$faNumbers1 = array ();
			$sql_fache = "select facheDate, facheNumber,phase from sss_fache 
					where materialCode = '" . $row ['materialCode'] . "' and orderNumber = '" . $row ['orderNumber'] . "' and orderSubitemNumber = '" . $row ['orderSubitemNumber'] . "'";
			DB::query ( $sql_fache );
			$result1 = DB::getResult ();
			while ( $row1 = $result1->fetch_assoc () ) {
				if (! empty ( $row1 ['facheDate'] )) {
					if ($row1['phase']=='入库') {
						$phaseWords = '入库:<br>';
					}
					elseif ($row1['phase']=='出库') {
						$phaseWords = '出库:<br>';
					}
					else {
						$phaseWords = '销售:<br>';
					}
					array_push ( $dates1, $phaseWords.$row1 ['facheDate'] );
				}
				if (! empty ( $row1 ['facheNumber'] )) {
					array_push ( $faNumbers1, '车号<br>'.$row1 ['facheNumber'] );
				}
			}
			$sql_fachuan = "select fachuanDate, fachuanNumber from sss_fachuan 
					where materialCode = '" . $row ['materialCode'] . "' and orderNumber = '" . $row ['orderNumber'] . "' and orderSubitemNumber = '" . $row ['orderSubitemNumber'] . "'";
			DB::query ( $sql_fachuan );
			$result2 = DB::getResult ();
			while ( $row2 = $result2->fetch_assoc () ) {
				if (! empty ( $row2 ['fachuanDate'] )) {
					array_push ( $dates1, '发船:<br>'.$row2 ['fachuanDate'] );
				}
				if (! empty ( $row2 ['fachuanNumber'] )) {
					array_push ( $faNumbers1, '船号<br>'.$row2 ['fachuanNumber'] );
				}
			}
			$dates2 [$i] = $dates1;
			$faNumbers2 [$i] = $faNumbers1;
			$i ++;
		}
		
		$smarty->assign ( array ('main_sequenceNumber' => $sequenceNumbers, 'main_date' => $dates2, 'main_faNumber' => $faNumbers2, 'main_manufactory' => $manufactorys, 'main_materialCode' => $materialCodes, 'main_shipsClassification' => $shipsClassifications, 'main_material' => $materials, 'main_thickness' => $thicknesses, 'main_width' => $widths, 'main_length' => $lengths, 'main_sumCount' => $sumCounts, 'main_unRukuCount' => $unRukuCounts, 'main_kuzhongCount' => $kuzhongCounts, 'main_soldCount' => $soldCounts, 'main_unitWeight' => $unitWeights, 'main_weight' => $weights, 'main_remark' => $remarks, 'main_uploadTime' => $uploadTimes, 'main_filename' => $filenames, 'main_orderNumber' => $orderNumbers, 'main_orderSubitemNumber' => $orderSubitemNumbers, 'main_unitPrice' => $unitPrices, 'main_batchNumber' => $batchNumbers, 'main_purchaseNumber' => $purchaseNumbers, 'main_destination' => $destinations, 'main_storePlace' => $storePlaces, 'main_ids' => $ids, 'main_checkoutBatch' => $checkoutBatchs, 'main_certificateNumber' => $certificateNumbers, 'main_materialNumber' => $materialNumbers, 'main_consignmentBatch' => $consignmentBatchs ) );
		$smarty->assign('main_checkout', $_GET['checkout']);
	}

}

$smarty->display ( 'simpleTableStat.html' );
?>