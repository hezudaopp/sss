<?php

require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class JiapianAccountant extends Accountant {

	/**
	 * 是否显示文件名
	 *
	 * @var string
	 */
	private $showFilename = false;
	
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
	 * 材料代码，虽然材料代码都是“加片”，但这个是佳片的数组
	 *
	 * @var array
	 */
	private $materialCode = array();

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
	 * 显示文件名称
	 *
	 * @var array
	 */
	private $filename = array();
	
	
	private $id = array();
	/**
	 * 构造函数
	 *
	 */
	public function __construct($showFilename = false){
		$this->showFilename = $showFilename;
		
		$this->sql = "select shipsClassification, material, thickness, width, length, 
		sumCount, (sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount, 
		(coalesce(rukuCount, 0) - coalesce(chukuCount, 0)) as kuzhongCount,
		(coalesce(chukuCount, 0) + coalesce(directCount, 0)) as soldCount,
		 orderNumber, orderSubitemNumber, filename
from
		(
			(
			select shipsClassification, material, width, thickness, length, orderNumber, orderSubitemNumber, sum(`count`) as sumCount, filename
			from sss_main
			where materialCode = '加片'
			group by orderNumber, orderSubitemNumber
			) as mcInfosTable

		left join

			(
			select orderNumber, orderSubitemNumber, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库' and materialCode = '加片'
			group by orderNumber, orderSubitemNumber
			) as rukuCountTable
		using (orderNumber, orderSubitemNumber)

		left join
			(
			select orderNumber, orderSubitemNumber, sum(halfChukuCount) as chukuCount
			from
				(
					(
					select orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fache
					where phase = '出库' and materialCode = '加片'
					group by orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fachuan
					where materialCode = '加片'
					group by orderNumber, orderSubitemNumber
					)
				)as nativeChukuCountTable
			group by orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (orderNumber, orderSubitemNumber)

		left join

			(
			select orderNumber, orderSubitemNumber, sum(`count`) as directCount
			from sss_fache
			where phase='销售' and materialCode = '加片'
			group by orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (orderNumber, orderSubitemNumber)
		)
		order by orderNumber, orderSubitemNumber, thickness, width, length;
";
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
			array_push($this->materialCode, '加片');
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
			//array_push($this->id, $row['id']);
			if($this->showFilename){
				array_push($this->filename, $row['filename']);
			}
		}
	}

	public function getMaterialCode(){
		return $this->materialCode;
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

	public function getOrderNumber(){
		return $this->orderNumber;
	}

	public function getOrderSubitemNumber(){
		return $this->orderSubitemNumber;
	}
	
	public function getFilename(){
		return $this->filename;
	}
}
?>