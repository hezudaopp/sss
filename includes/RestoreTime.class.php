<?php
require_once('../includes/DB.class.php');

class RestoreTime{
	private $fromDate = "";
	private $toDate = "";
	private $storePlace = "";
	private $destination = "";
	
	private $facheWheres = "";
	private $fachuanWheres = "";
	
	private $materialCodes = array();
	private $ships = array();
	private $materials = array();
	private $thicknesses = array();
	private $widths = array();
	private $lengths = array();
	private $counts = array();
	private $rukuDate = array();
	private $chukuDate = array();
	private $rukuNumber = array();
	private $chukuNumber = array();
	private $intervals = array();
	private $unitWeights = array();
	private $weights =array();
	private $orderNumbers = array();
	private $orderSubitemNumbers = array();
	private $unitPrices = array();
	private $batchNumbers = array();
	private $purchaseNumbers = array();
	private $destinations = array();
	private $storePlaces = array();
	private $certificateNumbers = array();
	private $checkoutBatchs = array();
	private $materialNumbers = array();
	private $consignmentBatchs = array();
	
	/**
	 * 判断是否有错误条目
	 * @var bool
	 */
	private $isExistFalseItem;
	
	//private $result = array();
	
	public function __construct($from, $to, $store=NULL, $dest=NULL){
		$this->fromDate = $from;
		$this->toDate = $to;
		$this->storePlace = $store;
		$this->destination = $dest;
		
		
		if($from == NULL){
			$this->fromDate = "1000/10/10";
		}
		if($to == NULL){
			$this->toDate = "2100/10/10";
		}
		
		$this->facheWheres .= " facheDate >= '".$this->fromDate . "' and facheDate <= '" . $this->toDate ."'";
		$this->fachuanWheres .= " fachuanDate >= '".$this->fromDate . "' and fachuanDate <= '" . $this->toDate . "'";
		
		if($this->storePlace != NULL){
			$this->facheWheres .= " and storePlace = '" . $this->storePlace ."'";
			$this->fachuanWheres .= " and storePlace = '" . $this->storePlace ."'";
		}
		
		if($this->destination != NULL){
			$this->facheWheres .= " and destination = '" . $this->destination ."'";
			$this->fachuanWheres .= " and destination = '" . $this->destination ."'";
		}
		
	}
	
