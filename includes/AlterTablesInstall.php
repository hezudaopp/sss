<?php
require_once ('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_fache表
 *
 */
class AlterTablesInstall extends CreateTable {
	public function __construct() {
		$sql = array ('drop index main_mc_index on sss_main', 'drop index fache_mc_index on sss_fache', 'drop index fachuan_mc_index on sss_fachuan', 

		'alter table sss_main change manufactory manufactory varchar(8),
										change shipsClassification shipsClassification varchar(5),
										change material material varchar(5),
										change orderNumber orderNumber varchar(10),
										change orderSubitemNumber orderSubitemNumber varchar(4)', 

		'update sss_fache set phase = "1" where phase = "入库"', 'update sss_fache set phase = "2" where phase = "出库"', 'update sss_fache set phase = "3" where phase = "销售"', 

		'alter table sss_fache change facheNumber facheNumber varchar(6),
										change shipsClassification shipsClassification varchar(5),
										change material material varchar(5),
										change filename filename varchar(30) not null,
										change phase phase enum("入库","出库","销售") not null,
										add column orderNumber varchar(10),
										add column orderSubitemNumber varchar(4)', 

		'alter table sss_fachuan change fachuanNumber fachuanNumber varchar(6),
										change shipsClassification shipsClassification varchar(5),
										change material material varchar(5),
										change filename filename varchar(30) not null,
										add column orderNumber varchar(10),
										add column orderSubitemNumber varchar(4)', 

		'alter table sss_main add index main_mc_index(materialCode(15))', 'alter table sss_fache add index fache_mc_index(materialCode(15))', 'alter table sss_fachuan add index fachuan_mc_index(materialCode(15))' );
		$table = '<altering all tables>';
		parent::__construct ( $sql, $table );
	}
}

?>