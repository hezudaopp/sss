<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_log表
 *
 */
class LogTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_log;', '
		/*==============================================================*/
		/* Table: sss_log                                           */
		/*==============================================================*/
		create table sss_log
		(
			id		int unsigned not null auto_increment primary key,
			time				datetime,								/* 记录日期 */
			content				text,								/* 记录内容 */
			content2				text,								/* 记录内容,改的时候需要 */
			type				smallint							/* 1：增，2：删，3：改 */
			check(phase in ("1", "2", "3"))
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_log';
		parent::__construct ( $sql, $table );
	}
}

?>