	public function findCommon(){
		try{
			$query = "drop view if exists sss_chuku";
			DB::query($query);
			//创建视图
			//查找这段时间内出库的数量（包括发车出库数量和发船数量）,
			//默认排序方式为材料代码按照从大到小的方式排序，出库时间按照从大到小的方式（也就是从晚到早的方式排序）。
			//为的是从尾部计算，对计算完的随时pop_back
			$query = "create algorithm = temptable
			view sss_chuku
			as 
			(select materialCode,orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
			 shipsClassification, material, thickness, width, length, unitWeight,
				facheNumber as chukuNumber, facheDate as chukuDate, sum(count) as chukuCount,
				certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_fache
			where {$this->facheWheres} and phase = '出库' 
			group by materialCode,orderNumber, orderSubitemNumber, chukuDate
			order by materialCode desc,orderNumber desc, orderSubitemNumber desc, chukuDate desc )
			union all
			(select materialCode, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
			shipsClassification, material, thickness, width, length, unitWeight,
				fachuanNumber as chukuNumber, fachuanDate as chukuDate, sum(count) as chukuCount,
				certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			from sss_fachuan
			where {$this->fachuanWheres}
			group by materialCode, orderNumber, orderSubitemNumber, chukuDate
			order by materialCode desc, orderNumber desc, orderSubitemNumber desc, chukuDate desc)";
			DB::query($query);
		}catch(Exception $e){
			//删除视图
			$query = "drop view sss_chuku;";
			DB::query($query);
			
			echo $e;
			die();
		}
		
		
		
		try{
			DB::beginTransaction();
			
			//查询出库信息, 排序方式：材料代码按照从大到小的方式排序，而出库时间按照从大到小的方式（也就是从晚到早的方式排序）
			$query = 'select materialCode, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
			shipsClassification, material, thickness, width, length, unitWeight,
				chukuNumber, chukuDate, sum(`chukuCount`) as chukuCount, chukuCount*unitWeight as weight,
				certificateNumber, checkoutBatch, materialNumber, consignmentBatch
				 from sss_chuku
				 group by materialCode, orderNumber, orderSubitemNumber, chukuDate
				 order by materialCode desc, orderNumber desc, orderSubitemNumber desc, chukuDate desc';
			DB::query($query);
			$result = DB::getResult();
			
			//二维数组，里边存储的每条数据的信息
			$chukuCounts = array();
			while(($row = $result->fetch_assoc())!=NULL){
				//将字符串转换为timestamp以便于以后的频繁比较
				$row['chukuDate'] = strtotime($row['chukuDate']);
				
				array_push($chukuCounts, $row);
			}
//			echo "chukuCounts:";
//			var_dump($chukuCounts);
//			echo "<br>";
			
			
			//查询oldCount，也就是以前的出库数量
			//排序方式：材料代码按照从大到小的方式
			$query = "
			select distinct materialCode, orderNumber, orderSubitemNumber, sum(`tempOldCount`) as oldCount
			from (
					(select distinct materialCode, orderNumber, orderSubitemNumber, sum(`count`) as tempOldCount
					from sss_fache
					where (materialCode, orderNumber, orderSubitemNumber) 
						in (select materialCode, orderNumber, orderSubitemNumber
						 	from sss_chuku) and facheDate < '{$this->fromDate}' and phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					order by materialCode desc, orderNumber desc, orderSubitemNumber desc )
				union
					(select distinct materialCode, orderNumber, orderSubitemNumber, sum(`count`) as tempOldCount
					from sss_fachuan
					where (materialCode, orderNumber, orderSubitemNumber) 
						in (select materialCode, orderNumber, orderSubitemNumber
						 from sss_chuku) and fachuanDate < '{$this->fromDate}'
					group by materialCode, orderNumber, orderSubitemNumber
					order by materialCode desc, orderNumber desc, orderSubitemNumber desc)
				) as tempOldCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			order by materialCode desc, orderNumber desc, orderSubitemNumber desc
			";
			DB::query($query);
			$result = DB::getResult();
			$oldCounts = array();
			while(($row = $result->fetch_assoc())!=NULL){
				array_push($oldCounts, $row);
			}
			//echo ("oldCounts:");
			//var_dump($oldCounts);
			
			
			//查询入库时间
			//排序方式：材料代码按照从大到小的方式，入库时间按照从小到大的方式，也就是从早到晚的方式。
			$query = "
				select materialCode, orderNumber, orderSubitemNumber,
				 sum(`count`) as rukuCount, facheDate as rukuDate, facheNumber as rukuNumber
				from sss_fache
				where (materialCode, orderNumber, orderSubitemNumber) 
					in (select materialCode, orderNumber, orderSubitemNumber
					 from sss_chuku) and facheDate <= '{$this->toDate}' and phase = '入库'
				group by materialCode, orderNumber, orderSubitemNumber, rukuDate
				order by materialCode desc, orderNumber desc, orderSubitemNumber desc, rukuDate asc
			";
			DB::query($query);
			//echo "the ruku Date:<pre>$query</pre>";
			
			$result = DB::getResult();
			$rukuCounts = array();
			while(($row = $result->fetch_assoc())!=NULL){
				//将字符串转换为timestamp以便于以后的频繁比较
				$row['rukuDate'] = strtotime($row['rukuDate']);
				array_push($rukuCounts, $row);
				//echo $row['materialCode'].' : '.$row['rukuDate'].' : '.$row['rukuCount'].'<br />';
			}
			//echo ("rukuCounts:");
//			var_dump($rukuCounts);
			
			//删除视图
			$query = "drop view sss_chuku;";
			DB::query($query);
			
			DB::commit();
		}catch(Exception $e){
			echo $e;
			DB::rollback();
			
			//删除视图
			$query = "drop view sss_chuku;";
			DB::query($query);
			
			die();
		}
		
		
	try{
		DB::beginTransaction();
		
		//查询销售信息, 排序方式：材料代码按照从大到小的方式排序，而出库时间按照从大到小的方式（也就是从晚到早的方式排序）
		$query = "select materialCode, orderNumber, orderSubitemNumber, unitPrice, batchNumber, purchaseNumber, destination, storePlace,
		shipsClassification, material, thickness, width, length, unitWeight,
			facheNumber, facheDate, sum(`count`) as count, count*unitWeight as weight,
			certificateNumber, checkoutBatch, materialNumber, consignmentBatch
			 from sss_fache 
			 where {$this->facheWheres} and phase = '销售'
			 group by materialCode, orderNumber, orderSubitemNumber, facheDate
			 order by materialCode desc, orderNumber desc, orderSubitemNumber desc, facheDate desc";
		DB::query($query);
		$result = DB::getResult();
		while(($row = $result->fetch_assoc())!=NULL){
			//将字符串转换为timestamp以便于以后的频繁比较
			$row['facheDate'] = strtotime($row['facheDate']);
			
			array_push($this->materialCodes, $row['materialCode']);
			array_push($this->ships, $row['shipsClassification']);
			array_push($this->materials, $row['material']);
			array_push($this->thicknesses, $row['thickness']);
			array_push($this->widths, $row['width']);
			array_push($this->lengths, $row['length']);
			array_push($this->unitWeights, $row['unitWeight']);
			array_push($this->weights, $row['weight']);
			array_push($this->chukuDate, "销售:".date('Y-m-d',$row['facheDate']));
			array_push($this->chukuNumber, "销售:".$row['facheNumber']);
			array_push($this->orderNumbers, $row['orderNumber']);
			array_push($this->orderSubitemNumbers, $row['orderSubitemNumber']);
			array_push($this->unitPrices, $row['unitPrices']);
			array_push($this->batchNumbers, $row['batchNumber']);
			array_push($this->purchaseNumbers, $row['purchaseNumber']);
			array_push($this->destinations, $row['destination']);
			array_push($this->storePlaces, $row['storePlace']);
			array_push($this->certificateNumbers, $row['certificateNumber']);
			array_push($this->checkoutBatchs,$row['checkoutBatch']);
			array_push($this->materialNumbers,$row['materialNumber']);
			array_push($this->consignmentBatchs,$row['consignmentBatch']);
			array_push($this->rukuNumber, "");
			array_push($this->rukuDate, "");
			array_push($this->intervals, 1);
			array_push($this->counts,$row['count']);
		}
		
		DB::commit();
	}catch(Exception $e){
		echo $e;
		DB::rollback();
		die();
	}
		
		//$result = &$this->result;
/*		echo '<pre>';
		var_dump($rukuCounts);
		echo '</pre>';
		die();*/
	
		//当sss_chuku视图中有数据，进入循环(该循环为倒序循环)
		while(sizeof($chukuCounts)){
			//每次循环$n会随着$chukuCounts的pop而减小
			$n = sizeof($chukuCounts) - 1;
			$currentMc = $chukuCounts[$n]['materialCode'];
			$currentOrder = $chukuCounts[$n]['orderNumber'];
			$currentSub = $chukuCounts[$n]['orderSubitemNumber'];
			
			//如果查询时间内的出库数据在这之前出库过
			if(!empty($oldCounts) and $currentMc == $oldCounts[sizeof($oldCounts) - 1]['materialCode']
					and $currentOrder == $oldCounts[sizeof($oldCounts) - 1]['orderNumber']
					and $currentSub == $oldCounts[sizeof($oldCounts) - 1]['orderSubitemNumber']){
				$oldSum = $oldCounts[sizeof($oldCounts) - 1]['oldCount'];
				array_pop($oldCounts);
				
				//循环，使用oldCounts将那个rukuCounts中对应的元素消掉
				while($oldSum > 0){
					//!!!!不知道这样使用引用是否合适
					$last = &$rukuCounts[sizeof($rukuCounts) - 1];
					
					//如果rukuCount中的最后一个是当前的材料代码，那么就参与计算
					if($last['materialCode'] == $currentMc
						and $last['orderNumber'] == $currentOrder
						and $last['orderSubitemNumber'] == $currentSub){
						//如果最后一个的入库数量小于等于以前的出库数量，相减之后的$oldSum表示查询时间之前的多出库的数量
						if($last['rukuCount'] <= $oldSum){
							$oldSum -= $last['rukuCount'];
							array_pop($rukuCounts);//之前出库数量就大于入库数量，肯定是数据错误
							continue;
						}else{
						//如果最后一个的入库数量大于以前的出库数量,相减之后的$last['rukuCount']表示查询时间内库中的数量
							$last['rukuCount'] -= $oldSum;
							$oldSum = 0;
							break;
						}
					//否则，表明没有入库（竟然后出库），必定有错误，应该如何处理？
					}else{
						//这里处理那些错误
						while(!empty($chukuCounts) 
							and $chukuCounts[sizeof($chukuCounts) - 1]['materialCode'] == $currentMc
							and $chukuCounts[sizeof($chukuCounts) - 1]['orderNumber'] == $currentOrder
							and $chukuCounts[sizeof($chukuCounts) - 1]['orderSubitemNumber'] == $currentSub){
							array_pop($chukuCounts);
						}
						
						//将$currentMc设置成null表示当前这一轮出现错误，要结束
						$currentMc = null;
						
						//应该显示，这个材料代码的出库和入库有问题。！！！！！！！！！！！！！！！！！
						
						break;
					}
				}
			}
			
			//因为入库的材料代码是从出库中找材料代码进行查询的，所以不可能出现入库中有而出库中没有材料代码的情况，只可能出现
			//出库中有而入库中没有的情况（当然这种情况是因为上传的表格的错误造成的），这是，就应该这样进行处理：
			//往入库的材料代码中添加一个虚拟的项，这个项和正在比对的出库的材料代码一样，这样就会按照正常程序进行计算。
			if($currentMc != null && !empty($rukuCounts) 
				and ($rukuCounts[sizeof($rukuCounts) - 1]['materialCode'] != $currentMc
				or $rukuCounts[sizeof($rukuCounts) - 1]['orderNumber'] != $currentOrder
				or $rukuCounts[sizeof($rukuCounts) - 1]['orderSubitemNumber'] != $currentSub)){
				$newRuku['materialCode'] = $currentMc;
				$newRuku['orderNumber'] = $currentOrder;
				$newRuku['orderSubitemNumber'] = $currentSub;
				$newRuku['rukuCount'] = $chukuCounts[sizeof($chukuCounts) - 1]['chukuCount'];
				$newRuku['rukuDate'] = $chukuCounts[sizeof($chukuCounts) - 1]['chukuDate'];
				$newRuku['added'] = true;
				$rukuCounts[sizeof($rukuCounts)] = $newRuku;
			}
			
			while(!empty($chukuCounts) 
				and $chukuCounts[sizeof($chukuCounts) - 1]['materialCode'] == $currentMc
				and $chukuCounts[sizeof($chukuCounts) - 1]['orderNumber'] == $currentOrder
				and $chukuCounts[sizeof($chukuCounts) - 1]['orderSubitemNumber'] == $currentSub){
			//for($i = sizeof($chukuCounts) -1; $i >= 0; $i--){
				$i = sizeof($chukuCounts) - 1;
				//消去$rukuCount和$chukuCount数组中的某个元素，就表示ok为true;如果$ok为false,表示这个过程没有进展，则表示陷入死循环
				//死循环条件：所有入库时间均大于这个出库时间（这样的情况的解决方案：将所有这个材料代码的信息全部删除，然后报告错误）。
				$ok = false;
				
				//如果$j没有设置或者为null，那么进行设置，否则保持原来的值，这样在chukuCount没有被减完的情况下非常有用。
				if(!isset($j) or $j == null){
					$j = sizeof($rukuCounts) - 1;
				}

				for(; $j >= 0 and $rukuCounts[$j]['materialCode'] == $currentMc 
					and $rukuCounts[$j]['orderNumber'] == $currentOrder
					and $rukuCounts[$j]['orderSubitemNumber'] == $currentSub; 
					$j--){
					if($chukuCounts[$i]['chukuDate'] >= $rukuCounts[$j]['rukuDate']){
						$chuku = &$chukuCounts[$i];
						
						array_push($this->materialCodes, $chuku['materialCode']);
						array_push($this->ships, $chuku['shipsClassification']);
						array_push($this->materials, $chuku['material']);
						array_push($this->thicknesses, $chuku['thickness']);
						array_push($this->widths, $chuku['width']);
						array_push($this->lengths, $chuku['length']);
						array_push($this->unitWeights, $chuku['unitWeight']);
						array_push($this->weights, $chuku['weight']);
						array_push($this->chukuDate, date('Y-m-d', $chuku['chukuDate']));
						array_push($this->chukuNumber, $chuku['chukuNumber']);
						array_push($this->orderNumbers, $chuku['orderNumber']);
						array_push($this->orderSubitemNumbers, $chuku['orderSubitemNumber']);
						array_push($this->unitPrices, $chuku['unitPrices']);
						array_push($this->batchNumbers, $chuku['batchNumber']);
						array_push($this->purchaseNumbers, $chuku['purchaseNumber']);
						array_push($this->destinations, $chuku['destination']);
						array_push($this->storePlaces, $chuku['storePlace']);
						array_push($this->certificateNumbers, $chuku['certificateNumber']);
						array_push($this->checkoutBatchs,$chuku['checkoutBatch']);
						array_push($this->materialNumbers,$chuku['materialNumber']);
						array_push($this->consignmentBatchs,$chuku['consignmentBatch']);
						if(!isset($rukuCounts[$j]['added'])){
							array_push($this->rukuNumber, $rukuCounts[$j]['rukuNumber']);
							array_push($this->rukuDate, date('Y-m-d', $rukuCounts[$j]['rukuDate']));
							array_push($this->intervals, ($chuku['chukuDate'] - $rukuCounts[$j]['rukuDate'])/3600/24 + 1);
						}else{
							array_push($this->rukuNumber, "未入库");
							array_push($this->rukuDate, "未入库却有出库");
							array_push($this->intervals, 0);
						}
						
						if($chukuCounts[$i]['chukuCount'] > $rukuCounts[$j]['rukuCount']){
							
							//$end['chukuCount'] = $rukuCounts[$j]['rukuCount'];
							array_push($this->counts, intval($rukuCounts[$j]['rukuCount']));
							$chukuCounts[$i]['chukuCount'] -= $rukuCounts[$j]['rukuCount'];
							//echo $chukuCounts[$i]['materialCode'].' and '.'count : '.$rukuCounts[$j]['rukuCount'].'<br />';
							unset($rukuCounts[$j]);
							//下次循环就从$j -1 开始
							$j -= 1;
					
						}else if($chukuCounts[$i]['chukuCount'] < $rukuCounts[$j]['rukuCount']){
							
							//$end['chukuCount'] = $chukuCounts[$i]['chukuCount'];
							array_push($this->counts, intval($chukuCounts[$i]['chukuCount']));
							$rukuCounts[$j]['rukuCount'] -= $chukuCounts[$i]['chukuCount'];
							array_pop($chukuCounts);
							$j = null;
				
						}else{
							
							//$end['chukuCount'] = $rukuCounts[$j]['rukuCount'];
							array_push($this->counts, intval($chukuCounts[$i]['chukuCount']));
							unset($rukuCounts[$j]);	
							array_pop($chukuCounts);
							$j = null;
						}
						$ok = true;
						break;
					}
				}
				
				//当$ok为false，就会在出错的这条项目break，同时，设置isExistFalseItem为true;
				if($ok == false){
					if($chukuCounts[sizeof($chukuCounts) - 1]['materialCode'] == $currentMc
						and $chukuCounts[sizeof($chukuCounts) - 1]['orderNumber'] == $currentOrder
						and $chukuCounts[sizeof($chukuCounts) - 1]['orderSubitemNumber'] == $currentSub){
						array_pop($chukuCounts);
						$this->isExistFalseItem = true;
					}
					break;
				}
			}
			
			/*//将剩下的这个材料代码的入库信息清除掉
			while(!empty($rukuCounts) && isset($rukuCounts[sizeof($rukuCounts) - 1]['materialCode']) 
					and  $rukuCounts[sizeof($rukuCounts) - 1]['materialCode']== $currentMc
					and  $rukuCounts[sizeof($rukuCounts) - 1]['orderNumber']== $currentOrder
					and  $rukuCounts[sizeof($rukuCounts) - 1]['orderSubitemNumber']== $currentSub){
				array_pop($rukuCounts);
			}*/
			$cRuku = end($rukuCounts);
			while(!empty($rukuCounts) and isset($cRuku['materialCode'])
					and $cRuku['materialCode'] == $currentMc
					and $cRuku['orderNumber'] == $currentOrder
					and $cRuku['orderSubitemNumber'] == $currentSub){
				array_pop($rukuCounts);
				$cRuku = end($rukuCounts);
			}
		}
		
		
	}
	
	
	public function findJiapian(){
		
	}
	
