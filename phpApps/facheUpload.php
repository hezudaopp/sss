<?php
require('../includes/functions.inc.php');
require('../includes/SSS_ExcelReader.class.php');

$smarty = SmartyManager::getSmarty();

/*************************************************************
 * 每个上传文件只要修改下面这些变量就基本可以了
 ************************************************************/
$formFilename = 'truckFile';

//不可缺少的列
if($_POST['phase']==0){
	$obligatoCols = array(
	'日期', '车号', '材料代码','船级', '材质',
	'厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','库存地','备注');
}elseif ($_POST['phase']==1){
	$obligatoCols = array(
	'日期', '车号', '材料代码','船级', '材质',
	'厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','库存地','目的地','备注');
}else{
	$obligatoCols = array(
	'日期', '车号', '材料代码','船级', '材质',
	'厚', '宽', '长', '数量', '单重','重量','订单号','订单子项号','受订单价','购单号','批号','物料号','目的地','备注');
}


//电子表格和数据库表中的列的对应关系
$tableCols = array(
		'日期' 		=> 	'facheDate',
		'车号' 		=> 	'facheNumber',
		'材料代码' 	=> 	'materialCode',
		'船级' 		=> 	'shipsClassification',
		'材质' 		=> 	'material',
		'厚' 		=> 	'thickness',
		'宽' 		=> 	'width',
		'长' 		=> 	'length',
		'数量' 		=> 	'count',
		'单重'		=> 	'unitWeight',
		'订单号' 	=> 	'orderNumber',
		'订单子项号' 	=> 	'orderSubitemNumber',
		'受订单价'  =>   'unitPrice',
		'批号'      => 	'batchNumber',
		'物料号'		=>	'materialNumber',
		'购单号'    =>	'purchaseNumber',
		'目的地'		=> 'destination',
		'库存地'	    =>	'storePlace',
	    '备注1' 		=> 	'remark1',
		'备注2'		=>	'remark2',
		'备注3' 		=> 	'remark3',
		'备注4'		=>	'remark4',
		'备注5'		=> 	'remark5',
		'备注'		=> 	'remark1'
);

//对比时用来比较的列
$compareCols = array(
	'materialCode', 'orderNumber', 'orderSubitemNumber'
);

$tableName = 'sss_fache';

/************************************************************
 * 文件上传
 ***********************************************************/
try{
	//$file 在本程序中用作了全局变量，用来在需要filename的函数中使用
	$file = saveUploadedFile($formFilename);
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	exit();
}

