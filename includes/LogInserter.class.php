<?php
require_once("DB.class.php");

class LogInserter{
	public static $DELETE = 2;
	public static $INSERT = 1;
	public static $EDIT = 3;
	
	private static $ecpairs = 
			array("materialCode" => "材料代码",
				'material' => '材质',
				'shipsClassification' => '船级',
				'thickness' => '厚',
				'width' => '宽',
				'length' => '长',
				'unitWeight' => '单重',
//				'filename' => '文件名',
				'orderNumber' => '订单号',
				'orderSubitemNumber' => '订单子项号',
//				'unitPrice' => '受订单价',
				'batchNumber' => '批号',
				'purchaseNumber' => '购单号',
//				'destination' => '目的地',
//				'storePlace' => '库存地',
//				'certificateNumber' => '证书号',
//				'checkoutBatch' => '结算批号',
				'materialNumber' => '物料号',
//				'consignmentBatch' => '发货批次',
				'facheNumber' => '车号',
				'fachuanNumber' => '船号',
				'faNumber' => '车号/船号',
				'facheDate' => '发车日期',
				'fachuanDate' => '发船日期',
				'faDate' => '发车/发船日期',
//				'phase' => '阶段',
//				'remark1' => '备注1',
//				'remark2' => "备注2",
//				'remark3' => "备注3",
//				'remark4' => "备注4",
//				'remark5' => "备注5",
//				'uploadTime' => '上传时间',
				'count' => '数量'); 
	
	private function log($content, $type){
		if(empty($content)){
			return;
		}
		
		$logcontent = array();
		$subLogcontent = array();
		$logcontentStr = "";
		$subLogcontentStr = "";
		
		if (is_array($content)){
			$split = "，";
			foreach($content as $key => $val){
				if(empty($val)){
					continue;
				}
				
				if(is_array($val)){
					$subLogcontent = array();
					foreach($val as $subkey => $subval){
						isset(self::$ecpairs[$subkey]) ? array_push($subLogcontent, self::$ecpairs[$subkey] . " : " . $subval) : null;
					}
					if($key == 0){
						$contentStr = join("，", $subLogcontent);					
					}else if($key ==1){
						$contentStr2 = join("，", $subLogcontent);
					}
				}else{
					isset(self::$ecpairs[$key]) ? array_push($logcontent, self::$ecpairs[$key]." : ".$val) : null;
				}
			}
		}else{
			$contentStr = "$content";
		}
		
		if($type ==3){
			$query = "insert into sss_log
			set time = now(), content = '$contentStr', content2 = '$contentStr2', type = $type";
		}else{
			$query = "insert into sss_log
			set time = now(), content = '$contentStr', type = $type";
		}
		DB::query($query);
	}
	
	public function logForInsert($content){
		self::log($content, self::$INSERT);
	}
	
	public function logForDelete($content){
		self::log($content, self::$DELETE);
	}
	
	public function logForEdit($content){
		self::log($content, self::$EDIT);
	}

}
?>