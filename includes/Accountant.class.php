<?php
require_once('DB.class.php');

class Accountant{
	/**
	 * Accountant的查询语句
	 *
	 * @var string
	 */
	protected $sql;
	
	/**
	 * mysql的查询结果，没有进行任何处理
	 *
	 * @var mysqli_result
	 */
	protected $result;

	/**
	 * 汉字的材料代码,这里不包括“加片”，因为用户说要将汉字的材料代码和加片分开。
	 *
	 * @var string
	 */
	private static $chineseChars = array("现货", "大坞1", "大坞2", "大坞3", "大坞4", "大坞5");

	/**
	 * 执行查询
	 *
	 */
	public function execute(){
		$this->result = DB::query($this->sql);
	}

	/**
	 * 获得查询语句
	 *
	 * @return unknown
	 */
	public function getSql(){
		return $this->sql;
	}

	/**
	 * 获得查询结果,未经过处理
	 *
	 * @return mysqli_result
	 */
	public function getResult(){
		return $this->result;
	}

	/**
	 * 获得查询语句中的where子句，以使查询结果中不包含任何汉字材料代码的信息
	 *
	 * @param boolean $noJiapian
	 * @return string
	 */
	protected function getNotAndChineseMaterialCodes($noJiapian = false){
		$whereArr = array();
		$chars = self::$chineseChars;
		foreach($chars as $key => $val){
			array_push($whereArr, "materialCode not like '%{$chars[$key]}%'");
		}
		if($noJiapian == false){
			array_push($whereArr, "materialCode != '加片'");
		}

		return join(" and ", $whereArr);
	}



	/**
	 * 获得查询语句中的where子句，以使查询结果包含所有汉字材料代码
	 *
	 * @param boolean $noJiapian
	 * @return string
	 */
	protected function getIsOrChineseMaterialCodes($noJiapian = false){
		$whereArr = array();
		$chars = self::$chineseChars;
		foreach($chars as $key => $val){
			array_push($whereArr, "materialCode like '%{$chars[$key]}%'");
		}
		if($noJiapian == false){
			array_push($whereArr, "materialCode = '加片'");
		}

		return join(" or ", $whereArr);
	}
	
	public static function getChineseCharsArray(){
		return self::$chineseChars;
	}
}
?>