try{
	beginTransaction();
	$sssReader = new SSS_ExcelReader();
	$sssReader->read($file->getNewPath());

	$sssReader->obligatoColumns = $obligatoCols;
	$sssReader->tableColumns = $tableCols;
	$sssReader->compareColumns = $compareCols;
	$sssReader->tableName = $tableName;
	
	if($_POST['phase']==0){
		$sssReader->checkFirstRow('ruku');
	}elseif ($_POST['phase']==1){
		$sssReader->checkFirstRow('chuku');
	}
	else{
		$sssReader->checkFirstRow('sale');
	}
	

	if(($date = $sssReader->haveUploaded())){
		throw new UploadSameFileException($file->getFilename(), $date);
	}
	if(($havenot = $sssReader->getHaveNotUploadedMaterialCode())!=null){
		throw new HaveNotUploadedMaterialCode($havenot);
	}
	
	if(($conflictsPair = $sssReader->getMcAndOrdersNotInDb())!=null){
		$table = "";
		foreach($conflictsPair as $pair){
			$table .= "| ".$pair." |<br />";
		}
		$msg = "文件中有些材料代码，订单号，订单子项号的组合在数据库中不存在，他们是：<br />
			| 文件中材料代码,订单号,订单子项号 |<br />
			$table<br />";
		throw new CustomException($msg);
	}
	
	if($_POST['phase']==0 || $_POST['phase']==2){
		if(($conflictsPair = $sssReader->getMcAndOrdersRukuCountExceed()) != null){
			$table = "";
			foreach($conflictsPair as $pair){
				$table .= "| ".$pair." |<br />";
			}
			$warnMsg = "文件中有些材料代码，订单号，订单子项号的组合数量超过了总表中的数量，他们是：<br />
				| 文件中材料代码,订单号,订单子项号 ,数量|<br />
				$table<br />";
		}
	}
	
	if($_POST['phase']==1){
		if(($conflictsPair = $sssReader->getMcAndOrdersChukuCountExceed()) != null){
			$table = "";
			foreach($conflictsPair as $pair){
				$table .= "| ".$pair." |<br />";
			}
			$msg = "文件中有些材料代码，订单号，订单子项号的组合数量超过了库中的数量，他们是：<br />
				| 文件中材料代码,订单号,订单子项号 ,数量|<br />
				$table<br />";
			throw new CustomException($msg);
		}
	}

	/*
	if($mcs = $sssReader->haveSameMaterialCode()){
		$warnMsg = "但是，程序检查到上传的文件中有重复的材料代码：'".join("', '", $mcs)."', 如果你确认这里出现了问题，可以直接在系统中进行修改或者删除刚才上传的文件";
	}
	*/
	$sssReader->readIntoDB();
	LogInserter::logForInsert("导入发车文件：{$file->getFilename()}");
	
	$mcArray = $sssReader->getMaterialCodeAndOrdersArray();
	
	
	
	
	if ($_POST['phase']==0) {
		foreach ($mcArray as $value) {
				$mc = $value[0];
				$on = $value[1];
				$osn = $value[2];
				$batchNumbers = array();
				$materialNumbers = array();
				$storePlaces = array();
				$sql1 = "select batchNumber,materialNumber,storePlace from sss_fache 
				where  materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn' and phase = '入库';";
				$result1 = DB::query($sql1);
				while (($row1 = $result1->fetch_array())!=null) {
					if (!in_array($row1['batchNumber'],$batchNumbers)&&$row1['batchNumber']!=NULL) {
						array_push($batchNumbers,$row1['batchNumber']);
					}
					if (!in_array($row1['storePlace'],$storePlaces)&&$row1['storePlace']!=NULL) {
						array_push($storePlaces,$row1['storePlace']);
					}	
					if (!in_array($row1['materialNumber'],$materialNumbers)&&$row1['materialNumber']!=NULL) {
						array_push($materialNumbers,$row1['materialNumber']);
					}	
				}
				$batchNumber = join(",",$batchNumbers);
				$storePlace = join(",",$storePlaces);
				$materialNumber = join(",",$materialNumbers);
				
				$sql2 = "update sss_main set batchNumber = '{$batchNumber}',storePlace = '{$storePlace}',materialNumber = '{$materialNumber}' 
					where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
				DB::query($sql2);
			
		}
		
	}
	
	if ($_POST['phase']==1) {
		foreach ($mcArray as $value) {
				$mc = $value[0];
				$on = $value[1];
				$osn = $value[2];
				$destinations = array();
				
				//从发车(出库),发船中提取出同一个材料代码所有的目的地,用以更新总表和发车(入库)中的目的地
				$sql1 = "select destination from sss_fache 
				where  materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn' and phase = '出库'";
				$result1 = DB::query($sql1);
				while (($row1 = $result1->fetch_array())!=null) {
					
					if (!in_array($row1['destination'],$destinations) && $row1['destination']!=NULL) {
						array_push($destinations,$row1['destination']);
					}
				}	
				$destination = join(",",$destinations);	
				
				$sql2 = "update sss_main set destination = '{$destination}' 
				where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
				DB::query($sql2);
				$sql3 = "update sss_fache set destination = '{$destination}' 
				where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."' and phase = '入库';";
				DB::query($sql3);
		}
		
	}
	
	if ($_POST['phase']==2) {
		foreach ($mcArray as $value) {
				$mc = $value[0];
				$on = $value[1];
				$osn = $value[2];
				$batchNumbers = array();
				$destinations = array();
				$materialNumbers = array();
				$sql1 = "select batchNumber,materialNumber,destination from sss_fache 
				where  materialCode = '$mc' and orderNumber = '$on' and orderSubitemNumber = '$osn' and phase = '销售';";
				$result1 = DB::query($sql1);
				while (($row1 = $result1->fetch_array())!=null) {
					if (!in_array($row1['batchNumber'],$batchNumbers) && $row1['batchNumber']!=NULL) {
						array_push($batchNumbers,$row1['batchNumber']);
					}if (!in_array($row1['materialNumber'],$materialNumbers) && $row1['materialNumber']!=NULL) {
						array_push($materialNumbers,$row1['materialNumber']);
					}
					if (!in_array($row1['destination'],$destinations) && $row1['destination']!=NULL) {
						array_push($destinations,$row1['destination']);
					}
					
				}
				$batchNumber = join(",",$batchNumbers);
				$materialNumber = join(",",$materialNumbers);
				$destination = join(",",$destinations);
				
				$sql2 = "update sss_main set batchNumber = '{$batchNumber}',destination = '{$destination}',materialNumber = '{$materialNumber}'
				where materialCode = '".$mc."' and orderNumber = '".$on."' and orderSubitemNumber = '".$osn."';";
				DB::query($sql2);
			}
		
	}
	
}catch(DBQueryException $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	rollback();
	exit();
}catch(Exception $e){
	$smarty->assign('errMsg', $e);
	$smarty->display('error.html');
	rollback();
	exit();
}

$smarty->assign('successMsg', $file->getFilename().'上传成功');
if(isset($warnMsg)){
	$smarty->assign('warnMsg', $warnMsg);
}
$smarty->display('success.html');
commit();
?>