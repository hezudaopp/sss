<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');

$smarty = SmartyManager::getSmarty();

$shipNumbers = array();

$query = 'select distinct(shipNumber) from sss_summary';
DB::query($query);
$result = DB::getResult();
while(($row = $result->fetch_assoc())!=NULL){
	array_push($shipNumbers, Filter::forHtml($row['shipNumber']));
}

$smarty->assign(array('shipNumbers' => $shipNumbers,
	));

$smarty->display("summary.html");
?>
