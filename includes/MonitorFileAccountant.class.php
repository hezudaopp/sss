<?php
require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class MonitorFileAccountant extends Accountant {

	/**
	 * 文件Id
	 *
	 * @var int
	 */
	private $fileId = null;
	
	/**
	 * 监控中在总表中查找不到的查询语句
	 * @var string
	 */
	private $notInMainSql;
	
	/**
	 * 材料代码的数组
	 *
	 * @var array
	 */
	private $materialCode = array();

	/**
	 * 船级的数组
	 *
	 * @var array
	 */
	private $shipsClassification = array();

	/**
	 * 材料的数组
	 *
	 * @var array
	 */
	private $material = array();

	/**
	 * 厚度的数组
	 *
	 * @var array
	 */
	private $thickness = array();

	/**
	 * 宽度的数组
	 *
	 * @var array
	 */
	private $width = array();

	/**
	 * 长度的数组
	 *
	 * @var array
	 */
	private $length = array();
	
	
	/**
	 * 订单号
	 *
	 * @var array
	 */
	private $orderNumber = array();

	/**
	 * 订单子项号
	 *
	 * @var array
	 */
	private $orderSubitemNumber = array();
	
	/**
	 * 受订单价
	 *
	 * @var unknown_type
	 */
	private $unitPrice = array();

	/**
	 * 批号
	 *
	 * @var unknown_type
	 */
	private $batchNumber = array();
	
	/**
	 * 购单号
	 *
	 * @var unknown_type
	 */
	private $purchaseNumber = array();
	
	/**
	 * 目的地
	 *
	 * @var unknown_type
	 */
	private $destination = array();
	
	/**
	 * 库存地
	 *
	 * @var unknown_type
	 */
	private $storePlace = array();
	
	/**
	 * 证书号
	 *
	 * @var unknown_type
	 */
	private $certificateNumber = array();
	
	/**
	 * 结算清单
	 *
	 * @var unknown_type
	 */
	private $checkoutBatch = array();
	
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
	 * 总量
	 *
	 * @var array
	 */
	private $sumCount = array();
	
	/**
	 * 库中数量
	 *
	 * @var array
	 */
	private $kuzhongCount = array();
	
	/**
	 * 预计数量(也就是监控表格中的数量)
	 *
	 * @var array
	 */
	private $precount = array();

	/**
	 * 实际未完成的数量
	 *
	 * @var array
	 */
	private $unfinishedCount = array();
	
	/**
	 * 未入库数量
	 *  
	 * @var array
	 */
	private $unRukuCount = array();
	
	/**
	 * 出库和销售数量和
	 * @var array
	 */
	private $soldCount = array();

	private $deleteSql = array();
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
	
	//以下成员变量是对未能在总表中找到对应数据的监控条目
	
	private $notInMainMaterialCode = array();
	
	private $notInMainShipsClassification = array();
	
	private $notInMainMaterial =  array();
	
	private $notInMainThickness = array();
	
	private $notInMainWidth = array();
	
	private $notInMainLength = array();
	
	private $notInMainCount = array();
	
	private $notInMainOrderNumber = array();
	
	private $notInMainOrderSubitemNumber = array();
	
	private $notInMainUnitPrice = array();
	
	private $notInMainBatchNumber = array();
	
	private $notInMainPurchaseNumber = array();
	
	private $notInMainDestination = array();
	
	private $notInMainStorePlace = array();
	
	private $notInMainCertificateNumber = array();
	
	private $notInMainCheckoutBatch = array();
	
	private $notInMainMaterialNumber = array();
	
	private $notInMainConsignmentBatch = array();
	
	public function __construct($fileId){
		
		$query = "select * from sss_monitor where fileId = '{$fileId}'";
		DB::query($query);
		$result = DB::getResult();
		
		$tableCols = array(
				'材料代码' 	=> 	'materialCode',
				'船级' 		=> 	'shipsClassification',
				'材质' 		=> 	'material',
				'厚' 		=> 	'thickness',
				'宽' 		=> 	'width',
				'长' 		=> 	'length',
				'数量' 		=> 	'count',
				'订单号' 	=> 	'orderNumber',
				'订单子项号' 	=> 	'orderSubitemNumber',
				'受订单价'	=>	'unitPrice',
				'批号'		=>	'batchNumber',
				'购单号'		=>	'purchaseNumber',
				'目的地'		=>	'destination',
				'库存地'		=>	'storePlace',
				'证书号'		=>  'certificateNumber',
				'结算批次'	=>  'checkoutBatch',
				'物料号'		=>  'materialNumber',
				'发货批次'	=>  'consignmentBatch'
		);
		$cols = array();
		if(($row = $result->fetch_assoc())!=NULL){
			foreach($tableCols as $val){
				if(isset($row[$val]) and !empty($row[$val])){
					array_push($cols, $val);
				}
			}
		}
		
		$this->haveMc = false;
		if(in_array('materialCode', $cols)){
			$colswhere = 'materialCode';
			$this->haveMc = true;
		}
		
		$colswhere = join(',', $cols);
		
		if(empty($colswhere)){
			$this->sql = null;
			return;
		}
		
		//如果有count怎么办？
		//如果没有count又该怎么办？
		
		$this->sql = "select materialCode, shipsClassification, material, thickness, width, length,
		sumCount, count, directCount, coalesce(rukuCount, 0) rukuCount, coalesce(chukuCount, 0) + coalesce(directCount,0) as soldCount, coalesce(rukuCount, 0) - coalesce(chukuCount, 0) as kuzhongCount, 
		sumCount - coalesce(chukuCount, 0) - coalesce(directCount,0) as unfinishedCount, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		certificateNumber, checkoutBatch, materialNumber, consignmentBatch
from
		(
			(
			select materialCode, shipsClassification, material, thickness, width, length, count, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount,
			certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_main
			where ($colswhere) in (select $colswhere from sss_monitor where fileId = $fileId)
			group by materialCode, orderNumber, orderSubitemNumber
			) as mcTable
			
		left join

			(
			select sum(`count`) as rukuCount ,materialCode, orderNumber, orderSubitemNumber
			from sss_fache
			where phase = '入库'
			group by materialCode, orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join
			(
			select sum(`newCount`) as chukuCount, materialCode, orderNumber, orderSubitemNumber
			from
				(
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all
					
					(select materialCode, sum(`count`) as newCount, orderNumber, orderSubitemNumber
					from sss_fachuan
					group by materialCode, orderNumber, orderSubitemNumber
					)
				)as nativeChukuCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join

			(
			select sum(`count`) as directCount, materialCode, orderNumber, orderSubitemNumber
			from sss_fache
			where phase='销售'
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		order by materialCode, orderNumber, orderSubitemNumber, shipsClassification, material, thickness, width, length, unitPrice, batchNumber, purchaseNumber, destination, storePlace";
		
		
		$this->notInMainSql = "select * from sss_monitor where fileId = $fileId
		and ($colswhere) not in (select $colswhere from sss_main)";
		
		
		///////////////////////////////////////////////

			array_push($this->deleteSql,
			"delete from sss_fache 
			where ({$this->getNotAndChineseMaterialCodes()}) and 
			exists (select 1
					from sss_main 
					where ($colswhere) in (select $colswhere from sss_monitor where fileId = $fileId)
						and sss_main.materialCode = sss_fache.materialCode)"
			);
			
			array_push($this->deleteSql,
			"delete from sss_fachuan
			where ({$this->getNotAndChineseMaterialCodes()}) and 
			exists (select 1
					from sss_main 
					where ($colswhere) in (select $colswhere from sss_monitor where fileId = $fileId)
						and sss_main.materialCode = sss_fachuan.materialCode)"
			);
			
			array_push($this->deleteSql,
			"delete from sss_main
			where ({$this->getNotAndChineseMaterialCodes()}) and 
				($colswhere) in (select $colswhere from sss_monitor where fileId = $fileId)");
			
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
			array_push($this->materialCode, $row['materialCode']);
			array_push($this->shipsClassification, $row['shipsClassification']);
			array_push($this->material, $row['material']);
			array_push($this->thickness, $row['thickness']);
			array_push($this->width, $row['width']);
			array_push($this->length, $row['length']);			
			array_push($this->orderNumber, $row['orderNumber']);
			array_push($this->orderSubitemNumber, $row['orderSubitemNumber']);
			array_push($this->unitPrice,$row['unitPrice']);
			array_push($this->batchNumber,$row['batchNumber']);
			array_push($this->purchaseNumber,$row['purchaseNumber']);
			array_push($this->destination,$row['destination']);
			array_push($this->storePlace,$row['storePlace']);
			array_push($this->certificateNumber,$row['certificateNumber']);
			array_push($this->checkoutBatch, $row['checkoutBatch']);
			array_push($this->materialNumber, $row['materialNumber']);
			array_push($this->consignmentBatch, $row['consignmentBatch']);
			array_push($this->unRukuCount, $row['sumCount'] - $row['rukuCount'] - $row['directCount']);
			array_push($this->kuzhongCount, $row['kuzhongCount']);
			if($row['unfinishedCount'] == null){
				$row['unfinishedCount'] = 0;
			}
			if($row['sumCount'] == null){
				$row['sumCount'] = 0;
			}
			array_push($this->unfinishedCount, $row['unfinishedCount']);
			array_push($this->sumCount, $row['sumCount']);
			array_push($this->soldCount, $row['soldCount']);
			if($this->haveMc){
				array_push($this->precount, $row['count'] == null ? 0 : $row['count']);
			}else{
				array_push($this->precount, '空');
			}
			
		}
		
	$this->result = DB::query($this->notInMainSql);
		if($this->result == false){
			return;
		}
		while(($row = $this->result->fetch_assoc())!=NULL){
			array_push($this->notInMainMaterialCode, $row['materialCode']);
			array_push($this->notInMainShipsClassification, $row['shipsClassification']);
			array_push($this->notInMainMaterial, $row['material']);
			array_push($this->notInMainThickness, $row['thickness']);
			array_push($this->notInMainWidth, $row['width']);
			array_push($this->notInMainLength, $row['length']);			
			array_push($this->notInMainOrderNumber, $row['orderNumber']);
			array_push($this->notInMainOrderSubitemNumber, $row['orderSubitemNumber']);
			array_push($this->notInMainUnitPrice,$row['unitPrice']);
			array_push($this->notInMainBatchNumber,$row['batchNumber']);
			array_push($this->notInMainPurchaseNumber,$row['purchaseNumber']);
			array_push($this->notInMainDestination,$row['destination']);
			array_push($this->notInMainStorePlace,$row['storePlace']);
			array_push($this->notInMainCertificateNumber,$row['certificateNumber']);
			array_push($this->notInMainCheckoutBatch, $row['checkoutBatch']);
			array_push($this->notInMainMaterialNumber, $row['materialNumber']);
			array_push($this->notInMainConsignmentBatch, $row['consignmentBatch']);
			}			
	}
	
	public function getMaterialCode(){
		return $this->materialCode;
	}
	public function getMaterial(){
		return $this->material;
	}
	public function getShipsClassification(){
		return $this->shipsClassification;
	}
	public function getThickness(){
		return $this->thickness;
	}
	public function getWidth(){
		return $this->width;
	}
	public function getLength(){
		return $this->length;
	}
	public function getKuzhongCount(){
		return $this->kuzhongCount;
	}
	public function getSumCount(){
		return $this->sumCount;
	}

	public function getPrecount(){
		return $this->precount;
	}
	
	public function getUnfinishedCount(){
		return $this->unfinishedCount;
	}
	
	public function getUnRukuCount(){
		return $this->unRukuCount;
	}
	
	public function getOrderNumber(){
		return $this->orderNumber;
	}

	public function getOrderSubitemNuber(){
		return $this->orderSubitemNumber;
	}
	
	public function getSoldCount(){
		return $this->soldCount;
	}
	
	public function getUnitPrice(){
		return $this->unitPrice;
	}
	
	public function getBatchNumber(){
		return $this->batchNumber;
	}
	
	public function getPurchaseNumber(){
		return $this->purchaseNumber;
	}
	
	public function getDestination(){
		return $this->destination;
	}
	public function getStorePlace(){
		return $this->storePlace;
	}
	public function getCertificateNumber(){
		return $this->certificateNumber;
	}
	public function getCheckoutBatch(){
		return $this->checkoutBatch;
	}
	
	public function getMaterialNumber(){
		return $this->materialNumber;
	}
	
	public function getConsignmentBatch(){
		return $this->consignmentBatch;
	}
	
	public function getNotInMainMaterialCode(){
		return $this->notInMainMaterialCode;
	}
	public function getNotInMainMaterial(){
		return $this->notInMainMaterial;
	}
	public function getNotInMainShipsClassification(){
		return $this->notInMainShipsClassification;
	}
	public function getNotInMainThickness(){
		return $this->notInMainThickness;
	}
	public function getNotInMainWidth(){
		return $this->notInMainWidth;
	}
	public function getNotInMainLength(){
		return $this->notInMainLength;
	}

	public function getNotInMainOrderNumber(){
		return $this->notInMainOrderNumber;
	}

	public function getNotInMainOrderSubitemNuber(){
		return $this->notInMainOrderSubitemNumber;
	}
	
	public function getNotInMainUnitPrice(){
		return $this->notInMainUnitPrice;
	}
	
	public function getNotInMainBatchNumber(){
		return $this->notInMainBatchNumber;
	}
	
	public function getNotInMainPurchaseNumber(){
		return $this->notInMainPurchaseNumber;
	}
	
	public function getNotInMainDestination(){
		return $this->notInMainDestination;
	}
	public function getNotInMainStorePlace(){
		return $this->notInMainStorePlace;
	}
	public function getNotInMainCertificateNumber(){
		return $this->notInMainCertificateNumber;
	}
	public function getNotInMainCheckoutBatch(){
		return $this->notInMainCheckoutBatch;
	}
	
	public function getNotInMainMaterialNumber(){
		return $this->notInMainMaterialNumber;
	}
	
	public function getNotInMainConsignmentBatch(){
		return $this->notInMainConsignmentBatch;
	}
}
?>