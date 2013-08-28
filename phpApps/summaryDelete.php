<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/exceptions'.DS.'AppExceptions.class.php');
require_once('../includes/LogInserter.class.php');

$smarty = SmartyManager::getSmarty();
$dels = array();
foreach($_POST as $key => $val){
	if(stristr($key, "del")){
		array_push($dels, "id = $val");
	}
}

if(!empty($dels)){
	try{
		$delStats = join(' or ', $dels);
		DB::beginTransaction();
		$query = "select filename from sss_summary_file where $delStats";
		DB::query($query);
		$result = DB::getResult();
		while($row = $result->fetch_assoc()){
			LogInserter::logForDelete("删除这个汇总文件：({$row['filename']})");
		}
		$query = "delete from sss_summary_file where $delStats";
		DB::query($query);
		DB::commit();
	}catch(Exception $e){
		DB::rollback();
		$smarty->assign("errTitle", '删除汇总文件失败');
		$smarty->assign('errorType', 'summaryDelete');
		$smarty->assign('errMsg', $e);
		$smarty->display('error.html');
		die();
	}
}

$smarty->assign("successTitle", '删除汇总文件成功');
$smarty->assign('successType', 'summaryDelete');
$smarty->assign('successMsg', '删除文件成功');
$smarty->display('success.html');

?>