<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');


$subNumbers = array();
$shipNumber = $_GET['shipNumber'];
$query = "select distinct(subNumber) from sss_summary where shipNumber = '$shipNumber'";
DB::query($query);
$result = DB::getResult();
while(($row = $result->fetch_assoc())!=NULL){
	array_push($subNumbers, Filter::forHtml($row['subNumber']));
}
echo json_encode($subNumbers);

?>
