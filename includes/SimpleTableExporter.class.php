<?php
require_once ('Spreadsheet/Excel/Writer.php');
require_once 'SummaryElementFileAccountant.class.php';

class SimpleTableExporter{
	
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
	 * Excel sheet
	 * @param $filename
	 * @return unknown_type
	 */
	private $sheet;
	
	function SimpleTableExporter($filename = null){
		if($filename == null){
			$this->filename = date('Ymd_His').'.xls';
		}else{
			$this->filename = $filename;
		}
		$this->workbook = new Spreadsheet_Excel_Writer();
	}
	
	/**
	 * 创建一个新sheet
	 */
	function createNewSheet(){
		$this->sheet = $this->workbook->addWorksheet();
	}
	/**
	 * 向workbook中插入总表数据，
	 * $a为数组，其中的数据是有顺序的，顺序为：
	 * 见下面$ths 变量的元素顺序
	 * 
	 *
	 * @param array $array
	 */
	function insertMainData($a){
		// Creating a worksheet
		$worksheet =& $this->workbook->addWorksheet(iconv('utf-8', 'gbk','总表'));
		$format = &$this->workbook->addFormat(array('color' => 'green'));
		$ths = array('批次','材料代码','生产厂家','船级','材质',
				'厚','宽','长','数量','单重','重量','备注',
				'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$x = 0;
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		foreach($a as $col){
			$y = 1;
			foreach($col as $td){
				$td = iconv('utf-8', 'gbk', $td);
				$worksheet->write($y, $x, $td, $format);
				$y++;
			}
			$x++;
		}
	}
	
	/**
	 * 向workbook中插入发车数据，
	 * $a为数组，其中的数据是有顺序的，顺序为：
	 * 见下面$ths 变量的元素顺序
	 *
	 * @param array $a
	 */
	function insertFacheData($a){
		// Creating a worksheet
		$worksheet =& $this->workbook->addWorksheet(iconv('utf-8', 'gbk', '发车'));
		$format = &$this->workbook->addFormat(array('color' => 'blue'));
		$ths = array('日期','材料代码','车号','船级','材质','厚',
			'宽','长','数量','单重','重量','备注',
			'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$x = 0;
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		foreach($a as $col){
			$y = 1;
			foreach($col as $td){
				$td = iconv('utf-8', 'gbk', $td);
				$worksheet->write($y, $x, $td, $format);
				$y++;
			}
			$x++;
		}
	}
	
	/**
	 * 向workbook中插入发船数据，
	 * $a为数组，其中的数据是有顺序的，顺序为：
	 * 见下面$ths 变量的元素顺序
	 *
	 * @param unknown_type $a
	 */
	function insertFachuanData($a){
		// Creating a worksheet
		$worksheet =& $this->workbook->addWorksheet(iconv('utf-8', 'gbk', '发船'));
		$format = &$this->workbook->addFormat(array('color' => 'red'));
		$ths = array('日期','材料代码','船号','船级','材质','厚',
			'宽','长','数量','单重','重量','备注',
			'上传时间','上传文件','订单号','订单子项号','受订单价','批号','物料号','购单号','目的地','库存地','证书号','结算批号','发货批次','阶段');
		$x = 0;
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		foreach($a as $col){
			$y = 1;
			foreach($col as $td){
				$td = iconv('utf-8', 'gbk', $td);
				$worksheet->write($y, $x, $td, $format);
				$y++;
			}
			$x++;
		}
	}
	
	function insertCustomData($a, $ths, $sheetname, $color = 'black'){
		// Creating a worksheet
		$worksheet =& $this->workbook->addWorksheet(iconv('utf-8', 'gbk', $sheetname));
		$format = &$this->workbook->addFormat(array('color' => $color));
		$x = 0;
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		foreach($a as $col){
			$y = 1;
			foreach($col as $td){
				$td = iconv('utf-8', 'gbk', $td);
				$worksheet->write($y, $x, $td, $format);
				$y++;
			}
			$x++;
		}
	}
	
	/**
	 * 添加汇总导出表格的船号/发货批次信息
	 * @return unknown_type
	 */
	function insertHeadColumn($column,$secondColumnName,$shipNumber,$rows = array()){
		$y = 2;//从第三行开始插入船号和发货批次的信息
		foreach($rows as $td){
			$td = iconv('utf-8', 'gbk', $td);
			$this->sheet->write($y, $column, $shipNumber);
			if($shipNumber == "")
				$this->sheet->write($y, $column, $td);
			else
				$this->sheet->write($y, $column+1, $td);
			$y++;
		}
		$this->sheet->write(0,$column,iconv('utf-8','gbk','船号'));
		$this->sheet->mergeCells(0,$column,1,$column);
		if($secondColumnName!=""){//如果导出的是船号而没有其他的约束条件
			$this->sheet->write(0,$column+1,iconv('utf-8','gbk',$secondColumnName));
			$this->sheet->mergeCells(0,$column+1,1,$column+1);
			return $column+2;
		}
		return $column+1;
	}
	
	/**
	 * 插入特定船号和发货批次下的分段号信息或者插入相反的信息（特定船号和分段号下发货批次信息）
	 * @param $row 当前运行的行号
	 * @param $column 要插入的列号
	 * @param $columnName	列头名
	 * @param $subNumberStr	插入的分段号（发货批次）信息
	 * @return unknown_type
	 */
	function insertSubNumberOrConsignmentBatchColumn($row,$column,$columnName,$str){
		if($row==2)//第一次添加信息需要加入表头
			$this->sheet->write(0,$column,iconv('utf-8','gbk',$columnName));
		$this->sheet->write($row, $column, iconv('utf-8', 'gbk', $str));
		$this->sheet->mergeCells(0,$column,1,$column);//合并第一行下方的一个单元格
		return $column+1;
	}
	
	/**
	 * 添加汇总导出表格的船号/分段号信息
	 * @return unknown_type
	 */
	function insertSubNumber($column,$shipNumber,$subNumbers){
		$this->sheet->write(0,$column+1,iconv('utf-8','gbk','分段号'));
		$this->sheet->write(0,$column,iconv('utf-8','gbk','船号'));
//		$this->sheet->write(0,$column,'船号');
//		$this->sheet->write(0,$column+1,'分段号');
		$y = 2;
		foreach($subNumbers as $td){
			$td = iconv('utf-8', 'gbk', $td);
			$this->sheet->write($y, $column, $shipNumber);
			$this->sheet->write($y, $column+1, $td);
			$y++;
		}
		$this->sheet->mergeCells(0,$column,1,$column);
		$this->sheet->mergeCells(0,$column+1,1,$column+1);
		return $column+2;
	}
	
/**
	 * 添加汇总导出表格的船号/日期信息
	 * @return unknown_type
	 */
	function insertDate($column,$shipNumber,$dates){
		$this->sheet->write(0,$column+1,iconv('utf-8','gbk','日期'));
		$this->sheet->write(0,$column,iconv('utf-8','gbk','船号'));
//		$this->sheet->write(0,$column,'船号');
//		$this->sheet->write(0,$column+1,'月份');
		$y = 2;
		foreach($dates as $td){
			$td = iconv('utf-8', 'gbk', $td);
			$this->sheet->write($y, $column, $shipNumber);
			$this->sheet->write($y, $column+1, $td);
			$y++;
		}
		$this->sheet->mergeCells(0,$column,1,$column);
		$this->sheet->mergeCells(0,$column+1,1,$column+1);
		return $column+2;
	}
	
	/**
	 * 添加列信息
	 * @param $row 当前执行到的行号
	 * @param $column 当前执行到的列号
	 * @param $sum 订货数量的总计信息
	 * @param $columnName	列名，可能取值：“订货数量”，“入库”，“发货”，“库中”，“未动”
	 * @param $subColumnN 表示第N个列汇总因素，可能取值：“生产厂家”，“库存地”，“目的地”,"出库方式"
	 * @return $column 执行结束后的列号
	 */
	function insertColumn($row,$column,$columnName,$sum,$subColumn1=array(),$subColumn2=array(),$subColumn3=array(),$sumColumn4=array()) {
		$columnBegin = $column;
		
		if((!empty($sum) || !empty($manufactories)) && $row == 2){//当前执行的行号为2（第一次插入行），需要将表头信息补上
			$this->sheet->write(0,$column,iconv('utf-8','gbk',$columnName));
		}
	
		if(!empty($sum)){//用户如果选择了订货数量中的总计，则插入“总计”这几列信息
			if($row==2){
				$this->sheet->write(1,$column,iconv('utf-8','gbk','总计'));
				$this->sheet->mergeCells(1,$column,1,$column+1);
			}
			foreach($sum as $col){
				$this->sheet->write($row,$column,$col[0]);
				$this->sheet->write($row,$column+1,$col[1]);
				$column+=2;
			}
		}
		
		if(!empty($subColumn1)){
			foreach ($subColumn1 as $col){
				if($row==2){
					$this->sheet->write(1,$column,iconv('utf-8', 'gbk',$col[0]));
					$this->sheet->mergeCells(1,$column,1,$column+1);
				}
				$this->sheet->write($row,$column,$col[1]);
				$this->sheet->write($row,$column+1,$col[2]);
				$column += 2;
			}
		}
		
		if(!empty($subColumn2)){
			foreach ($subColumn2 as $col){
				if($row==2){
					$this->sheet->write(1,$column,iconv('utf-8', 'gbk',$col[0]));
					$this->sheet->mergeCells(1,$column,1,$column+1);
				}
				$this->sheet->write($row,$column,$col[1]);
				$this->sheet->write($row,$column+1,$col[2]);
				$column += 2;
			}
		}
		
		if(!empty($subColumn3)){
			foreach ($subColumn3 as $col){
				if($row==2){
					$this->sheet->write(1,$column,iconv('utf-8', 'gbk',$col[0]));
					$this->sheet->mergeCells(1,$column,1,$column+1);
				}
				$this->sheet->write($row,$column,$col[1]);
				$this->sheet->write($row,$column+1,$col[2]);
				$column += 2;
			}
		}
		
		if(!empty($sumColumn4)){
			foreach ($sumColumn4 as $col){
				if($row==2){
					$this->sheet->write(1,$column,iconv('utf-8', 'gbk',$col[0]));
					$this->sheet->mergeCells(1,$column,1,$column+1);
				}
				$this->sheet->write($row,$column,$col[1]);
				$this->sheet->write($row,$column+1,$col[2]);
				$column += 2;
			}
		}
		
		//需要将头行的表头信息合并单元格
		if($row==2){
			$this->sheet->mergeCells(0,$columnBegin,0,$column-1);
		}
		return $column;
	}
	
	/*function insertCheckoutData($a, $ths, $sheetname, $color = 'black', $value1, $value2){
		// Creating a worksheet
		$worksheet =& $this->workbook->addWorksheet(iconv('utf-8', 'gbk', $sheetname));
		$format = &$this->workbook->addFormat(array('color' => $color));
		$formatRed = &$this->workbook->addFormat(array('color' => 'red','bgcolor' => 'black'));
		$x = 0;
		foreach($ths as $th){
			$th = iconv('utf-8', 'gbk', $th);
			$worksheet->write(0, $x, $th, $format);
			$x++;
		}
		$x = 0;
		foreach($a as $col){
			$y = 1;
			foreach($col as $td){
				$td = iconv('utf-8', 'gbk', $td);
				if($value1[$y-1]<$value2[$y-1])
					$worksheet->write($y, $x, $td, $formatRed);
				else 
					$worksheet->write($y, $x, $td, $format);
				$y++;
			}
			$x++;
		}
	}*/
	/**
	 * 导出表格
	 *
	 */
	function export(){
		// sending HTTP headers
		$this->workbook->send($this->filename);
		$this->workbook->close();
	}
	
	function getWorkbook(){
		return $this->workbook;
	}
}

?>