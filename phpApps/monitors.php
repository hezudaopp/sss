<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');

$smarty = SmartyManager::getSmarty();

$ids = array();
$filenames = array();
$records = array();
$uploadTimes = array();

$query = 'select * from sss_monitor_file order by uploadTime desc';
DB::query($query);
$result = DB::getResult();
while(($row = $result->fetch_assoc())!=NULL){
	array_push($ids, $row['id']);
	array_push($filenames, Filter::forHtml($row['filename']));
	array_push($uploadTimes, $row['uploadTime']);
	array_push($records, Filter::forHtml($row['record']));
}

$smarty->assign(array('ids' => $ids,
		'filenames' => $filenames,
		'records' => $records,
		'times' => $uploadTimes
	));

$smarty->display("monitors.html");
?>