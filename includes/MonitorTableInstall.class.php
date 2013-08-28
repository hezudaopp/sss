<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_monitor表
 *
 */
class MonitorTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_monitor;', '
		/*==============================================================*/
		/* Table: sss_monitor                                           */
		/*==============================================================*/
		create table sss_monitor
		(
			id						int unsigned not null auto_increment primary key,
			fileId					int unsigned not null,
			constraint monitor_file_fk foreign key(fileId) references sss_monitor_file(id) on delete cascade on update cascade,
			materialCode			varchar(32),						/* 材料代码 */
			shipsClassification 	varchar(32),						/* 船级 */
			material				varchar(32),						/* 材质 */
			thickness				double(7,1),						/* 厚 */
			width					int unsigned ,						/* 宽 */
			length					int unsigned ,						/* 长 */
			count 					int unsigned ,						/* 数量 */
			orderNumber				varchar(16),						/* 订单号 */
			orderSubitemNumber 		varchar(10),						/* 订单子项号 */
			unitPrice				decimal(10,2),					/*受订单价*/
			batchNumber				varchar(10),					/*批号*/
			purchaseNumber  		varchar(10),					/*购单号*/
			destination				varchar(12),					/*目的地*/
			storePlace				varchar(12),					/*库存地*/
			certificateNumber		varchar(60),						/*证书号*/
			checkoutBatch			varchar(60),						/*结算清单*/
			materialNumber			varchar(60),						/*物料号*/
			consignmentBatch		varchar(60)						/*发货批次*/
		)
		ENGINE = InnoDB 
		CHARACTER SET utf8 COLLATE utf8_general_ci;' );
		$table = 'sss_monitor';
		parent::__construct ( $sql, $table );
	}
}
?>