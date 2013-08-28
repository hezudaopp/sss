<?php
require ('../includes/functions.inc.php');

$feedback = false;
if (! isset ( $_POST ['type'] )) {

} else if ($_POST ['type'] == 'tempLog') {
	try {
		clearTempLog ();
		$feedback = '清理临时数据库 成功';
	} catch ( MyException $e ) {
		$feedback = $e->__toString ();
	}
} else if ($_POST ['type'] == 'tempExcel') {
	try {
		if (clearTempExcel ())
			$feedback = '清理临时的excel文件 成功';
		else
			$feedback = '很遗憾， 清理临时excel文件失败';
	} catch ( Exception $e ) {
		$feedback = $e->__toString ();
	}
} else if ($_POST ['type'] == 'successed') {
	try {
		$rows = clearSuccessed ();
		$feedback = '成功：清理清理数据库中已完成发车（或发船）的记录 ' . $rows . '行；';
	} catch ( MyException $e ) {
		$feedback = $e->__toString ();
	}
}

$smarty = SmartyManager::getSmarty ();
$smarty->assign ( 'feedback', $feedback );
$smarty->display ( 'clear.html' );

?>