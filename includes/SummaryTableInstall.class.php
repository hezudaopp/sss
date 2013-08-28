<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_summary表
 *
 */
class SummaryTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_summary;', '
		/*==============================================================*/
		/* Table: sss_summary                                           */
		/*==============================================================*/
		create table sss_summary
		(
			id						int unsigned not null auto_increment primary key,
			fileId					int unsigned not null,
			constraint summary_file_fk foreign key(fileId) references sss_summary_file(id) on delete cascade on update cascade,
			shipNumber				varchar(20),					/*船号*/
			subNumber				varchar(20),					/*分段号*/
			consignmentBatch		varchar(60),					/*发货批次*/
			remark					varchar(60)					/*备注*/
		)
		ENGINE = InnoDB 
		CHARACTER SET utf8 COLLATE utf8_general_ci;' );
		$table = 'sss_summary';
		parent::__construct ( $sql, $table );
	}
}
?>