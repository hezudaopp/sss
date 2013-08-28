<?php
require_once ('CreateTable.class.php');
/**
 * 用于安装sss_summary_element_file表
 *
 */
class SummaryElementFileTableInstall extends CreateTable {
	public function __construct() {
		/* '
		alter table sss_summary drop foreign key summary_file_fk;
		', */
		$sql = array ('
		drop table if exists sss_summary_element_file;', '
		/*==============================================================*/
		/* Table: sss_summary_element_file                                           */
		/*==============================================================*/
		create table sss_summary_element_file
		(
			id		int unsigned not null auto_increment primary key,
			filename			varchar(60) not null,					/* filename */
			record				tinytext,								/*  备注 */
			uploadTime			datetime								/* 汇总因素文件上传时间 */
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_summary_element_file';
		parent::__construct ( $sql, $table );
	}
}
?>