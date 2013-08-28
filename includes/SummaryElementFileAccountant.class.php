<?php
require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class SummaryElementFileAccountant extends Accountant {

	/**
	 * 文件Id
	 *
	 * @var int
	 */
	private $fileId = null;
	
	/**
	 * 文件名
	 * @var array
	 */
	private $filename = array();
	/**
	 * 厂家
	 * @var array
	 */
	private $manufactory = array();
	
	/**
	 * 库存地
	 * @var array
	 */
	private $storePlace = array();
	
	/**
	 * 目的地
	 * @var array
	 */
	private $destination =  array();
	
	/**
	 * 备注
	 * @var array
	 */
	private $remark = array();
	
	/**
	 * 厂家集合
	 * @var array
	 */
	private $manufactories = array();
	
	/**
	 * 库存地集合
	 * @var array
	 */
	private $storePlaces = array();
	
	/**
	 * 目的地集合
	 * @var array
	 */
	private $destinations = array();
	
	
	private $deleteSql = null;
	
	/**
	 * 厂家查询语句
	 * @var string
	 */
	private $manufactorySql = "select distinct(manufactory) from sss_summary_element
		where manufactory is not null";
	
	/**
	 * 库存地查询语句
	 * @var string
	 */
	private $storePlaceSql = "select distinct(storePlace) from sss_summary_element
		where storePlace is not null";
	
	/**
	 * 目的地查询语句
	 * @var string
	 */
	private $destinationSql = "select distinct(destination) from sss_summary_element
		where destination is not null";
	
	public function __construct($fileId=null){
		
		$query = "select * from sss_summary_element where fileId = '{$fileId}'";
		DB::query($query);
		$result = DB::getResult();
		
		$tableCols = array(
				'厂家' 		=> 	'manufactory',
				'库存地' 		=> 	'storePlace',
				'目的地' 	=> 	'destination',
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
		
		$this->sql = "select * from sss_summary_element where fileId = '{$fileId}'";
		
		
		///////////////////////////////////////////////
		$this->deleteSql = "delete from sss_summary_element where fileId = '{$fileId}'";
			
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
			array_push($this->manufactory, $row['manufactory']);
			array_push($this->storePlace, $row['storePlace']);
			array_push($this->destination, $row['destination']);
			array_push($this->remark, $row['remark']);
			
		}
	}
	
	/**
	 * 执行汇总因素查询命令，并整理查询的结果
	 *
	 */
	public function executeSummaryElement(){
		//添加所有的厂家到manufactories
		$result = DB::query($this->manufactorySql);
		if($result == false){
			return;
		}
		while(($row = $result->fetch_assoc())!=NULL){
			array_push($this->manufactories, $row['manufactory']);
		}
		
		//添加所有库存地到storePlaces
		$result = DB::query($this->storePlaceSql);
		if($result == false){
			return;
		}
		while(($row = $result->fetch_assoc())!=NULL){
			array_push($this->storePlaces, $row['storePlace']);
		}
		
		//添加所有目的地到destinations
		$result = DB::query($this->destinationSql);
		if($result == false){
			return;
		}
		while(($row = $result->fetch_assoc())!=NULL){
			array_push($this->destinations, $row['destination']);
		}
	}
	/**
	 * @return the $destinations
	 */
	public function getDestinations() {
		return $this->destinations;
	}

	/**
	 * @return the $storePlaces
	 */
	public function getStorePlaces() {
		return $this->storePlaces;
	}

	/**
	 * @return the $manufactories
	 */
	public function getManufactories() {
		return $this->manufactories;
	}

	/**
	 * @return the $remark
	 */
	public function getRemark() {
		return $this->remark;
	}

	/**
	 * @return the $destination
	 */
	public function getDestination() {
		return $this->destination;
	}

	/**
	 * @return the $storePlace
	 */
	public function getStorePlace() {
		return $this->storePlace;
	}

	/**
	 * @return the $manufactory
	 */
	public function getManufactory() {
		return $this->manufactory;
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