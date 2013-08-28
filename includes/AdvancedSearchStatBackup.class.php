<?php
require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");
require_once('../includes/SmartyManager.class.php');
require_once('../includes/DB.class.php');
require_once('../includes/Verifier.class.php');
require_once('../includes/Filter.class.php');

ini_set("max_execution_time",0);


class AdvancedSearchStat extends Accountant {

	private $wheres = array();
	
	private $facheWhereStr;
	
	private $fachuanWhereStr;
	
	private $phaseStr;
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
	
	private $unitPrice = array();
	
	private $batchNumber = array();
	
	private $purchaseNumber = array();
	 
	private $destination = array();
	
	private $storePlace = array();

	/**
	 * 构造函数，根据文件名称初始化sql语句。
	 *
	 * @param string $filename
	 */
	public function __construct(){
		
	if(isset($_GET['materialCode'])){
		$_GET['materialCode'] = Filter::forDBInsertion(trim($_GET['materialCode']));
		}
		if(isset($_GET['thicknessFrom'])){
			$_GET['thicknessFrom'] = trim($_GET['thicknessFrom']);
		}
		if(isset($_GET['thicknessTo'])){
			$_GET['thicknessTo'] = trim($_GET['thicknessTo']);
		}
		if(isset($_GET['widthFrom'])){
			$_GET['widthFrom'] = trim($_GET['widthFrom']);
		}
		if(isset($_GET['widthTo'])){
			$_GET['widthTo'] = trim($_GET['widthTo']);
		}
		if(isset($_GET['lengthFrom'])){
			$_GET['lengthFrom'] = trim($_GET['lengthFrom']);
		}
		if(isset($_GET['lengthTo'])){
			$_GET['lengthTo'] = trim($_GET['lengthTo']);
		}
		if(isset($_GET['uploadTimeFrom'])){
			$_GET['uploadTimeFrom'] = trim($_GET['uploadTimeFrom']);
		}
		if(isset($_GET['uploadTimeTo'])){
			$_GET['uploadTimeTo'] = trim($_GET['uploadTimeTo']);
		}
		
		if(isset($_GET['faDateFrom'])){
			$_GET['faDateFrom'] = trim($_GET['faDateFrom']);
		}
		if(isset($_GET['faDateTo'])){
			$_GET['faDateTo'] = trim($_GET['faDateTo']);
		}
		
		if(isset($_GET['faNumber'])){
			$_GET['faNumber'] = trim($_GET['faNumber']);
		}
		
		if(isset($_GET['shipsClassification'])){
			$_GET['shipsClassification'] = Filter::forDBInsertion(trim($_GET['shipsClassification']));
		}
		if(isset($_GET['material'])){
			$_GET['material'] = Filter::forDBInsertion(trim($_GET['material']));
		}
		
		if(isset($_GET['orderNumber'])){
			$_GET['orderNumber'] = Filter::forDBInsertion(trim($_GET['orderNumber']));
		}
		
		if(isset($_GET['orderSubitemNumber'])){
			$_GET['orderSubitemNumber'] = trim($_GET['orderSubitemNumber']);
		}
		
		if(isset($_GET['filename'])){
			$_GET['filename'] = Filter::forDBInsertion(trim($_GET['filename']));
		}
		if(isset($_GET['remark'])){
			$_GET['remark'] = Filter::forDBInsertion(trim($_GET['remark']));
		}
		
		if(isset($_GET['unitPrice'])){
			$_GET['unitPrice'] = trim($_GET['unitPrice']);
		}
		if(isset($_GET['batchNumber'])){
			$_GET['batchNumber'] = Filter::forDBInsertion(trim($_GET['batchNumber']));
		}
		if(isset($_GET['purchaseNumber'])){
			$_GET['purchaseNumber'] = Filter::forDBInsertion(trim($_GET['purchaseNumber']));
		}
		if(isset($_GET['destination'])){
			$_GET['destination'] = Filter::forDBInsertion(trim($_GET['destination']));
		}
		if(isset($_GET['storePlace'])){
			$_GET['storePlace'] = Filter::forDBInsertion(trim($_GET['storePlace']));
		}
		if(isset($_GET['sequenceNumber'])){
			$_GET['sequenceNumber'] = Filter::forDBInsertion(trim($_GET['sequenceNumber']));
		}
		if(isset($_GET['manufactory'])){
			$_GET['manufactory'] = Filter::forDBInsertion(trim($_GET['manufactory']));
		}
		if(isset($_GET['ruku'])){
			$_GET['ruku'] = Filter::forDBInsertion(trim($_GET['ruku']));
		}
		if(isset($_GET['chuku'])){
			$_GET['chuku'] = Filter::forDBInsertion(trim($_GET['chuku']));
		}
		if(isset($_GET['sale'])){
			$_GET['sale'] = Filter::forDBInsertion(trim($_GET['sale']));
		}
		if(isset($_GET['main'])){
			$_GET['main'] = Filter::forDBInsertion(trim($_GET['main']));
		}
		
		if(!empty($_GET['materialCode'])){
			array_push($this->wheres, "materialCode like '%{$_GET['materialCode']}%'");
		}
		
		if(!empty($_GET['thicknessFrom']) && Verifier::isNUMBER($_GET['thicknessFrom'])){
			$thicknessFrom = doubleval($_GET['thicknessFrom']);
			array_push($this->wheres, "thickness >= {$thicknessFrom}");
		}
		
		if(!empty($_GET['thicknessTo']) && Verifier::isNUMBER($_GET['thicknessTo'])){
			$thicknessTo = doubleval($_GET['thicknessTo']);
			array_push($this->wheres, "thickness <= {$thicknessTo}");
		}
		
		if(!empty($_GET['widthFrom']) && Verifier::isNUMBER($_GET['widthFrom'])){
			$widthFrom = doubleval($_GET['widthFrom']);
			array_push($this->wheres, "width >= {$widthFrom}");
		}
		
		if(!empty($_GET['widthTo']) && Verifier::isNUMBER($_GET['widthTo'])){
			$widthTo = doubleval($_GET['widthTo']);
			array_push($this->wheres, "width <= {$widthTo}");
		}
		
		if(!empty($_GET['lengthFrom']) && Verifier::isNUMBER($_GET['lengthFrom'])){
			$lengthFrom = doubleval($_GET['lengthFrom']);
			array_push($this->wheres, "length >= {$lengthFrom}");
		}
		
		if(!empty($_GET['lengthTo']) && Verifier::isNUMBER($_GET['lengthTo'])){
			$lengthTo = doubleval($_GET['lengthTo']);
			array_push($this->wheres, "length <= {$lengthTo}");
		}
		
		if(!empty($_GET['uploadTimeFrom']) && Verifier::isTIME($_GET['uploadTimeFrom'])){
			$time = date('Y/m/d H:i:s', strtotime($_GET['uploadTimeFrom']));
			array_push($this->wheres, "uploadTime >= '$time'");
		}
		
		if(!empty($_GET['uploadTimeTo']) && Verifier::isTIME($_GET['uploadTimeTo'])){
			$time = date('Y/m/d H:i:s', strtotime($_GET['uploadTimeTo']));
			array_push($this->wheres, "uploadTime <= '$time'");
		}
		
		
		if(!empty($_GET['shipsClassification'])){
			array_push($this->wheres, "shipsClassification = '{$_GET['shipsClassification']}'");
		}
		
		if(!empty($_GET['material'])){
			array_push($this->wheres, "material = '{$_GET['material']}'");
		}
		
		if(!empty($_GET['filename'])){
			array_push($this->wheres, "filename like '%{$_GET['filename']}%'");
		}
		
		if(!empty($_GET['remark'])){
			array_push($this->wheres, "(remark1 like '%{$_GET['remark']}%' or remark2 like '%{$_GET['remark']}%'
								  or remark3 like '%{$_GET['remark']}%' or remark4 like '%{$_GET['remark']}%' or remark5 like '%{$_GET['remark']}%')");
		}
		
		if(!empty($_GET['orderNumber'])){
			array_push($this->wheres, "orderNumber like '%{$_GET['orderNumber']}%'");
		}
		
		if(!empty($_GET['orderSubitemNumber'])){
			array_push($this->wheres, "orderSubitemNumber = '{$_GET['orderSubitemNumber']}'");
		}
		
		if(!empty($_GET['unitPrice'])){
			array_push($this->wheres, "unitPrice = '{$_GET['unitPrice']}'");
		}
		
		if(!empty($_GET['batchNumber'])){
			array_push($this->wheres, "batchNumber like '%{$_GET['batchNumber']}%'");
		}
		
		if(!empty($_GET['purchaseNumber'])){
			array_push($this->wheres, "purchaseNumber like '%{$_GET['purchaseNumber']}%'");
		}
		
		if(!empty($_GET['destination'])){
			array_push($this->wheres, "destination = '{$_GET['destination']}'");
		}
		
		if(!empty($_GET['storePlace'])){
			array_push($this->wheres, "storePlace = '{$_GET['storePlace']}'");
		}
		
		
		if((count($this->wheres) == 0 and empty($_GET['faDateFrom']) and empty($_GET['faDateTo']) and 
		   empty($_GET['faNumber']) and empty($_GET['sequenceNumber']) and empty($_GET['manufactory']))
		   || (empty($_GET['ruku']) and empty($_GET['chuku']) and empty($_GET['sale']) and empty($_GET['main']))){
			header('Location: advancedSearch.html');
		}
		
		$faTable = true;
		
		if(!empty($_GET['sequenceNumber']) or !empty($_GET['manufactory'])){
			$faTable = false;
		}
		
		if($faTable){
			if(!empty($_GET['ruku']) || !empty($_GET['chuku']) || !empty($_GET['sale']) ){
				$facheWheres = $this->wheres;
				if(!empty($_GET['faNumber'])){
					array_push($facheWheres, "facheNumber like '%{$_GET['faNumber']}%' ");
				}
		
				
				if(!empty($_GET['faDateFrom']) || Verifier::isTIME($_GET['faDateFrom'])){
					$time = date('Y/m/d', strtotime($_GET['faDateFrom']));
					array_push($facheWheres, "facheDate >= '$time'");
				}
			
				if(!empty($_GET['faDateTo']) || Verifier::isTIME($_GET['faDateTo'])){
					$time = date('Y/m/d', strtotime($_GET['faDateTo']));
					array_push($facheWheres, "facheDate <= '$time'");
				}
			
				
				$this->facheWhereStr = join(' and ', $facheWheres);
				
				$phaseArray = array();
				if(!empty($_GET['ruku'])){
					array_push($phaseArray,"phase = '{$_GET['ruku']}'");
				}
				if(!empty($_GET['chuku'])){
				    array_push($phaseArray,"phase = '{$_GET['chuku']}'");
				}
				if(!empty($_GET['sale'])){
					array_push($phaseArray,"phase = '{$_GET['sale']}'");
				}
			
				$this->phaseStr = join(' or ',$phaseArray);
						
				$this->facheWhereStr .= ' and ('.$this->phaseStr.')';
				
			}			
						
			if(!empty($_GET['chuku'])){
				$fachuanWheres = $this->wheres;
			
				if(!empty($_GET['faDateFrom']) && Verifier::isTIME($_GET['faDateFrom'])){
					$time = date('Y/m/d', strtotime($_GET['faDateFrom']));
					array_push($fachuanWheres, "fachuanDate >= '$time'");
				}
			
				if(!empty($_GET['faDateTo']) && Verifier::isTIME($_GET['faDateTo'])){
					$time = date('Y/m/d', strtotime($_GET['faDateTo']));
					array_push($fachuanWheres, "fachuanDate <= '$time'");
				}
				
				if(!empty($_GET['faNumber'])){
					array_push($fachuanWheres, "fachuanNumber like '%{$_GET['faNumber']}%' ");
				}
				
				$this->fachuanWhereStr = join(' and ', $fachuanWheres);
		//		var_dump($fachuanWhereStr);
				
			}
		
		
		$this->sql = "select materialCode, shipsClassification, material, thickness, width, length, 
		sumCount, beforeSumCount, coalesce(afterSumCount, 0) as afterSumCount, coalesce(rukuCount, 0) as rukuCount, coalesce(directCount, 0) as directCount,
	coalesce(chukuCount, 0) as chukuCount, orderNumber, orderSubitemNumber,
	unitPrice, batchNumber, purchaseNumber, destination, storePlace
from
		(
			(
			select materialCode, shipsClassification, material, width, thickness, length, orderNumber, orderSubitemNumber,
			unitPrice, batchNumber, purchaseNumber, destination, storePlace, sum(`count`) as sumCount
			from sss_main
			where '$this->'
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
				}else{
					array_push($this->soldCount, 0);
				}
				array_push($this->kuzhongCount, $kuzhong);
				
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
}
?>