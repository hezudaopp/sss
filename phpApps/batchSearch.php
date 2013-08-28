<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');

$smarty = SmartyManager::getSmarty();
$smarty->assign('batchSearch', true);

$sequenceNumbers = array();
$manufactorys = array();
$materialCodes = array();
$shipsClassifications = array();
$materials = array();
$thicknesses = array();
$widths = array();
$lengths = array();
$counts = array();
$unitWeights = array();
$weights = array();
$remarks = array();
$uploadTimes = array();
$filenames = array();
$orderNumbers = array();
$orderSubitemNumbers = array();
$colors = array();


$materialCodes = array('A', 'A', 'A', 'A', 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'C', 'C');
$counts = array(5,4,6,4,2,3,10,8,3,5,18,10,10,11);
$filenames = array('zongbiao.xls','zongbiao.xls','facheruku.xls','fachechuku.xls','fachuanchuku.xls','zhijiexiaoshou.xls',
'zongbiao.xls','facheruku.xls','fachechuku.xls','fachuanchuku.xls','zongbiao.xls','facheruku.xls','fachechuku.xls',
'zhijiexiaoshou.xls');
$types =  array('总表','总表','入库','发车出库','发船出库','直接销售','总表','入库',
		'发车出库','发船出库','总表','入库','发车出库','直接销售'); 
$shipsClassifications = array('neu','neu', '82290', '82290', '82290', '82290', 'neu', '82290',
		'82290', '82290', 'neu', '82290', '82290', '82290');

for($i = 0; $i < 14; $i++){
	
	array_push($lengths, 100);
	array_push($widths, 18);
	array_push($thicknesses, 14.2);
	array_push($uploadTimes, '2008/23/23');
	array_push($remarks, 'auto insert');
	array_push($materials, 'A');
	array_push($sequenceNumbers, '001');
	array_push($manufactorys, 'neu');
	array_push($unitWeights, 120.0);
	array_push($weights, $unitWeights[$i] * $counts[$i]);
	if($filenames[$i] == 'zongbiao.xls'){
		array_push($orderNumbers, '051');
		array_push($orderSubitemNumbers, '0');
	}else {
		array_push($orderNumbers, null);
		array_push($orderSubitemNumbers, null);
	}
	
	if($materialCodes[$i] == 'A'){
		array_push($colors, 'blue');
	}else if($materialCodes[$i] == 'B'){
		array_push($colors, null);
	}else if($materialCodes[$i] == 'C'){
		array_push($colors, 'red');
	}
}
$smarty->assign(array('main_sequenceNumber' => $sequenceNumbers,
		'main_manufactory' => $manufactorys,
		'main_materialCode' => $materialCodes,
		'main_shipsClassification' => $shipsClassifications,
		'main_material' => $materials,
		'main_thickness' => $thicknesses,
		'main_width' => $widths,
		'main_length' => $lengths,
		'main_count' => $counts,
		'main_unitWeight' => $unitWeights,
		'main_weight' => $weights,
		'main_remark' => $remarks,
		'main_uploadTime' => $uploadTimes,
		'main_filename' => $filenames,
		'main_orderNumber' => $orderNumbers,
		'main_orderSubitemNumber' => $orderSubitemNumbers,
		'type' => $types,
		'colors' => $colors
	));

$smarty->display("batchSearch.html");

?>