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
	private $materialNumbers = array();
	private $consignmentBatchs = array();
	private $destinations = array ();
	private $storePlaces = array ();
	private $remarks = array();
	private $filenames = array();
	private $checkoutBatchs = array();
	private $certificateNumbers = array();
	private $wheres = array();
	
	/**
	 * 构造函数，facheDate和fachuanDate：出库，销售和发船的日期。
	 * 如果相同材料代码，订单号，订单子项号有多条数据，开始和结束日期都以最晚的那条计算
	 * @param $from
	 * @param $to
	 * @return unknown_type
	 */
	public function __construct($from, $to) {
		$this->fromDate = $from;
		$this->toDate = $to;
		
		if ($from == NULL) {
			$this->fromDate = "1000/10/10";
		}
		if ($to == NULL) {
			$this->toDate = "2100/10/10";
		}
		
		$this->facheWheres .= " max(facheDate) >= '" . $this->fromDate . "' and max(facheDate) <= '" . $this->toDate . "'";
		$this->fachuanWheres .= " max(fachuanDate) >= '" . $this->fromDate . "' and max(fachuanDate) <= '" . $this->toDate . "'";
	
	}
	
	/**
	 * 通过传递来的参数$type查询完成数据的信息（是否结算）
	 * @param $type
	 * @return unknown_type
	 */
	public function findCommon($type = "unsettled") {
		
	/*找到所有已经完成*/
	try {
	$query = "select materialCode,orderNumber, orderSubitemNumber,
	 sumCount, coalesce(directCount, 0) as directCount, coalesce(chukuCount, 0) as chukuCount 
		  
from
		(
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as sumCount
			from sss_main ";
			    
			if ($type == "unsettled") {
				$query .= "where certificateNumber IS NULL or certificateNumber = '' ";
			}
			elseif ($type == "settled") {
				$query .= "where certificateNumber IS NOT NULL and certificateNumber != '' ";
			}
			
			$query .= "group by materialCode, orderNumber, orderSubitemNumber
			) as mcInfosTable

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
					having $this->facheWheres
					)
					
					union all

					(
					select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fachuan 
					group by materialCode, orderNumber, orderSubitemNumber
					having $this->fachuanWheres
					)
				)as nativeChukuCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber,
			sum(`count`) as directCount
			from sss_fache
			where phase='销售'
			group by materialCode, orderNumber, orderSubitemNumber
			having $this->facheWheres
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
		where sumCount <= chukuCount + directCount
		or sumCount <= chukuCount or sumCount <= directCount
		order by materialCode, orderNumber, orderSubitemNumber;";
			
		$soldResult=DB::query ( $query );
		
		} catch ( Exception $e ) {
			echo $e;
			die ();
		}
		
		$allCode = array();
		while (($sold=$soldResult->fetch_assoc())!=NULL) {
			array_push ( $allCode, $sold ['materialCode'].','.$sold['orderNumber'].','.$sold['orderSubitemNumber'] );
		}
		/*找到所有已经完成*/
		
		
		/*从fache表中找到已经完成的条目*/
		try{
			$query = "select * from sss_fache
					where (phase = '销售' or phase = '出库') ";
					if ($type == "unsettled") {
						$query .= "and certificateNumber IS NULL or certificateNumber = '' ";
					}
					elseif ($type == "settled") {
						$query .= "and certificateNumber IS NOT NULL and certificateNumber != '' ";
					}
					$query .= "order by materialCode, orderNumber, orderSubitemNumber, facheDate;";
			$result = DB::query($query);
		}catch(Exception $e){
			echo $e;
			die();
		}
		
		while (($chuku=$result->fetch_assoc())!=NULL) {
			if (in_array(($chuku['materialCode'].','.$chuku['orderNumber'].','.$chuku['orderSubitemNumber']),$allCode)) {
				array_push ( $this->materialCodes, $chuku ['materialCode'] );
				array_push ( $this->ships, $chuku ['shipsClassification'] );
				array_push ( $this->materials, $chuku ['material'] );
				array_push ( $this->thicknesses, $chuku ['thickness'] );
				array_push ( $this->widths, $chuku ['width'] );
				array_push ( $this->lengths, $chuku ['length'] );
				array_push ( $this->counts, $chuku ['count'] );
				array_push ( $this->unitWeights, $chuku ['unitWeight'] );
				array_push ( $this->weights, $chuku ['count']*$chuku['unitWeight'] );
				array_push ( $this->orderNumbers, $chuku ['orderNumber'] );
				array_push ( $this->orderSubitemNumbers, $chuku ['orderSubitemNumber'] );
				array_push ( $this->unitPrices, $chuku ['unitPrices'] );
				array_push ( $this->purchaseNumbers, $chuku ['purchaseNumber'] );
				array_push ( $this->batchNumbers, $chuku ['batchNumber'] );
				array_push ( $this->materialNumbers, $chuku ['materialNumber'] );
				array_push ( $this->consignmentBatchs, $chuku ['consignmentBatch'] );
				array_push ( $this->storePlaces, $chuku ['storePlace'] );
				array_push ( $this->destinations, $chuku ['destination'] );
				
				//同样的材料代码，订单号，订单子项号可能在总表可能对应到多条数据，所以需要找到所有条目
				$filenameTemp = array();
				$checkoutBatchTemp = array();
				$certificateNumberTemp = array();
				$query = "select filename,checkoutBatch,certificateNumber from sss_main 
					where materialCode= '{$chuku['materialCode']}' and orderNumber='{$chuku['orderNumber']}' and orderSubitemNumber = '{$chuku['orderSubitemNumber']}'";
				$tempResult = DB::query($query);
				while (($row = $tempResult->fetch_assoc())!=NULL) {
					array_push($filenameTemp,$row['filename']);
					array_push($checkoutBatchTemp,$row['checkoutBatch']);
					array_push($certificateNumberTemp,$row['certificateNumber']);
				}
				$filenameTemp = join(",",$filenameTemp);
				$checkoutBatchTemp = join(",",$checkoutBatchTemp);
				$certificateNumberTemp = join(",",$certificateNumberTemp);
				array_push($this->filenames,$filenameTemp);
				array_push($this->checkoutBatchs,$checkoutBatchTemp);
				array_push($this->certificateNumbers,$certificateNumberTemp);
				
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
				if($chuku['phase']=='出库'){
					array_push ( $this->chukuDates, '出库:'.$chuku ['facheDate'] );
					array_push ( $this->chukuNumbers, $chuku ['facheNumber'] );
				}
				if($chuku['phase']=='销售'){
					array_push ( $this->chukuDates, '销售:'.$chuku ['facheDate'] );
					array_push ( $this->chukuNumbers, $chuku ['facheNumber'] );
				}
			}
		}
		/*从fache表中找到已经完成的条目*/
		
		
		/*从fachuan表中找到已经完成的条目*/
		try{
			$query = "select * from sss_fachuan ";
					if ($type == "unsettled") {
						$query .= "where certificateNumber IS NULL  or certificateNumber = '' ";
					}
					elseif ($type == "settled") {
						$query .= "where certificateNumber IS NOT NULL  and certificateNumber != '' ";
					}
					$query .= "order by materialCode, orderNumber, orderSubitemNumber, fachuanDate;";
			$result = DB::query($query);
		}catch(Exception $e){
			echo $e;
			die();
		}
		
		while (($chuku=$result->fetch_assoc())!=NULL) {
			if (in_array(($chuku['materialCode'].','.$chuku['orderNumber'].','.$chuku['orderSubitemNumber']),$allCode)) {
				array_push ( $this->materialCodes, $chuku ['materialCode'] );
				array_push ( $this->ships, $chuku ['shipsClassification'] );
				array_push ( $this->materials, $chuku ['material'] );
				array_push ( $this->thicknesses, $chuku ['thickness'] );
				array_push ( $this->widths, $chuku ['width'] );
				array_push ( $this->lengths, $chuku ['length'] );
				array_push ( $this->counts, $chuku ['count'] );
				array_push ( $this->unitWeights, $chuku ['unitWeight'] );
				array_push ( $this->weights, $chuku ['count']*$chuku['unitWeight'] );
				array_push ( $this->orderNumbers, $chuku ['orderNumber'] );
				array_push ( $this->orderSubitemNumbers, $chuku ['orderSubitemNumber'] );
				array_push ( $this->unitPrices, $chuku ['unitPrices'] );
				array_push ( $this->purchaseNumbers, $chuku ['purchaseNumber'] );
				array_push ( $this->batchNumbers, $chuku ['batchNumber'] );
				array_push ( $this->materialNumbers, $chuku ['materialNumber'] );
				array_push ( $this->consignmentBatchs, $chuku ['consignmentBatch'] );
				array_push ( $this->storePlaces, $chuku ['storePlace'] );
				array_push ( $this->destinations, $chuku ['destination'] );
				
				//同样的材料代码，订单号，订单子项号可能在总表可能对应到多条数据，所以需要找到所有条目
				$filenameTemp = array();
				$checkoutBatchTemp = array();
				$certificateNumberTemp = array();
				$query = "select filename,checkoutBatch,certificateNumber from sss_main 
					where materialCode= '{$chuku['materialCode']}' and orderNumber='{$chuku['orderNumber']}' and orderSubitemNumber = '{$chuku['orderSubitemNumber']}'";
				$tempResult = DB::query($query);
				while (($row = $tempResult->fetch_assoc())!=NULL) {
					array_push($filenameTemp,$row['filename']);
					array_push($checkoutBatchTemp,$row['checkoutBatch']);
					array_push($certificateNumberTemp,$row['certificateNumber']);
				}
				$filenameTemp = join(",",$filenameTemp);
				$checkoutBatchTemp = join(",",$checkoutBatchTemp);
				$certificateNumberTemp = join(",",$certificateNumberTemp);
				array_push($this->filenames,$filenameTemp);
				array_push($this->checkoutBatchs,$checkoutBatchTemp);
				array_push($this->certificateNumbers,$certificateNumberTemp);
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
				array_push ( $this->chukuDates, '发船:'.$chuku ['fachuanDate'] );
				array_push ( $this->chukuNumbers, $chuku ['fachuanNumber'] );

			}
		}
		/*从fachuan表中找到已经完成的条目*/
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
	
	public function getCheckoutBatchs(){
		return $this->checkoutBatchs;
	}
	
	public function getCertificateNumbers(){
		return $this->certificateNumbers;
	}
	
	public function getMaterialNumber(){
		return $this->materialNumbers;
	}
	
	public function getConsignmentBatch(){
		return $this->consignmentBatchs;
	}
}


?>