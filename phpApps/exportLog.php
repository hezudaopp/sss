<?php
require_once('../includes/DB.class.php');
require_once("../includes/Verifier.class.php");
require_once('../includes/functions.inc.php');

$filename = $_GET['filename'];

ini_set("max_execution_time",0);

function redirect($str){
	$url = "log.php?$str";
	
	$gets = array();
	foreach($_POST as $key => $val){
		array_push($gets, urlencode($key)."=".urlencode($val));
	}
	$url .= "&".join("&", $gets);
	header("location: $url");
}

$times = array();
$contents = array();
$contents2 = array();
$types = array();

if(isset($_POST['fromDate'])){
	if(Verifier::isDATE($_POST['fromDate'])){
		$fromDate = $_POST['fromDate'];
	}else if(empty($_POST['fromDate'])){
		$fromDate = "1000/10/10";
	}
	else{
		redirect("err=format1");
		exit();
	}
}

if(isset($_POST['toDate'])){
	if( Verifier::isDATE($_POST['toDate'])){
		$toDate = $_POST['toDate'];
	}else if(empty($_POST['toDate'])){
		$toDate = "2100/10/10";
	}
	else{
		redirect("err=format2");
		exit();
	}
}

$wheres = " time >= '".$fromDate . "' and time <= '" . $toDate ."'";

$sql = "SELECT time, content, content2, type FROM sss_log
		WHERE {$wheres}";
$result = DB::query($sql);
if($result == false){
	return;
}
while($row = $result->fetch_assoc()){
	array_push($times, $row['time']);
	if($row['type']==1){
		$temp = "添加";
	}
	else if($row['type']==2){
		$temp = "删除";
	}
	else {
		$temp = "修改";
	}
	array_push($types, $temp);
	array_push($contents, $row['content']);
	array_push($contents2, $row['content2']);
}

$data = array(
		'时间' => $times,
		'操作' => $types,
		'记录' => $contents,
		'修改后记录' => $contents2
	);

$filename = pathinfo($filename, PATHINFO_FILENAME).'修改记录-'.date('YmdHis').'.xls';
generateLogHttpExcelByCols($data,$filename);
?>