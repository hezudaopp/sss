<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_monitor表
 *
 */
class ListTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_list;', '
		/*==============================================================*/
		/* Table: sss_list                                           */
		/*==============================================================*/
		create table sss_list
		(
			id						int unsigned not null auto_increment primary key,
			fileId					int unsigned not null,
			constraint list_file_fk foreign key(fileId) references sss_list_file(id) on delete cascade on update cascade,
			chukuDate				varchar(32),						/* 出库日期 */
			chukuNumber 			varchar(32),						/* 车号|船号 */
			materialCode			varchar(32),						/*材料代码*/
			material				varchar(32),						/* 材质 */
			shipsClassification		varchar(5),							/*船级*/
			thickness				double(7,1),						/* 厚 */
			width					int unsigned ,						/* 宽 */
			length					int unsigned ,						/* 长 */
			count 					int unsigned ,						/* 数量 */
			unitWeight				double(11,3),						/*单重*/
			weight					double(11,3),						/*重量*/
			filename  				varchar(40),						/*文件名*/
			orderNumber				varchar(16),						/* 订单号 */
			orderSubitemNumber 		varchar(10),						/* 订单子项号 */
			unitPrice				decimal(10,2),					/*受订单价*/
			purchaseNumber  		varchar(20),					/*购单号*/
			batchNumber				varchar(50),					/*批号*/
			destination				varchar(30),					/*目的地*/
			storePlace				varchar(30),					/*库存地*/
			remarks					tinytext,						/*备注*/
			certificateNumber		varchar(60),					/*证书号*/
			checkoutBatch			varchar(60),					/*结算批号*/
			materialNumber			varchar(60),						/*物料号*/
			consignmentBatch		varchar(60)						/*发货批次*/
			
		)
		ENGINE = InnoDB 
		CHARACTER SET utf8 COLLATE utf8_general_ci' );
		$table = 'sss_list';
		parent::__construct ( $sql, $table );
	}
}
?>