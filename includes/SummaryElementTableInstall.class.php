<?php
require_once ('CreateTable.class.php');
/**
 * 用于安装sss_summary_element表
 *
 */
class SummaryElementTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_summary_element;', '
		/*==============================================================*/
		/* Table: sss_summary_element                                           */
		/*==============================================================*/
		create table sss_summary_element
		(
			id						int unsigned not null auto_increment primary key,
			fileId					int unsigned not null,
			constraint summary_element_file_fk foreign key(fileId) references sss_summary_element_file(id) on delete cascade on update cascade,
			manufactory				varchar(40),					/*产家*/
			storePlace				varchar(40),					/*库存地*/
			destination				varchar(40),					/*目的地*/
			remark					varchar(60)						/*备注*/
		)
		ENGINE = InnoDB 
		CHARACTER SET utf8 COLLATE utf8_general_ci;' );
		$table = 'sss_summary_element';
		parent::__construct ( $sql, $table );
	}
}
?>