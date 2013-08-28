<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_fache表
 *
 */
class FacheTableInstall extends CreateTable {
	public function __construct() {
		$sql = array ('
		drop table if exists sss_fache;', '
		/*==============================================================*/
		/* Table: sss_fache                                           */
		/*==============================================================*/
		create table sss_fache
		(
			id		int unsigned not null auto_increment primary key,
			facheDate				date,								/* 发车日期 */
			facheNumber 			varchar(16),						/* 车号 */
			materialCode			varchar(32) not null,				/* 材料代码 */

			shipsClassification 	varchar(32) ,						/* 船级 */
			material				varchar(32) ,						/* 材质 */
			thickness				double(7,1) ,						/* 厚 */
			width					int unsigned ,						/* 宽 */
			length					int unsigned ,						/* 长 */
			count 					int unsigned ,						/* 数量 */
			unitWeight				double(11,3) ,						/* 单重 */

			remark1					tinytext,							/* 第一个备注,长度不超过255个字符 */
			remark2					tinytext,							/* 第二个备注,同上 */
			remark3					tinytext,							/* 第三个备注,同上 */
			remark4					tinytext,							/* 第四个备注,同上 */
			remark5					tinytext,							/* 第五个备注,同上 */

			uploadTime				datetime not null,					/* 上传时间 */
			phase					varchar(8) not null,				/* 阶段 */
			filename				varchar(100) not null,				/* 所在的总表文件名称 */
			
			orderNumber				varchar(16),						/* 订单号 */
			orderSubitemNumber 		varchar(10),						/* 订单子项号 */
			
			unitPrice				decimal(10,2),						/*受订单价*/
			batchNumber				varchar(50),						/*批号*/
			purchaseNumber			varchar(20),						/*购单号*/
			destination				varchar(30),						/*目的地*/
			storePlace				varchar(30),						/*库存地*/
			certificateNumber		varchar(60),						/*证书号*/
			checkoutBatch			varchar(60),						/*结算清单*/
			materialNumber			varchar(60),						/*物料号*/
			consignmentBatch		varchar(60),						/*发货批次*/

			check(phase in ("入库", "出库", "销售"))
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_fache';
		parent::__construct ( $sql, $table );
	}
}

?>