	public function findChinese(){
		
	}
	
	public function getMaterialCode(){
		return $this->materialCodes;
	}
	
	public function getShips(){
		return $this->ships;
	}
	
	public function getMaterial(){
		return $this->materials;
	}
	
	public function getThickness(){
		return $this->thicknesses;
	}
	
	public function getWidth(){
		return $this->widths;
	}
	
	public function getLength(){
		return $this->lengths;
	}
	
	public function getCount(){
		return $this->counts;
	}
	
	public function getUnitWeight(){
		return $this->unitWeights;
	}
	
	public function getWeight(){
		return $this->weights;
	}
	
	public function getInterval(){
		return $this->intervals;
	}
	
	public function getChukuDate(){
		return $this->chukuDate;
	}
	
	public function getRukuDate(){
		return $this->rukuDate;
	}
	
	public function getRukuNumber(){
		return $this->rukuNumber;
	}
	
	public function getChukuNumber(){
		return $this->chukuNumber;
	}
	
	public function getOrderNumber(){
		return $this->orderNumbers;
	}
	
	public function getOrderSubitemNumber(){
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
	
	public function getCertificateNumber(){
		return $this->certificateNumbers;
	}
	
	public function getCheckoutBatch(){
		return $this->checkoutBatchs;
	}

	public function getMaterialNumber(){
		return $this->materialNumbers;
	}
	
	public function getConsignmentBatch(){
		return $this->consignmentBatchs;
	}
	
	public function getIsExistFalseItem(){
		return $this->isExistFalseItem;
	}
}

?>