<?php

require_once("Accountant.class.php");
require_once("exceptions/AppExceptions.class.php");

class AllFileStateAccountant extends Accountant {

	private $detail = false;
	/**
	 * 未完成的材料代码的数量的数组
	 *
	 * @var array
	 */
	private $unFinishedCount = array();

	/**
	 * 完成的材料代码的数量的数组
	 *
	 * @var array
	 */
	private $finishedCount = array();

	/**
	 * 文件中distinct的材料代码的数量的数组
	 *
	 * @var array
	 */
	private $distinctMaterialCodeCount = array();

	/**
	 * 文件中全部数量的数组（每个材料代码可能包含多个数量）
	 * 
	 * @var array
	 */
	private $allCount = array();
	
	/**
	 * 文件中总数量的数组，和$allCount对应
	 * 
	 * @var array
	 */
	private $allWeight = array();
	
	/**
	 * 上传时间的数组
	 *
	 * @var array
	 */
	private $uploadTime = array();

	/**
	 * 文件名的数组
	 *
	 * @var array
	 */
	private $filename = array();
	
	/**
	 * 构造函数
	 *
	 * @param boolean $_detail
	 */
	public function __construct($_detail = false){
		$this->detail = $_detail;
		if($_detail){
			ini_set('max_execution_time', 0);
			
		//添加了订单号和订单子项号之后，这个sql语句已经更改
		$this->sql = "select filename, uploadTime, count(distinct materialCode, orderNumber, orderSubitemNumber) as distinctMaterialCodeCount,
		sum(isFinished) as finishedCount,sum(count) as allCount, sum(count*unitWeight) as allWeight
		from
	(
	select materialCode, orderNumber, orderSubitemNumber, filename, uploadTime
	from sss_main
	group by filename, materialCode, orderNumber, orderSubitemNumber
	)
	as mcInfoTable

left outer join

	(
	select materialCode, orderNumber, orderSubitemNumber, (sumCount = coalesce(chukuCount, 0) + coalesce(directCount, 0)) as isFinished
	from(
		select * from
		(
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as sumCount
			from sss_main
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as sumCountTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`halfChukuCount`) as chukuCount
			from
				(
					(select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all
					
					(select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
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
	   )
		as CountTable
	)as isFinishedTable
using (materialCode, orderNumber, orderSubitemNumber)
group by filename
order by uploadTime desc;";
		}else{
			$this->sql = "select filename, uploadTime, count(distinct materialCode, orderNumber, orderSubitemNumber) as distinctMaterialCodeCount,
			sum(count) as allCount, sum(count*unitWeight) as allWeight
		from sss_main
		group by filename
		order by filename asc, uploadTime desc;";
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
		if($this->detail){
			while($row = $this->result->fetch_assoc()){
				array_push($this->filename, $row['filename']);
				array_push($this->uploadTime, $row['uploadTime']);
				array_push($this->distinctMaterialCodeCount, intval($row['distinctMaterialCodeCount']) );
				array_push($this->finishedCount, intval($row['finishedCount']) );
				array_push($this->unFinishedCount, intval($row['distinctMaterialCodeCount']) - intval($row['finishedCount']) );
			}
		}else{
			while($row = $this->result->fetch_assoc()){
				array_push($this->filename, $row['filename']);
				array_push($this->uploadTime, $row['uploadTime']);
				array_push($this->distinctMaterialCodeCount, intval($row['distinctMaterialCodeCount']) );
				array_push($this->allCount,$row['allCount']);
				array_push($this->allWeight,$row['allWeight']);
			}
		}
	}


	public function getFilename(){
		return $this->filename;
	}
	public function getUploadTime(){
		return $this->uploadTime;
	}
	public function getDistinctMaterialCodeCount(){
		return $this->distinctMaterialCodeCount;
	}
	public function getFinishedCount(){
		return $this->finishedCount;
	}
	public function getUnFinishedCount(){
		return $this->unFinishedCount;
	}
	/**
	 * @return the $allWeight
	 */
	public function getAllWeight() {
		return $this->allWeight;
	}

	/**
	 * @return the $allCount
	 */
	public function getAllCount() {
		return $this->allCount;
	}

	
	
}
?>