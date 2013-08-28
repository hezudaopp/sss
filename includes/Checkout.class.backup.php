<?php

require_once ('../includes/DB.class.php');

class Checkout {
	private $fromDate = "";
	private $toDate = "";
	
	private $facheWheres = "";
	private $fachuanWheres = "";
	
	private $chukuDates = array ();
	private $chukuNumbers = array ();
	private $materialCodes = array ();
	private $ships = array ();
	private $materials = array ();
	private $thicknesses = array ();
	private $widths = array ();
	private $lengths = array ();
	private $counts = array ();
	private $soldCounts = array();
	private $unitWeights = array ();
	private $weights = array ();
	private $orderNumbers = array ();
	private $orderSubitemNumbers = array ();
	private $unitPrices = array ();
	private $purchaseNumbers = array ();
	private $batchNumbers = array ();
	private $destinations = array ();
	private $storePlaces = array ();
	private $remarks = array();
	private $filenames = array();
	private $wheres = array();
	

	public function __construct($from, $to) {
		$this->fromDate = $from;
		$this->toDate = $to;
		
		if ($from == NULL) {
			$this->fromDate = "1000/10/10";
		}
		if ($to == NULL) {
			$this->toDate = "2100/10/10";
		}
		
		$this->facheWheres .= " facheDate >= '" . $this->fromDate . "' and facheDate <= '" . $this->toDate . "'";
		$this->fachuanWheres .= " fachuanDate >= '" . $this->fromDate . "' and fachuanDate <= '" . $this->toDate . "'";
	
	}
	
	public function findCommon($type = "unsettled") {
	
		try {
			//创建视图
			//查找这段时间内出库的数量（包括发车出库数量和发船数量）,
			//默认排序方式为材料代码按照从大到小的方式排序，出库时间按照从大到小的方式（也就是从晚到早的方式排序）。
			//为的是从尾部计算，对计算完的随时pop_back
			$query = "select faDate, directDate, faNumber, directNumber, materialCode, shipsClassification, material, thickness, width, length, 
		sumCount, coalesce(directCount, 0) as directCount, coalesce(chukuCount, 0) as chukuCount, 
		unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5 
		unitPrice, batchNumber, purchaseNumber, destination, storePlace, filename
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length, 
			unitWeight, orderNumber, orderSubitemNumber, remark1, remark2, remark3, remark4, remark5,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount, filename
			from sss_main ";
			    
			if ($type == "unsettled") {
				$query .= "where certificateNumber IS NULL ";
			}
			elseif ($type == "settled") {
				$query .= "where certificateNumber IS NOT NULL ";
			}
			
			$query .= "group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

		left join
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(halfChukuCount) as chukuCount, faDate, faNumber
			from
				(
					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount,
					max(facheDate) as faDate, facheNumber as faNumber
					from sss_fache
					where phase = '出库' and $this->facheWheres
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount,
					max(fachuanDate) as faDate,fachuanNumber as faNumber
					from sss_fachuan 
					where $this->fachuanWheres
					group by materialCode, orderNumber, orderSubitemNumber
					)
				)as nativeChukuCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber,
			sum(`count`) as directCount, max(facheDate) as directDate, facheNumber as directNumber
			from sss_fache
			where phase='销售' and $this->facheWheres
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		
		where sumCount <= chukuCount + directCount
		or sumCount <= chukuCount or sumCount <= directCount
		order by materialCode, orderNumber, orderSubitemNumber, thickness, width, length;";
			
		$result=DB::query ( $query );
		
		} catch ( Exception $e ) {
			echo $e;
			die ();
		}
		
