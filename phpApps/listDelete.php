<?php
require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/DB.class.php');
require_once ('../includes/exceptions' . DS . 'AppExceptions.class.php');
require_once ('../includes/LogInserter.class.php');

$smarty = SmartyManager::getSmarty ();
$dels = array ();
foreach ( $_POST as $key => $val ) {
	if (stristr ( $key, "del" )) {
		array_push ( $dels, "id = $val" );
	}
}

if (! empty ( $dels )) {
	try {
		$delStats = join ( ' or ', $dels );
		DB::beginTransaction ();
		$query = "select filename, id from sss_list_file where $delStats";
		DB::query ( $query );
		$result = DB::getResult ();
		while ( $row = $result->fetch_assoc () ) {
			LogInserter::logForDelete ( "删除这个结算清单文件：({$row['filename']})" );
			$query = "select materialCode, orderNumber, orderSubitemNumber from sss_list where fileId = '{$row['id']}'";
			DB::query($query);
			$result = DB::getResult();
			while( $row = $result->fetch_assoc()){
				$query1 = "UPDATE `sss_main` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
							where materialCode = '{$row['materialCode']}' and orderNumber = '{$row['orderNumber']}' and orderSubitemNumber = '{$row['orderSubitemNumber']}'";
				$query2 = "UPDATE `sss_fache` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
							where materialCode = '{$row['materialCode']}' and orderNumber = '{$row['orderNumber']}' and orderSubitemNumber = '{$row['orderSubitemNumber']}'";
				$query3 = "UPDATE `sss_fachuan` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
							where materialCode = '{$row['materialCode']}' and orderNumber = '{$row['orderNumber']}' and orderSubitemNumber = '{$row['orderSubitemNumber']}'";
				DB::query($query1);
				DB::query($query2);
				DB::query($query3);						
			}
		}
		$query = "delete from sss_list_file where $delStats";
		DB::query ( $query );
		DB::commit ();
	} catch ( Exception $e ) {
		DB::rollback ();
		$smarty->assign ( "errTitle", '删除结算清单文件失败' );
		$smarty->assign ( 'errorType', 'listDelete' );
		$smarty->assign ( 'errMsg', $e );
		$smarty->display ( 'error.html' );
		die ();
	}
}

$smarty->assign ( "successTitle", '删除结算清单文件成功' );
$smarty->assign ( 'successType', 'listDelete' );
$smarty->assign ( 'successMsg', '删除文件成功' );
$smarty->display ( 'success.html' );

?>