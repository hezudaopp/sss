<?php

require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');
require_once("../includes/LogInserter.class.php");

$main_ids = array();
$fache_ids = array();
$fachuan_ids = array();

try{
	beginTransaction();
	$count = 0;
	foreach($_POST as $key => $val){
		$val = trim($val);
		if(empty($val)){
			continue;
		}
		if(preg_match("/main_id/", $key)){
			array_push($main_ids, "id = $val");
		}else if(preg_match("/fache_id/", $key)){
			array_push($fache_ids, "id = $val");
		}else if(preg_match("/fachuan_id/", $key)){
			array_push($fachuan_ids, "id = $val");
		}
	}
	
	if(!empty($main_ids)){
		$wheres = join(" or ", $main_ids);
		
		//删除之前先记录一下
		$query = "select * from sss_main where $wheres";
		DB::query($query);
		$result = DB::getResult();
		$rows = array();
		while($row = $result->fetch_assoc()){
			array_push($rows, $row);
		}
		LogInserter::logForDelete($rows);
		
		//然后再执行删除
		$query = "delete from sss_main where $wheres";
		//echo $query;
		DB::query($query);
		$count += sizeof($main_ids);
	}
	if(!empty($fache_ids)){
		$wheres = join(" or ", $fache_ids);
		
		//删除之前先记录一下
		$query = "select * from sss_fache where $wheres";
		DB::query($query);
		$result = DB::getResult();
		$rows = array();
		while($row = $result->fetch_assoc()){
			array_push($rows, $row);
		}
		LogInserter::logForDelete($rows);
		
		
		$query = "delete from sss_fache where $wheres";
		//echo $query;
		DB::query($query);
		$count += sizeof($fache_ids);
	}
	if(!empty($fachuan_ids)){
		$wheres = join(" or ", $fachuan_ids);
		
		//删除之前先记录一下
		$query = "select * from sss_fachuan where $wheres";
		DB::query($query);
		$result = DB::getResult();
		$rows = array();
		while($row = $result->fetch_assoc()){
			array_push($rows, $row);
		}
		LogInserter::logForDelete($rows);
		
		
		$query = "delete from sss_fachuan where $wheres";
		//echo $query;
		DB::query($query);
		$count += sizeof($fachuan_ids);
	}
	
	$count;
	commit();
	$smarty = SmartyManager::getSmarty();
	$smarty->assign("successTitle", "批量删除成功");
	$smarty->assign("successMsg", "删除成功,共删除了{$count}条记录");
	$smarty->display("success.html");
	
}catch(Exception $e){
	rollback();
	$smarty = SmartyManager::getSmarty();
	$smarty->assign("errTitle", "删除失败");
	$smarty->assign("errMsg", "删除中遇到错误：$e");
	$smarty->display("error.html");
	exit();
}

?>