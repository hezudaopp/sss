<?php
require_once('CreateTable.class.php');
/**
 * 产品安装类
 * 用于安装sss_fache表
 *
 */
class FullfillOrderNumbersInstall extends CreateTable {
	public function __construct(){
		$sql = array(
					'update sss_fache, sss_main
						set sss_fache.orderNumber = sss_main.orderNumber,	
							sss_fache.orderSubitemNumber = sss_main.orderSubitemNumber
						where sss_fache.materialCode != "加片"
							and sss_fache.materialCode != "舾装" and sss_fache.materialCode != "扁钢"
							and sss_fache.materialCode = sss_main.materialCode',
					
					'update sss_fache, sss_main
						set sss_fache.orderNumber = sss_main.orderNumber,	
							sss_fache.orderSubitemNumber = sss_main.orderSubitemNumber
						where sss_fache.materialCode != "加片"
							and sss_fache.materialCode != "舾装" and sss_fache.materialCode != "扁钢"
							and sss_fache.materialCode = sss_main.materialCode
							and sss_fache.thickness = sss_main.thickness
							and sss_fache.width = sss_main.width
							and sss_fache.length = sss_main.length
							and sss_fache.material = sss_main.material
							and sss_fache.shipsClassification = sss_main.shipsClassification',
					
					'update sss_fachuan, sss_main
						set sss_fachuan.orderNumber = sss_main.orderNumber,	
							sss_fachuan.orderSubitemNumber = sss_main.orderSubitemNumber
						where sss_fachuan.materialCode != "加片"
							and sss_fachuan.materialCode != "舾装" and sss_fachuan.materialCode != "扁钢"
							and sss_fachuan.materialCode = sss_main.materialCode',
					
					'drop index main_mc_index on sss_main',
					'drop index fache_mc_index on sss_fache',
					'drop index fachuan_mc_index on sss_fachuan',
					
					'alter table sss_main add index main_mc_index(materialCode(15), orderNumber, orderSubitemNumber)',
					'alter table sss_fache add index fache_mc_index(materialCode(15), orderNumber, orderSubitemNumber)',
					'alter table sss_fachuan add index fachuan_mc_index(materialCode(15), orderNumber, orderSubitemNumber)'
		);
		$table = '<full filling orderNumbers>';
		parent::__construct($sql,$table);
	}
}

?>