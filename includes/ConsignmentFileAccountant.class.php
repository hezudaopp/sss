<?php
require_once ("Accountant.class.php");
require_once ("exceptions/AppExceptions.class.php");

class ConsignmentFileAccountant extends Accountant {
	
	/**
	 * 文件Id
	 *
	 * @var int
	 */
	private $fileId = null;
	
	/**
	 * 材料代码的数组
	 *
	 * @var array
	 */
	private $materialCode = array ();
	
	/**
	 * 订单号
	 *
	 * @var array
	 */
	private $orderNumber = array ();
	
	/**
	 * 订单子项号
	 *
	 * @var array
	 */
	private $orderSubitemNumber = array ();
	
	/**
	 * 购单号
	 *
	 * @var unknown_type
	 */
	private $purchaseNumber = array ();
	
	/**
	 * 物料号
	 *
	 * @var unknown_type
	 */
	private $materialNumber = array();
	
	/**
	 * 发货批次
	 *
	 * @var unknown_type
	 */
	private $consignmentBatch = array();
	
	/**
	 * 备注
	 *
	 * @var unknown_type
	 */
	private $remark = array();
	
	/**
	 * 船号
	 *
	 * @var unknown_type
	 */
	private $shipNumber = array();
	
	/**
	 * 分段号
	 *
	 * @var unknown_type
	 */
	private $subsectionNumber = array();
	
	private $deleteSql = array ();
	/**
	 * 标志这个监控文件的数据中是否有mc
	 *
	 * @var bool
	 */
	private $haveMc = false;
	/**
	 * 构造函数，根据文件名称初始化sql语句。
	 *
	 * @param string $filename
	 */
	public function __construct($fileId) {
		
		$query = "select * from sss_consignment where fileId = '{$fileId}'";
		DB::query ( $query );
		$result = DB::getResult ();
		
		$tableCols = array ('材料代码' => 'materialCode', 
							'订单号' => 'orderNumber', 
							'订单子项号' => 'orderSubitemNumber', 
							'购单号' => 'purchaseNumber',
							'备注' => 'remark',
							'物料号'		=>  'materialNumber',
							'发货批次'	=>  'consignmentBatch'
							);
		$cols = array ();
		if ($row = $result->fetch_assoc ()) {
			foreach ( $tableCols as $key => $val ) {
				if (isset ( $row [$val] ) and !empty ( $row [$val] )) {
					array_push ( $cols, $val );
				}
			}
		}
		
		$this->haveMc = false;
		if (in_array ( 'materialCode', $cols )) {
			$colswhere = 'materialCode';
			$this->haveMc = true;
		}
		
		$colswhere = join ( ',', $cols );
		
		if (empty ( $colswhere )) {
			$this->sql = null;
			return;
		}
		
		$this->sql = "select * from sss_consignment where fileId = '{$fileId}'";
		///////////////////////////////////////////////
		

		array_push ( $this->deleteSql, "UPDATE `sss_fache` SET `consignmentBatch` = NULL 
			where ({$this->getNotAndChineseMaterialCodes()}) and 
			exists (select 1
					from sss_main 
					where ($colswhere) in (select $colswhere from sss_consignment where fileId = $fileId)
						and sss_main.materialCode = sss_fache.materialCode)" );
		
		array_push ( $this->deleteSql, "UPDATE `sss_fachuan` SET `consignmentBatch` = NULL
			where ({$this->getNotAndChineseMaterialCodes()}) and 
			exists (select 1
					from sss_main 
					where ($colswhere) in (select $colswhere from sss_consignment where fileId = $fileId)
						and sss_main.materialCode = sss_fachuan.materialCode)" );
		
		array_push ( $this->deleteSql, "UPDATE `sss_main` SET `consignmentBatch` = NULL
			where ({$this->getNotAndChineseMaterialCodes()}) and 
				($colswhere) in (select $colswhere from sss_consignment where fileId = $fileId)" );
	
	}
	
	public function executeDelete() {
		
		try {
			DB::beginTransaction ();
			foreach ( $this->deleteSql as $val ) {
				DB::query ( $val );
			}
			DB::commit ();
		} catch ( Exception $e ) {
			DB::rollback ();
			throw $e;
		}
	
	}
	
	/**
	 * 执行查询命令，并整理查询的结果
	 *
	 */
	public function execute() {
		$this->result = DB::query ( $this->sql );
		if ($this->result == false) {
			return;
		}
		while ( $row = $this->result->fetch_assoc () ) {
			array_push ( $this->materialCode, $row ['materialCode'] );
			array_push ( $this->shipNumber, $row ['shipNumber'] );
			array_push ( $this->subsectionNumber, $row ['subsectionNumber'] );
			array_push ( $this->orderNumber, $row ['orderNumber'] );
			array_push ( $this->orderSubitemNumber, $row ['orderSubitemNumber'] );
			array_push ( $this->purchaseNumber, $row ['purchaseNumber'] );
			array_push ( $this->consignmentBatch, $row['consignmentBatch']);
			array_push ( $this->remark, $row ['remark'] );
		}
	}
	
	public function getMaterialCode() {
		return $this->materialCode;
	}
	
	public function getShipNumber(){
		return $this->shipNumber;
	}
	
	public function getSubsectionNumber(){
		return $this->subsectionNumber;
	}
	
	public function getOrderNumber() {
		return $this->orderNumber;
	}
	
	public function getOrderSubitemNuber() {
		return $this->orderSubitemNumber;
	}
	
	public function getPurchaseNumber() {
		return $this->purchaseNumber;
	}
	
	public function getRemark(){
		return $this->remark;
	}
	
	public function getConsignmentBatch(){
		return $this->consignmentBatch;
	}
	
	public function getMaterialNumber(){
		return $this->materialNumber;
	}
}
?>