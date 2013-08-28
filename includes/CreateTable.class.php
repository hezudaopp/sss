<?php

require_once ("DB.class.php");
/**
 * 数据库表的安装类，
 * 1.执行数据库的安装
 * 2.向外显示反馈信息
 *
 */
class CreateTable {
	public function __construct($sql, $table = null) {
		try {
			foreach ( $sql as $query ) {
				DB::query ( $query );
			}
			echo "<span 
				style='color:green'>$table 安装成功</span><br />";
		} catch ( Exception $e ) {
			$errMsg = nl2br ( htmlspecialchars ( $e, ENT_QUOTES ) );
			echo "<span style='color:red'>$table 安装失败</span><br />
			<p style='font-size:10px; color:blue'>$errMsg</p>";
		}
	}
}
;
?>