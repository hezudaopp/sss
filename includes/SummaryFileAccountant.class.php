<?php
require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class SummaryFileAccountant extends Accountant {

	/**
	 * 文件Id
	 *
	 * @var int
	 */
	private $fileId = null;
	
	/**
	 * 文件名
	 * @var string
	 */
	private $filename = array();
	/**
	 * 船号
	 * @var string
	 */
	private $shipNumber = array();
	
	/**
	 * 分段号
	 * @var string
	 */
	private $subNumber = array();
	
	/**
	 * 发货批次
	 * @var string
	 */
	private $consignmentBatch =  array();
	
	/**
	 * 备注
	 * @var string
	 */
	private $remark = array();
	
	private $deleteSql = null;
	
	public function __construct($fileId){
		
		$query = "select * from sss_summary where fileId = '{$fileId}'";
		DB::query($query);
		$result = DB::getResult();
		
		$tableCols = array(
				'船号' 		=> 	'shipNumber',
				'分段号' 		=> 	'subumber',
				'发货批次' 	=> 	'consignmentBatch',
				'备注'		=>	'remark'
		);
		$cols = array();
		if(($row = $result->fetch_assoc())!=NULL){
			foreach($tableCols as $val){
				if(isset($row[$val]) and !empty($row[$val])){
					array_push($cols, $val);
				}
			}
		}
		
		$colswhere = join(',', $cols);
		
		if(empty($colswhere)){
			$this->sql = null;
			return;
		}
		
		//如果有count怎么办？
		//如果没有count又该怎么办？
		
		$this->sql = "select * from sss_summary where fileId = '{$fileId}'";
		
		
		///////////////////////////////////////////////
		$this->deleteSql = "delete from sss_summary where fileId = '{$fileId}'";
			
	}
	
	public function executeDelete(){
		
		try{
			DB::beginTransaction();
			foreach($this->deleteSql as $val){
				DB::query($val);
			}
			DB::commit();
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		
	}

	/**
	 * 执行查询命令，并整理查询的结果
	 *
	 */
	public function execute(){
		$this->result = DB::query($this->sql);
		if($this->result == false){
			return;
		}
		while(($row = $this->result->fetch_assoc())!=NULL){
			array_push($this->shipNumber, $row['shipNumber']);
			array_push($this->subNumber, $row['subNumber']);
			array_push($this->consignmentBatch, $row['consignmentBatch']);
			array_push($this->remark, $row['remark']);
			
		}
	}
	/**
	 * @return the $remark
	 */
	public function getRemark() {
		return $this->remark;
	}

	/**
	 * @return the $consignmentBatch
	 */
	public function getConsignmentBatch() {
		return $this->consignmentBatch;
	}

	/**
	 * @return the $subNumber
	 */
	public function getSubNumber() {
		return $this->subNumber;
	}

	/**
	 * @return the $shipNumber
	 */
	public function getShipNumber() {
		return $this->shipNumber;
	}

	/**
	 * @return the $filename
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @return the $fileId
	 */
	public function getFileId() {
		return $this->fileId;
	}

	
}
?>