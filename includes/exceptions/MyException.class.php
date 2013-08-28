<?php

/**
 * 封装了自己的Exception，继承自系统的Exception
 * 主要目的是方便为所有继承自MyException的类添加功能：
 * 比如添加写入日志的功能，对于某些一场直接发送到邮箱，
 * 或者输出可读性更强的错误信息
 */
class MyException extends Exception {
	public function __construct($msg = null, $code = 0) {
		$this->errorMsg = $msg;
		$this->errorCode = $code;
		parent::__construct ( $msg, $code );
		//DB::query('rollback;');
	}
}
?>