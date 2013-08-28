<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_monitor表
 *
 */
class ConsignmentTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_consignment;', '
		/*==============================================================*/
		/* Table: sss_consignment                                           */
		/*==============================================================*/
		create table sss_consignment
		(
			id						int unsigned not null auto_increment primary key,
			fileId					int unsigned not null,
			constraint consignment_file_fk foreign key(fileId) references sss_consignment_file(id) on delete cascade on update cascade,
			materialCode			varchar(32),						/* 材料代码 */
			shipNumber				varchar(10),						/*船号*/
			subsectionNumber		varchar(5),							/*分段号*/
			orderNumber				varchar(16),						/* 订单号 */
			orderSubitemNumber 		varchar(10),						/* 订单子项号 */
			purchaseNumber  		varchar(10),						/*购单号*/
			consignmentBatch		varchar(60),						/*发货批次*/
			remark					varchar(60)						/*备注*/
		)
		ENGINE = InnoDB 
		CHARACTER SET utf8 COLLATE utf8_general_ci;' );
		$table = 'sss_consignment';
		parent::__construct ( $sql, $table );
	}
}
?>