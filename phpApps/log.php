<?php
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');
require_once('../includes/LogInserter.class.php');


$smarty = SmartyManager::getSmarty();

if(isset($_GET['err'])){
	if($_GET['err'] == 'format1'){
		$smarty->assign("errMsg", '第一个输入框里的时间格式不正确');
	} else if ($_GET['err'] == 'format2'){
		$smarty->assign("errMsg", "第二个输入框里的时间格式不正确");
	}
}

DB::query("select * from sss_log order by time desc");
$result = DB::getResult();

$contents = array();
$contents2 = array();
$times = array();
$types = array();
$ids = array();

$log = array('添加：总表上传：“sss.xls”',
			'删除：按照 船级 = DNV, 材质=A，厚=200，宽=3，长=20进行删除，删除了14条数据',
			'删除：删除一条记录：船级，材质，厚宽长分别为：xxxxxx',
			'修改：将原来信息：“各种信息”修改为“修改后的信息”');
			
while($row = $result->fetch_assoc()){
	array_push($contents, $row['content']);
	array_push($contents2, $row['content2']);
	if($row['type'] == LogInserter::$EDIT){
		array_push($types, 'edit');
	}else if($row['type'] == LogInserter::$DELETE){
		array_push($types, 'delete');
	}else {
		array_push($types, 'insert');
	}
	
	array_push($ids, $row['id']);
	array_push($times, $row['time']);
}

$smarty->assign(array('contents' => $contents,
		'contents2' => $contents2,
		'times' => $times,
		'types' => $types,
		'ids' => $ids
	));
	
	
$smarty->display("log.html");

?>