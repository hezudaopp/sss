<?php
require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class SpecialFileStateAccountant extends Accountant {

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
	 * 材料代码的计划总数的数组
	 *
	 * @var array
	 */
	private $sumCount = array();

	/**
	 * 材料代码到未入库的总数的数组
	 *
	 * @var array
	 */
	private $unRukuCount = array();

	/**
	 * 库中数量的数组
	 *
	 * @var array
	 */
	private $kuzhongCount = array();

	/**
	 * 销售的数量的数组
	 *
	 * @var array
	 */
	private $soldCount = array();


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
	 * 结算批号
	 *
	 * @var unknown_type
	 */
	private $checkoutBatch = array();
	
	/**
	 * 证书号
	 *
	 * @var unknown_type
	 */
	private $certificateNumber = array();

	/**
	 * 物料号
	 */
	private $materialNumber = array();
	
	/**
	 * 发货批次
	 *
	 * @var unknown_type
	 */
	private $consignmentBatch = array();
	/**
	 * 构造函数，根据文件名称初始化sql语句。
	 *
	 * @param string $filename
	 */
	public function __construct($filename){
		$query = "select uploadTime from sss_main where filename = '$filename'";
		DB::query($query);
		$row = DB::getResult()->fetch_assoc();
		$uploadTime = $row['uploadTime'];
		
		/**
		 * 现在进行了修改，这里的sumCount表示的是这个表格中的材料代码总数
		 * 而beforeSumCount表示的是在这个表格上传之前上传总表中那些材料代码的sumCount
		 */
		/*
		$this->sql = "select materialCode, shipsClassification, material, thickness, width, length, sumCount,
	(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount,
	(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount,
	(coalesce(chukuCount, 0) + coalesce(directCount, 0)) as soldCount,
	orderNumber, orderSubitemNumber"; 

		

		*/
		$this->sql = "select materialCode, shipsClassification, material, thickness, width, length, 
		sumCount, beforeSumCount, coalesce(afterSumCount, 0) as afterSumCount, coalesce(rukuCount, 0) as rukuCount, coalesce(directCount, 0) as directCount,
	coalesce(chukuCount, 0) as chukuCount, orderNumber, orderSubitemNumber,
	unitPrice, batchNumber, purchaseNumber, destination, storePlace, certificateNumber, checkoutBatch, materialNumber, consignmentBatch
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace,
			certificateNumber, checkoutBatch, materialNumber, consignmentBatch, sum(`count`) as sumCount
			from sss_main
			where filename = '$filename'
			group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as beforeSumCount
			from sss_main
			where uploadTime < '$uploadTime'
			group by materialCode, orderNumber, orderSubitemNumber
			) as sumCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as afterSumCount
			from sss_main
			where uploadTime > '$uploadTime'
			group by materialCode, orderNumber, orderSubitemNumber
			) as afterSumCountTable
		using (materialCode, orderNumber, orderSubitemNumber)
		
		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库'
			group by materialCode, orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(halfChukuCount) as chukuCount
			from
				(
					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
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
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as directCount
			from sss_fache
			where phase='销售'
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		order by materialCode, orderNumber, orderSubitemNumber, thickness, width, length;";
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
		while($row = $this->result->fetch_assoc()){
			array_push($this->materialCode, $row['materialCode']);
			array_push($this->shipsClassification, $row['shipsClassification']);
			array_push($this->material, $row['material']);
			array_push($this->thickness, $row['thickness']);
			array_push($this->width, $row['width']);
			array_push($this->length, $row['length']);
			
			
			
			array_push($this->sumCount, intval($row['sumCount']));
			
			$sold = $row['chukuCount'] + $row['directCount'];
			$kuzhong = $row['rukuCount'] - $row['chukuCount'];
			if($row['afterSumCount'] != 0){
				if($sold >= $row['beforeSumCount'] + $row['sumCount']){
					array_push($this->soldCount, $row['sumCount']);
					array_push($this->kuzhongCount, 0);
					array_push($this->unRukuCount, 0);
				}else if($sold >= $row['beforeSumCount']){
					$theSold = $sold - $row['beforeSumCount'];
					array_push($this->soldCount, $theSold);
					$remain = $row['sumCount'] - $theSold;
					if( $kuzhong >= $remain){
						array_push($this->kuzhongCount, $remain);
						array_push($this->unRukuCount, 0);
					}else{
						array_push($this->kuzhongCount, $kuzhong);
						array_push($this->unRukuCount, $remain - $kuzhong);
					}
				} else{
					if($kuzhong >= $row['beforeSumCount'] - $sold){
						array_push($this->soldCount, 0);
						$theKuzhong = $kuzhong - ($row['beforeSumCount'] - $sold);
						array_push($this->kuzhongCount, $theKuzhong);
						array_push($this->unRukuCount, $row['sumCount'] - $theKuzhong);
					} else{
						array_push($this->soldCount, 0);
						array_push($this->kuzhongCount, 0);
						array_push($this->unRukuCount, $row['sumCount']);
					}
				}
			}else{
				if($sold >= $row['beforeSumCount']){
					array_push($this->soldCount, $sold - $row['beforeSumCount']);
					array_push($this->kuzhongCount, $kuzhong);
				}else{
					array_push($this->soldCount, 0);
					$remain = $row['beforeSumCount'] - $sold;
					echo $remain;
					if($remain >= $kuzhong){
						array_push($this->kuzhongCount, 0);
					}else{
						array_push($this->kuzhongCount, $kuzhong - $remain);
					}
				}
				
				$theUnRukuCount = $row['sumCount'] + $row['beforeSumCount'] - $row['directCount'] - $row['rukuCount'];
				if($theUnRukuCount >= 0){
					array_push($this->unRukuCount, $theUnRukuCount);
				}else{
					array_push($this->unRukuCount, 0);
				}
				
			}
			
			
			/*array_push($this->kuzhongCount, intval($row['kuzhongCount']));
			array_push($this->unRukuCount, intval($row['unRukuCount']));
			array_push($this->soldCount, intval($row['soldCount']));*/
			
			array_push($this->orderNumber, $row['orderNumber']);
			array_push($this->orderSubitemNumber, $row['orderSubitemNumber']);
			array_push($this->unitPrice,$row['unitPrice']);
			array_push($this->batchNumber,$row['batchNumber']);
			array_push($this->purchaseNumber,$row['purchaseNumber']);
			array_push($this->destination,$row['destination']);
			array_push($this->storePlace,$row['storePlace']);
			array_push($this->checkoutBatch,$row['checkoutBatch']);
			array_push($this->certificateNumber,$row['certificateNumber']);
			array_push($this->materialNumber, $row['materialNumber']);
			array_push($this->consignmentBatch, $row['consignmentBatch']);
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
	public function getUnRukuCount(){
		return $this->unRukuCount;
	}
	public function getSoldCount(){
		return $this->soldCount;
	}

	public function getOrderNumber(){
		return $this->orderNumber;
	}

	public function getOrderSubitemNuber(){
		return $this->orderSubitemNumber;
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
	
	public function getCheckoutBatch(){
		return $this->checkoutBatch;
	}
	
	public function getCertificateNumber(){
		return $this->certificateNumber;
	}
	
	public function getMaterialNumber(){
		return $this->materialNumber;
	}
	
	public function getConsignmentBatch(){
		return $this->consignmentBatch;
	}
}
?>