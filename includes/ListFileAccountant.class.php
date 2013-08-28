<?php
require_once ("Accountant.class.php");
require_once ("exceptions/AppExceptions.class.php");

class ListFileAccountant extends Accountant {
	
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
	 * 船级的数组
	 *
	 * @var array
	 */
	private $shipsClassification = array ();
	
	/**
	 * 材料的数组
	 *
	 * @var array
	 */
	private $material = array ();
	
	/**
	 * 厚度的数组
	 *
	 * @var array
	 */
	private $thickness = array ();
	
	/**
	 * 宽度的数组
	 *
	 * @var array
	 */
	private $width = array ();
	
	/**
	 * 长度的数组
	 *
	 * @var array
	 */
	private $length = array ();
	
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
	 * 受订单价
	 *
	 * @var unknown_type
	 */
	private $unitPrice = array ();
	
	/**
	 * 批号
	 *
	 * @var unknown_type
	 */
	private $batchNumber = array ();
	
	/**
	 * 购单号
	 *
	 * @var unknown_type
	 */
	private $purchaseNumber = array ();
	
	/**
	 * 目的地
	 *
	 * @var unknown_type
	 */
	private $destination = array ();
	
	/**
	 * 库存地
	 *
	 * @var unknown_type
	 */
	private $storePlace = array ();
	
	/**
	 * 总量
	 *
	 * @var array
	 */
	
	private $filename = array();
	
	private $unitWeight = array();
	
	private $weight = array();
	
	private $remarks = array();
	
	private $count = array ();
	
	private $chukuDate = array();
	
	private $chukuNumber = array();
	
	private $checkoutBatch = array();
	
	private $certificateNumber = array(); 
	
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
		
		$query = "select * from(
				  (select * from sss_list where fileId = '{$fileId}') as listTable
					left outer join
				  (select filename,id from sss_list_file) as listFileTable
				  on listTable.fileId = listFileTable.id
				  )";
		DB::query ( $query );
		$result = DB::getResult ();
		
		$tableCols = array ('出库日期' => 'chukuDate',
							'车号|船号'=> 'chukuNumber',
							'材料代码' => 'materialCode', 
							'船级' => 'shipsClassification', 
							'材质' => 'material', 
							'厚' => 'thickness', '宽' => 'width', '长' => 'length',
							'单重' => 'unitWeight', 
							'数量' => 'count',
							'重量' => 'weight',
							'文件名' => 'filename', 
							'订单号' => 'orderNumber', 
							'订单子项号' => 'orderSubitemNumber', 
							'受订单价' => 'unitPrice', 
							'购单号' => 'purchaseNumber',
							'批号' => 'batchNumber', 
							'目的地' => 'destination', 
							'库存地' => 'storePlace',
							'备注' => 'remarks',
							'证书号' => 'certificateNumber',
							'结算批号' => 'checkoutBatch'		
							);
		$cols = array ();
		if (($row = $result->fetch_assoc ())!=NULL) {
			foreach ( $tableCols as $val ) {
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
		
		$this->sql = "select *,listFileTable.filename as filename from(
				  (select * from sss_list where fileId = '{$fileId}') as listTable
					left outer join
				  (select filename,id from sss_list_file) as listFileTable
				  on listTable.fileId = listFileTable.id
				  )";
		///////////////////////////////////////////////
		

		array_push ( $this->deleteSql, "UPDATE `sss_fache` SET `certificateNumber` = NULL, `checkoutBatch` = NULL 
			where ({$this->getNotAndChineseMaterialCodes()}) and 
			exists (select 1
					from sss_main 
					where ($colswhere) in (select $colswhere from sss_list where fileId = $fileId)
						and sss_main.materialCode = sss_fache.materialCode)" );
		
		array_push ( $this->deleteSql, "UPDATE `sss_fachuan` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
			where ({$this->getNotAndChineseMaterialCodes()}) and 
			exists (select 1
					from sss_main 
					where ($colswhere) in (select $colswhere from sss_list where fileId = $fileId)
						and sss_main.materialCode = sss_fachuan.materialCode)" );
		
		array_push ( $this->deleteSql, "UPDATE `sss_main` SET `certificateNumber` = NULL, `checkoutBatch` = NULL
			where ({$this->getNotAndChineseMaterialCodes()}) and 
				($colswhere) in (select $colswhere from sss_list where fileId = $fileId)" );
	
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
		while ( ($row = $this->result->fetch_assoc ())!=NULL ) {
			array_push ( $this->chukuNumber, $row ['chukuNumber'] );
			array_push ( $this->chukuDate, $row ['chukuDate'] );
			array_push ( $this->materialCode, $row ['materialCode'] );
			array_push ( $this->shipsClassification, $row ['shipsClassification'] );
			array_push ( $this->material, $row ['material'] );
			array_push ( $this->thickness, $row ['thickness'] );
			array_push ( $this->width, $row ['width'] );
			array_push ( $this->length, $row ['length'] );
			array_push ( $this->orderNumber, $row ['orderNumber'] );
			array_push ( $this->orderSubitemNumber, $row ['orderSubitemNumber'] );
			array_push ( $this->unitPrice, $row ['unitPrice'] );
			array_push ( $this->batchNumber, $row ['batchNumber'] );
			array_push ( $this->purchaseNumber, $row ['purchaseNumber'] );
			array_push ( $this->destination, $row ['destination'] );
			array_push ( $this->storePlace, $row ['storePlace'] );
			array_push ( $this->unitWeight, $row ['unitWeight'] );
			array_push ( $this->count, $row ['count'] );
			array_push ( $this->weight, $row ['count']*$row['unitWeight'] );
			array_push ( $this->filename, $row ['filename'] );
			array_push ( $this->checkoutBatch, $row ['checkoutBatch'] );
			array_push ( $this->certificateNumber, $row ['certificateNumber'] );
			array_push ( $this->remarks, $row ['remarks'] );
		}
	}
	
	public function getMaterialCode() {
		return $this->materialCode;
	}
	public function getMaterial() {
		return $this->material;
	}
	public function getShipsClassification() {
		return $this->shipsClassification;
	}
	public function getThickness() {
		return $this->thickness;
	}
	public function getWidth() {
		return $this->width;
	}
	public function getLength() {
		return $this->length;
	}
	
	public function getcount() {
		return $this->count;
	}
	
	public function getOrderNumber() {
		return $this->orderNumber;
	}
	
	public function getOrderSubitemNuber() {
		return $this->orderSubitemNumber;
	}
	
	public function getUnitPrice() {
		return $this->unitPrice;
	}
	
	public function getWeight(){
		return $this->weight;
	}
	
	public function getUnitWeight(){
		return $this->unitWeight;
	}
	
	public function getFilename(){
		return $this->filename;
	}
	
	public function getBatchNumber() {
		return $this->batchNumber;
	}
	
	public function getPurchaseNumber() {
		return $this->purchaseNumber;
	}
	
	public function getDestination() {
		return $this->destination;
	}
	
	public function getStorePlace() {
		return $this->storePlace;
	}
	
	public function getCheckoutBatch(){
		return $this->checkoutBatch;
	}
	
	public function getCertificateNumber(){
		return $this->certificateNumber;
	}
	
	public function getChukuNumber(){
		return $this->chukuNumber;
	}
	
	public function getChukuDate(){
		return $this->chukuDate;
	}
	
	public function getRemarks(){
		return $this->remarks;
	}
}
?>