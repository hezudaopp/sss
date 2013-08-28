<?php
require_once ('../includes/SmartyManager.class.php');
require_once ('../includes/DB.class.php');
require_once ('../includes/exceptions' . DS . 'AppExceptions.class.php');
require_once ('../includes/LogInserter.class.php');
ini_set("max_execution_time",0);
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
		$query = "select filename, id from sss_consignment_file where $delStats";
		DB::query ( $query );
		$result = DB::getResult ();
		while ( $row = $result->fetch_assoc () ) {
			LogInserter::logForDelete ( "删除这个发货批次文件：({$row['filename']})" );
			$query = "select materialCode, shipNumber, subsectionNumber, orderNumber, orderSubitemNumber, purchaseNumber from sss_consignment where fileId = '{$row['id']}'";
			DB::query($query);
			$result = DB::getResult();
			while( $row = $result->fetch_assoc()){
				$sql2 = "update sss_main set consignmentBatch = NULL 
			where ";
			$wheres = array();
			if($row['materialCode']){
				array_push($wheres, "materialCode = '{$row['materialCode']}'");
			}
			if($row['shipNumber']){
				array_push($wheres, "substring(materialCode,1,5) = '{$row['shipNumber']}'");
			}
			if($row['subsectionNumber']){
				array_push($wheres, "substring(materialCode,9,3) = '{$row['subsectionNumber']}'");
			}
			if($row['orderNumber']){
				array_push($wheres, "orderNumber = '{$row['orderNumber']}'");
			}
			if($row['orderSubitemNumber']){
				array_push($wheres, "orderSubitemNumber = '{$row['orderSubitemNumber']}'");
			}
			if($row['purchaseNumber']){
				array_push($wheres, "purchaseNumber = '{$row['purchaseNumber']}'");
			}
			$where = join(' and ',$wheres);
			$sql2 .= $where;
			DB::query($sql2);
			
			$sql3 = "update sss_fache set consignmentBatch = NULL
				where ";
			$wheres = array();
			if($row['materialCode']){
				array_push($wheres, "materialCode = '{$row['materialCode']}'");
			}
			if($row['shipNumber']){
				array_push($wheres, "substring(materialCode,1,5) = '{$row['shipNumber']}'");
			}
			if($row['subsectionNumber']){
				array_push($wheres, "substring(materialCode,9,3) = '{$row['subsectionNumber']}'");
			}
			if($row['orderNumber']){
				array_push($wheres, "orderNumber = '{$row['orderNumber']}'");
			}
			if($row['orderSubitemNumber']){
				array_push($wheres, "orderSubitemNumber = '{$row['orderSubitemNumber']}'");
			}
			if($row['purchaseNumber']){
				array_push($wheres, "purchaseNumber = '{$row['purchaseNumber']}'");
			}
			$where = join(' and ',$wheres);
			$sql3 .= $where;
			DB::query($sql3);
			$sql4 = "update sss_fachuan set consignmentBatch = NULL 
				where ";
			$wheres = array();
			if($row['materialCode']){
				array_push($wheres, "materialCode = '{$row['materialCode']}'");
			}
			if($row['shipNumber']){
				array_push($wheres, "substring(materialCode,1,5) = '{$row['shipNumber']}'");
			}
			if($row['subsectionNumber']){
				array_push($wheres, "substring(materialCode,9,3) = '{$row['subsectionNumber']}'");
			}
			if($row['orderNumber']){
				array_push($wheres, "orderNumber = '{$row['orderNumber']}'");
			}
			if($row['orderSubitemNumber']){
				array_push($wheres, "orderSubitemNumber = '{$row['orderSubitemNumber']}'");
			}
			if($row['purchaseNumber']){
				array_push($wheres, "purchaseNumber = '{$row['purchaseNumber']}'");
			}
			$where = join(' and ',$wheres);
			$sql4 .= $where;
			DB::query($sql4);					
				}
			}
			$query = "delete from sss_consignment_file where $delStats";
			DB::query ( $query );
			DB::commit ();
	} catch ( Exception $e ) {
		DB::rollback ();
		$smarty->assign ( "errTitle", '删除发货批次文件失败' );
		$smarty->assign ( 'errorType', 'consignmentDelete' );
		$smarty->assign ( 'errMsg', $e );
		$smarty->display ( 'error.html' );
		die ();
	}
}

$smarty->assign ( "successTitle", '删除发货批次文件成功' );
$smarty->assign ( 'successType', 'consignmentDelete' );
$smarty->assign ( 'successMsg', '删除文件成功' );
$smarty->display ( 'success.html' );

?>