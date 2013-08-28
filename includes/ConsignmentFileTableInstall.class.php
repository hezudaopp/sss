<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_consignment_file表
 *
 */
class ConsignmentFileTableInstall extends CreateTable {
	public function __construct() {
		/* '
		alter table sss_consignment drop foreign key consignment_file_fk;
		', */
		$sql = array ('
		drop table if exists sss_consignment_file;', '
		/*==============================================================*/
		/* Table: sss_consignment_file                                           */
		/*==============================================================*/
		create table sss_consignment_file
		(
			id		int unsigned not null auto_increment primary key,
			filename			varchar(60) not null,					/* filename */
			record				tinytext,								/*  备注 */
			uploadTime			datetime								/* 发货批次文件上传时间 */
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_consignment_file';
		parent::__construct ( $sql, $table );
	}
}
?>