<?php
require_once('SpreadSheet/Excel/writer.php');
class MultipleTableExporter{
	
	/**
	 * 导出时的文件名称
	 *
	 * @var string
	 */
	private $filename;
	
	/**
	 * Excel 表格
	 *
	 * @var Spreadsheet_Excel_Writer
	 */
	private $workbook;
	
	/**
	 * Excel 表格的一个sheet
	 *
	 * @var Spreadsheet_Excel_WorkSheet
	 */
	private $worksheet;
	
	
	private $rowIndex;
	
	private $format;
	private $sumCount;
	private $sumWeight;
	private $ths;
	
	function MultipleTableExporter($filename = null){
		if($filename == null){
			$this->filename = date('Ymd_His').'.xls';
		}else{
			$this->filename = $filename;
		}
		$this->workbook = new Spreadsheet_Excel_Writer();
		// Creating a worksheet
		$this->worksheet =& $this->workbook->addWorksheet(iconv('utf-8', 'gbk','sheet1'));
		
		$this->rowIndex = 0;
	}
	
	/**
	 * 设为私有的，是因为这个只在insertxxxxxData()函数中调用
	 *
	 * @param array $a
	 */
	private function insertData($a){
		$x = 0;
		if($this->ths != null){
			foreach($this->ths as $th){
				$th = iconv('utf-8', 'gbk', $th);
				$this->worksheet->write($this->rowIndex, $x, $th, $this->format);
				$x++;
			}
			$this->rowIndex++;
		}
		
		$x = 1;
		$height = 0;
		foreach($a as $col){
			$y = $this->rowIndex;
			foreach($col as $td){
				$td = iconv('utf-8', 'gbk', $td);
				$this->worksheet->write($y, $x, $td, $this->format);
				$y++;
			}
			$x++;
			$height = count($col);
		}
		$this->rowIndex += $height;
		
		
		if($this->sumCount == null){
			return;
		}
		$y = $this->rowIndex;
		$format =& $this->workbook->addFormat(array('bgColor' => 'red', 'color' => 'white'));
		$this->worksheet->write($y, 0, iconv('utf-8', 'gbk', '小结'), $format);
		$this->worksheet->write($y, 1, iconv('utf-8', 'gbk', '总数'), $format);
		$this->worksheet->write($y, 2, iconv('utf-8', 'gbk', $this->sumCount), $format);
		$this->worksheet->write($y, 3, iconv('utf-8', 'gbk', '总重量'), $format);
		$this->worksheet->write($y, 4, iconv('utf-8', 'gbk', $this->sumWeight), $format);
		$this->rowIndex++;
	}
	
	/**
	 * 设置计划中的总个数
	 *
	 * @param int $count
	 */
	function setMainSumCount($count){
		$this->mainSumCount = $count;
	}
	
	/**
	 * 设置总表中的总重量
	 *
	 * @param double $weight
	 */
	function setMainSumWeight($weight){
		$this->mainSumWeight = $weight;
	}
	
	/**
	 * 在引用这个之前，在外部一定要调用setMainSumxxxx()函数
	 *
	 * @param array $a
	 */
	function insertMainData($a){
		$this->format = &$this->workbook->addFormat(array('color' => 'green'));
		$this->ths = array('总表','批次','材料代码','生产厂家','船级','材质',
				'厚','宽','长','数量','单重','重量','备注',
				'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次');
		$this->sumCount = $this->mainSumCount;
		$this->sumWeight = $this->mainSumWeight;
		$this->insertData($a);
	}
	
	
	function setRukuSumCount($count){
		$this->rukuSumCount = $count;
	}
	function setRukuSumWeight($weight){
		$this->rukuSumWeight = $weight;
	}
	
	/**
	 * 在调用这个函数之前一定要先调用setRukuSumxxxx()函数
	 *
	 * @param array $a
	 */
	function insertRukuData($a){
		$this->format = &$this->workbook->addFormat(array('color' => 'blue'));
		$this->ths = array('入库','日期','材料代码','车号','船级','材质','厚',
			'宽','长','数量','单重','重量','备注',
			'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$this->sumCount = $this->rukuSumCount;
		$this->sumWeight = $this->rukuSumWeight;
		$this->insertData($a);
	}
	
	/**
	 * 在调用这个函数之前不用调用setChukuFacheSumxxxx(),
	 * 因为出库包括fache 和 fachuan, 在发船下面才显示总计
	 *
	 * @param array $a
	 */
	function insertChukuFacheData($a){
		$this->format = &$this->workbook->addFormat(array('color' => 'blue'));
		$this->ths = array('出库','日期','材料代码','车/船号','船级','材质','厚',
			'宽','长','数量','单重','重量','备注',
			'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$this->sumCount = null;
		$this->sumWeight = null;
		$this->insertData($a);
	}
	
	function setChukuSumCount($count){
		$this->chukuSumCount = $count;
	}
	function setChukuSumWeight($weight){
		$this->chukuSumWeight = $weight;
	}
	
	/**
	 * 在调用这个函数之前必须调用setChukuSumxxxx()函数，因为发船下面要显示出库的小结
	 *
	 * @param array $a
	 */
	function insertChukuFachuanData($a){
		$this->format = &$this->workbook->addFormat(array('color' => 'red'));
		$this->ths = array('出库','日期','材料代码','车/船号','船级','材质','厚',
			'宽','长','数量','单重','重量','备注',
			'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$this->sumCount = $this->chukuSumCount;
		$this->sumWeight = $this->chukuSumWeight;
		$this->insertData($a);
	}
	
	function setSellSumCount($count){
		$this->sellSumCount = $count;
	}
	function setSellSumWeight($weight){
		$this->sellSumWeight = $weight;
	}
	
	/**
	 * 调用这个函数之前一定要调用setSellSumxxxx()函数
	 *
	 * @param array $a
	 */
	function insertSellData($a){
		$this->format = &$this->workbook->addFormat(array('color' => 'blue'));
		$this->ths = array('销售','日期','材料代码','车号','船级','材质','厚',
			'宽','长','数量','单重','重量','备注',
			'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$this->sumCount = $this->sellSumCount;
		$this->sumWeight = $this->sellSumWeight;
		$this->insertData($a);
	}
	
	function export(){
		$this->workbook->send($this->filename);
		$this->workbook->close();
	}
}

?>