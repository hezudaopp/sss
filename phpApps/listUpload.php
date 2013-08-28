<?php
require ('../includes/functions.inc.php');
require ('../includes/SSS_ListExcelReader.class.php');

ini_set( "memory_limit" , "256M");

$smarty = SmartyManager::getSmarty ();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'listFile';

//不可缺少的列
$obligatoCols = array(
	'材料代码','订单号','订单子项号','证书号','结算批号' 
);

//电子表格和数据库表中的列的对应关系
$tableCols = array ('日期' => 'chukuDate',
					'车号' => 'chukuNumber',
					'船号' => 'chukuNumber', 
					'材料代码' => 'materialCode', 
					'船级' => 'shipsClassification', 
					'材质' => 'material', 
					'厚' => 'thickness', '宽' => 'width', '长' => 'length', 
					'数量' => 'count',
					'单重' => 'unitWeight',
					'重量' => 'weight',
					'文件名' => 'filename',
					'订单号' => 'orderNumber', 
					'订单子项号' => 'orderSubitemNumber', 
					'受订单价' => 'unitPrice', 
					'批号' => 'batchNumber', 
					'购单号' => 'purchaseNumber', 
					'目的地' => 'destination', 
					'库存地' => 'storePlace',
					'备注' => 'remarks',
					'证书号' => 'certificateNumber',
					'结算批号' => 'checkoutBatch' );

//对比时用来比较的列
$compareCols = array ();

$record = Filter::forDBInsertion ( $_POST ['record'], true );

/************************************************************
 * 文件上传
 ***********************************************************/
try {
	//$file 在本程序中用作了全局变量，用来在需要filename的函数中使用
	$file = saveUploadedFile ( $formFilename );
} catch ( Exception $e ) {
	$smarty->assign ( 'errMsg', $e );
	$smarty->display ( 'error.html' );
	exit ();
}

try {
	beginTransaction ();
	
	//然后read上传的表格的信息，将其中的数据存入数据库中
	$sssReader = new SSS_ListExcelReader ( );
	$sssReader->read ( $file->getNewPath () );
	
	$sssReader->obligatoColumns = $obligatoCols;
	$sssReader->tableColumns = $tableCols;
	//$sssReader->compareColumns = $compareCols;
	

	$warnMsg = "";
	try {
		$sssReader->checkMyFirstRow ();
	} catch ( UnknownColumnIndex $e ) {
		//这个函数可能抛出两个异常，一个是LackSomeColumn
		//另一个是UnknownColumnIndex。
		//对于UnknownColumnIndex只需要一个警告提醒，而不会像LackSomeColumn一样停止运行
		$warnMsg .= $e->getMessage ();
	}
	
	if (($date = $sssReader->myHaveUploaded ())!=NULL) {
		throw new UploadSameFileException ( $file->getFilename (), $date );
	} else {
		$sssReader->saveFileInfo ( $record );
	}
	
	//这个放在saveFileInfo下面是因为如果放在上边的话，保存file信息后获得不到inser_id。
	//beginTransaction();
	/*	$mcs =array();
	if($mcs = $sssReader->haveSameMaterialCode()){
		$warnMsg .= "<br />表格上传完成，但是程序检查到上传的文件中有重复的材料代码：'".join("', '", $mcs)."', 如果你确认这里出现了问题，可以直接在系统中进行修改或者删除刚才上传的文件";
	}*/
	
	$sssReader->checkCertificateNumberAndCheckoutBatch();
	
	if(($havenot = $sssReader->getHaveNotUploadedMaterialCode()) != null){
		throw new HaveNotUploadedMaterialCode($havenot);
	}
	
	if(($conflictsPair = $sssReader->getMcAndOrdersNotInDb()) != null){
		$table = "";
		foreach($conflictsPair as $pair){
			$table .= "| ".$pair." |<br />";
		}
		$msg = "文件中有些材料代码，订单号，订单子项号的组合在数据库中不存在，他们是：<br />
			| 文件中材料代码,订单号,订单子项号 |<br />
			$table<br />";
		throw new CustomException($msg);
	}
	
	$sssReader->myReadIntoDB ();//先上传表格中的数据存储到sss_list表中
	LogInserter::logForInsert ( "导入结算清单文件：{$file->getFilename()}" );
	
	$mcArray = $sssReader->getMaterialCodeAndOrdersArray();
	
	//对比材料代码，订单号，订单子项号后添加证书号和结算批号
	foreach ($mcArray as $value) {
		$mc = $value[0];//材料代码
		$on = $value[1];//订单号
		$osn = $value[2];//订单子项号
		$cns = array();//证书号
		$cbs = array();//结算批号
		
		//对于已经标记过的上传的时候需要提示
		$sql0 = "select 1 from sss_main 
		where materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn'
		and (certificateNumber is not null or certificateNumber <> '')";
		DB::query($sql0);
		if(DB::num_rows()>0){
			$warnMsg .= "'$mc','$on','$osn'之前已经标记过<br />";
		}
		
		$sql1 = "select certificateNumber,checkoutBatch from sss_list 
		where  materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn';";
		$result1 = DB::query($sql1);
		
		while (($row1 = $result1->fetch_array())!=NULL) {
			if (!in_array($row1['certificateNumber'],$cns)) {//检查$cns数组中是否存在某个值
				array_push($cns,$row1['certificateNumber']);
			}
			
			if (!in_array($row1['checkoutBatch'],$cbs)) {
				array_push($cbs,$row1['checkoutBatch']);
			}		
		}
		$cn = join(",",$cns);
		$cb = join(",",$cbs);
		
		$sql2 = "update sss_main set certificateNumber = '{$cn}',checkoutBatch = '{$cb}' 
			where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
		DB::query($sql2);
		$sql3 = "update sss_fache set certificateNumber = '{$cn}',checkoutBatch = '{$cb}' 
			where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
		DB::query($sql3);
		$sql4 = "update sss_fachuan set certificateNumber = '{$cn}',checkoutBatch = '{$cb}' 
			where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
		DB::query($sql4);	
	}
	
} catch ( DBQueryException $e ) {
	$smarty->assign ( 'errMsg', $e );
	$smarty->assign ( 'errorType', 'listUpload' );
	$smarty->assign ( "errTitle", '上传结算清单文件失败1' );
	$smarty->display ( 'error.html' );
	
	rollback ();
	exit ();
} catch ( Exception $e ) {
	$smarty->assign ( 'errMsg', $e );
	$smarty->assign ( 'errorType', 'listUpload' );
	$smarty->assign ( "errTitle", '上传结算清单文件失败2' );
	$smarty->display ( 'error.html' );
	rollback ();
	exit ();
}

$smarty->assign ( 'successMsg', $file->getFilename () . '上传成功' );
$smarty->assign ( "successTitle", '上传结算清单文件成功' );
$smarty->assign ( 'successType', 'listUpload' );
if (isset ( $warnMsg )) {
	$smarty->assign ( 'warnMsg', $warnMsg );
}
$smarty->display ( 'success.html' );
commit ();
?>