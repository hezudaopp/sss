<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');


$consignmentBatchs = array();
$shipNumber = $_GET['shipNumber'];
$query = "select distinct(consignmentBatch) from sss_summary where shipNumber = '$shipNumber'";
DB::query($query);
$result = DB::getResult();
while(($row = $result->fetch_assoc())!=NULL){
	array_push($consignmentBatchs, Filter::forHtml($row['consignmentBatch']));
}
echo json_encode($consignmentBatchs);

?>
