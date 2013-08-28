<?php

require_once 'MyException.class.php';
/**
 * 此文件为一些和数据库操作有关的异常
 */

/**
 * 数据库连接错误
 *
 */
class DBConnectException extends MyException {
	public function __construct() {
		$msg = '数据库连接错误:';
		$msg .= "\n" . DB::connect_error ();
		parent::__construct ( $msg );
	}
	
	public function __toString() {
		return $this->getMessage () . "<br />可能您输入的密码或者用户名不正确。";
	}
}

/**
 * 数据库查询错误
 */
class DBQueryException extends MyException {
	public function __construct($query) {
		$msg = '数据库查询错误，错误语句：' . DB::error () . '，查询语句：' . $query;
		parent::__construct ( $msg );
	}
	
	public function __toString() {
		$msg = "上传文件中有些数据有问题，请仔细检查然后上传.提示：<br />
		1.单重一列不能为空<br />
		2.材料代码字符个数不能超过32<br />
		3.厚宽长列不能有不是数字的单词(比如\"200-300\"这样的数字就会导致错误)
		";
		//return $msg;
		return $this->getMessage ();
	}
}

/**
 * 对值为null的数据库查询结果操作
 *
 */
class NullQueryResult extends MyException {
	public function __construct() {
		$msg = '操作的数据库查询结果 为null';
		parent::__construct ( $msg );
	}
}

?>