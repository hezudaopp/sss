<?php

require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class WeidongAccountant extends Accountant {

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
	 * 单重的数组
	 *
	 * @var array
	 */
	private $unitWeight = array();

	/**
	 * 未入库的数组
	 *
	 * @var array
	 */
	private $unRukuCount = array();

	/**
	 * 构造函数
	 *
	 */
	public function __construct(){

		$this->sql = "select materialCode, shipsClassification, material, thickness, width, length, unitWeight,
	(sumCount - coalesce(rukuCount, 0) - coalesce(directCount, 0)) as unRukuCount
from
		(
			(
			select materialCode, sum(`count`) as sumCount, shipsClassification, material, width, thickness, length, unitWeight
			from sss_main
			group by materialCode
			)
			as sumCountTable

		left join

			(
			select materialCode, sum(`count`) as rukuCount
			from sss_fache
			where phase = '入库'
			group by materialCode
			) as rukuCountTable
		using (materialCode)
		left join

			(
			select materialCode, sum(`count`) as directCount
			from sss_fache
			where phase='销售'
			group by materialCode
			)
			as directCountTable

		using (materialCode)
		)
		order by shipsClassification, material, thickness, width, length;";
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
			array_push($this->materialm, $row['material']);
			array_push($this->thickness, $row['thickness']);
			array_push($this->width, $row['width']);
			array_push($this->length, $row['length']);
			array_push($this->unitWeight, $row['unitWeight']);
			array_push($this->unRukuCount, $row['unRukuCount']);
		}

	}

	public function getUnRukuCount(){
		return $this->unRukuCount;
	}
	public function getUnitWeight(){
		return $this->unitWeight;
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

}
?>