		$i = 0;
		while ($chuku=$result->fetch_assoc()) {
			$chukuDates1 = array();
			$chukuNumbers1 = array();
			$soldCount = $chuku['chukuCount']+$chuku['directCount'];
			array_push ( $this->soldCounts, $soldCount );
			array_push ( $this->materialCodes, $chuku ['materialCode'] );
			array_push ( $this->ships, $chuku ['shipsClassification'] );
			array_push ( $this->materials, $chuku ['material'] );
			array_push ( $this->thicknesses, $chuku ['thickness'] );
			array_push ( $this->widths, $chuku ['width'] );
			array_push ( $this->lengths, $chuku ['length'] );
			array_push ( $this->counts, $chuku ['sumCount'] );
			array_push ( $this->unitWeights, $chuku ['unitWeight'] );
			array_push ( $this->weights, $chuku ['sumCount']*$chuku['unitWeight'] );
			array_push ( $this->orderNumbers, $chuku ['orderNumber'] );
			array_push ( $this->orderSubitemNumbers, $chuku ['orderSubitemNumber'] );
			array_push ( $this->unitPrices, $chuku ['unitPrices'] );
			array_push ( $this->purchaseNumbers, $chuku ['purchaseNumber'] );
			array_push ( $this->batchNumbers, $chuku ['batchNumber'] );
			array_push ( $this->storePlaces, $chuku ['storePlace'] );
			array_push ( $this->destinations, $chuku ['destination'] );
			array_push ( $this->filenames, $chuku['filename']);
			if (! empty ( $chuku ['remark2'] )) {
				$chuku ['remark1'] = '1. ' . $chuku ['remark1'] . '<br />2. ' . $chuku ['remark2'];
			}
			if (! empty ( $chuku ['remark3'] )) {
				$chuku ['remark1'] .= '<br />3. ' . $chuku ['remark3'];
			}
			if (! empty ( $chuku ['remark4'] )) {
				$chuku ['remark1'] .= '<br />4. ' . $chuku ['remark4'];
			}
			if (! empty ( $chuku ['remark5'] )) {
				$chuku ['remark1'] .= '<br />5. ' . $chuku ['remark5'];
			}
			
			array_push ( $this->remarks, $chuku ['remark1'] );
			if(!empty($chuku['faDate'])){
				array_push ( $chukuDates1, '出库:'.$chuku ['faDate'] );
				array_push ( $chukuNumbers1, $chuku ['faNumber'] );
			}
			if(!empty($chuku['directDate'])){
				array_push ( $chukuDates1, '销售:'.$chuku ['directDate'] );
				array_push ( $chukuNumbers1, $chuku ['directNumber'] );
			}
			
			$this->chukuDates[$i] = $chukuDates1;
			$this->chukuNumbers[$i] = $chukuNumbers1;
			
			$i++;
		}
	}

	
	public function getMaterialCode() {
		return $this->materialCodes;
	}
	
	public function getShips() {
		return $this->ships;
	}
	
	public function getMaterial() {
		return $this->materials;
	}
	
	public function getThickness() {
		return $this->thicknesses;
	}
	
	public function getWidth() {
		return $this->widths;
	}
	
	public function getLength() {
		return $this->lengths;
	}
	
	public function getCount() {
		return $this->counts;
	}
	public function getSoldCount() {
		return $this->soldCounts;
	}
	
	public function getUnitWeight() {
		return $this->unitWeights;
	}
	
	public function getWeight() {
		return $this->weights;
	}
	
	public function getChukuDate() {
		return $this->chukuDates;
	}
	
	public function getChukuNumber() {
		return $this->chukuNumbers;
	}
	
	public function getOrderNumber() {
		return $this->orderNumbers;
	}
	
	public function getOrderSubitemNumber() {
		return $this->orderSubitemNumbers;
	}
	
	public function getUnitPrice() {
		return $this->unitPrices;
	}
	
	public function getBatchNumber() {
		return $this->batchNumbers;
	}
	
	public function getPurchaseNumber() {
		return $this->purchaseNumbers;
	}
	
	public function getDestination() {
		return $this->destinations;
	}
	
	public function getStoreplace() {
		return $this->storePlaces;
	}

	public function getRemarks(){
		return $this->remarks;
	}
	
	public function getFilenames(){
		return $this->filenames;
	}
}



?>