<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_main表
 *
 */
class Main2TableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_main;', '
		/*==============================================================*/
		/* Table: sss_main                                           */
		/*==============================================================*/
		create table sss_main
		(
			id						int unsigned not null auto_increment primary key,
			sequenceNumber 			varchar(16),						/* 批次 */
			materialCode			varchar(32) not null,				/* 材料代码 */
			manufactory				varchar(8),						/* 生产厂家 */
			shipsClassification 	varchar(5),						/* 船级 */
			material				varchar(5),						/* 材质 */
			thickness				double(7,1),						/* 厚 */
			width					int unsigned ,						/* 宽 */
			length					int unsigned ,						/* 长 */
			count 					int unsigned ,						/* 数量 */
			unitWeight				double(11,3) ,						/* 单重 */
			orderNumber				varchar(10),						/* 订单号 */
			orderSubitemNumber 		varchar(4),						/* 订单子项号 */
			unitPrice				decimal(10,2),					/*受订单价*/
			batchNumber				varchar(10),					/*批号*/
			purchaseNumber  		varchar(10),					/*购单号*/
			destination				varchar(12),					/*目的地*/
			storePlace				varchar(12),					/*库存地*/
			remark1					tinytext,							/* 第一个备注,长度不超过255个字符 */
			remark2					tinytext,							/* 第二个备注,同上 */
			remark3					tinytext,							/* 第三个备注,同上 */
			remark4					tinytext,							/* 第四个备注,同上 */
			remark5					tinytext,							/* 第五个备注,同上 */
			uploadTime				datetime not null,					/* 上传时间 */
			filename				varchar(30) not null				/* 所在的总表文件名称 */
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_main';
		parent::__construct ( $sql, $table );
	}
}

?>