<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_fache表
 *
 */
class Fache2TableInstall extends CreateTable {
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
			facheNumber 			varchar(6),						/* 车号 */
			materialCode			varchar(32) not null,				/* 材料代码 */

			shipsClassification 	varchar(5) ,						/* 船级 */
			material				varchar(5) ,						/* 材质 */
			thickness				double(7,1) ,						/* 厚 */
			width					int unsigned ,						/* 宽 */
			length					int unsigned ,						/* 长 */
			count 					int unsigned ,						/* 数量 */
			unitWeight				double(11,3) ,						/* 单重 */
			orderNumber				varchar(10),						/* 订单号 */
			orderSubitemNumber 		varchar(4),							/* 订单子项号 */

			remark1					tinytext,							/* 第一个备注,长度不超过255个字符 */
			remark2					tinytext,							/* 第二个备注,同上 */

			uploadTime				datetime not null,					/* 上传时间 */
			phase					enum("入库","出库", "销售") not null,	/* 阶段 */
			filename				varchar(30) not null				/* 所在的总表文件名称 */
		)
		engine = InnoDB
		default charset = utf8;' );
		$table = 'sss_fache';
		parent::__construct ( $sql, $table );
	}
}

?>