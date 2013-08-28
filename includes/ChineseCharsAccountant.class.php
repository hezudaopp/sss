<?php

require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");


/**
 * 原来ChineseCharsAccountant只用来操作汉字材料代码（不包括加片），
 * 现在包括加片了，使用一个type='jiapian'确定他的加片身份
 * 默认为非加片的汉字
 *
 */
class ChineseCharsAccountant extends Accountant {

	/**
	 * 表示这个ChineseCharsAccountant是加片还是其他类别？
	 *
	 * @var enum('jiapian')
	 */
	private $type = null;
	
	
	/**
	 * 是否显示文件名
	 *
	 * @var boolean
	 */
	private $showFilename = false;
	
	
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
	 * 未入库数量的数组
	 *
	 * @var array
	 */
	private $unRukuCount = array();

	/**
	 * 库中的数量的数组
	 *
	 * @var array
	 */
	private $kuzhongCount = array();

	/**
	 * 直接销售的数量的数组
	 *
	 * @var array
	 */
	private $soldCount = array();

	/**
	 * 总量的数组
	 *
	 * @var array
	 */
	private $sumCount = array();


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
	 * 文件名
	 *
	 * @var array
	 */
	private $filename = array();
	
	/**
	 * id最原始使用第一个select子查询得到的，因此他的意义就跟这个子查询有很大关系
	 * 目前，也就是我添加这个id的意义，是想让他表示某个东西，相同的东西在现实的时候id相同
	 *
	 * @var int
	 */
	private $id = array();
	
	
	/**
	 * 构造函数
	 *
	 */
	public function __construct($showFilename = false, $type = null){
		if($type != null){
			$this->type = $type;
		}
		$this->showFilename = $showFilename;

		$where = $this->getIsOrChineseMaterialCodes(true);
		
		if($this->type == 'jiapian'){
			$where = 'materialCode like "%加片%"';
		}
		if($this->type == 'biangang'){
			$where = 'materialCode like "%扁钢%" or materialCode like "%舾装%"';
		}
		
		
		$this->sql = "select materialCode, shipsClassification, material, thickness, width, length,
		sumCount, (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
		(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount,
		(coalesce(chukuCount, 0) + coalesce(directCount, 0)) as soldCount,
		 orderNumber, orderSubitemNumber,unitPrice, batchNumber, purchaseNumber, destination, storePlace, filename,
		 certificateNumber, checkoutBatch, materialNumber, consignmentBatch
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount, filename,
			certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_main
			where $where
			group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库' and ($where)
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
					where phase = '出库' and ({$where})
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fachuan
					where ($where)
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
			where phase='销售' and ($where)
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		order by materialCode, orderNumber, orderSubitemNumber, thickness, width, length;
";
	}

	/**
	 * 执行查询命令，并整理查询的结果
	 *
	 */
	public function execute(){
		$this->result = DB::query($this->sql);
		//echo "<pre>".$this->sql."</pre>";
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
			array_push($this->kuzhongCount, $row['kuzhongCount']);
			array_push($this->unRukuCount, $row['unRukuCount']);
			array_push($this->soldCount, $row['soldCount']);
			array_push($this->sumCount, $row['sumCount']);
			array_push($this->orderNumber, $row['orderNumber']);
			array_push($this->orderSubitemNumber, $row['orderSubitemNumber']);
			array_push($this->unitPrice,$row['unitPrice']);
			array_push($this->batchNumber,$row['batchNumber']);
			array_push($this->purchaseNumber,$row['purchaseNumber']);
			array_push($this->destination,$row['destination']);
			array_push($this->storePlace,$row['storePlace']);
			//array_push($this->id, $row['id']);
			if($this->showFilename){
				array_push($this->filename, $row['filename']);
			}
			array_push($this->certificateNumber, $row['certificateNumber']);
			array_push($this->checkoutBatch,$row['checkoutBatch']);
			array_push($this->materialNumber, $row['materialNumber']);
			array_push($this->consignmentBatch, $row['consignmentBatch']);
		}
	}

	public function getSumCount(){
		return $this->sumCount;
	}
	public function getKuzhongCount(){
		return $this->kuzhongCount;
	}
	public function getUnRukuCount(){
		return $this->unRukuCount;
	}
	public function getSoldCount(){
		return $this->soldCount;
	}
	public function getLength(){
		return $this->length;
	}
	public function getWidth(){
		return $this->width;
	}
	public function getThickness(){
		return $this->thickness;
	}
	public function getMaterial(){
		return $this->material;
	}
	public function getShipsClassification(){
		return $this->shipsClassification;
	}
	public function getMaterialCode(){
		return $this->materialCode;
	}

	public function getOrderNumber(){
		return $this->orderNumber;
	}

	public function getOrderSubitemNumber(){
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
	
	public function getFilename(){
		return $this->filename;
	}
	
	public function getId(){
		return $this->id;
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
}
?>