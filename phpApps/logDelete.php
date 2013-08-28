<?php


require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');

$ids = array();

try{
	beginTransaction();
	
	$count = 0;
	foreach($_POST as $key => $val){
		$val = trim($val);
		if(empty($val)){
			continue;
		}
		if(preg_match("/^id/", $key)){
			array_push($ids, "id = $val");
		}
	}
	
	if(!empty($ids)){
		$wheres = join(" or ", $ids);
		$query = "delete from sss_log where $wheres";
		//echo $query;
		DB::query($query);
		$count += sizeof($ids);
	}
	
	$count;
	commit();
	$smarty = SmartyManager::getSmarty();
	$smarty->assign("successTitle", "删除记录成功");
	$smarty->assign("successMsg", "删除成功,共删除了{$count}条记录");
	$smarty->display("success.html");
	
}catch(Exception $e){
	rollback();
	$smarty = SmartyManager::getSmarty();
	$smarty->assign("errTitle", "删除记录失败");
	$smarty->assign("errMsg", "删除中遇到错误：$e");
	$smarty->display("error.html");
	exit();
}


?>