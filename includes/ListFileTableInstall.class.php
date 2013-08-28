<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_monitor_file表
 *
 */
class ListFileTableInstall extends CreateTable {
	public function __construct() {
		/* '
		alter table sss_monitor drop foreign key monitor_file_fk;
		', */
		$sql = array ('
		drop table if exists sss_list_file;', '
		/*==============================================================*/
		/* Table: sss_list_file                                           */
		/*==============================================================*/
		create table sss_list_file
		(
			id		int unsigned not null auto_increment primary key,
			filename			varchar(60) not null,					/* filename */
			record				tinytext,								/*  备注 */
			uploadTime			datetime								/* 监控文件上传时间 */
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_list_file';
		parent::__construct ( $sql, $table );
	}
}
?>