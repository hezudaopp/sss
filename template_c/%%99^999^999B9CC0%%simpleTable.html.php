<?php /* Smarty version 2.6.26, created on 2010-10-25 10:07:54
         compiled from simpleTable.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'simpleTable.html', 155, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>查询结果</title>
		<style>
td{
	border-right:1px solid #cbdced;
	border-bottom: 1px solid #cbdced;
	padding:3px;
}

th{
	background-color:#cbdced;
	padding:3px;
	border-right:1px solid #fff;
	border-bottom:1px solid #fff;
}
table{
	clear:both;
	border-top: 1px solid #cbdced;
	border-left: 1px solid #cbdced;
}
body{
	font-size:14px;
}
.green{
	color:green;
}

.red{
	color:red;
}

.blue{
	color:blue;
}
.exportBtn{
	margin-right:30px;
	margin-top:2px;
	margin-bottom:2px;
	float:right;
	display:block;
	padding:1px;
}

span[class=exportBtn]:hover{
	background-color:#8A95A1;
	color:white;
}
		</style>
		<script type="text/javascript">
		function allChecksChange(obj){
			checks = document.getElementsByTagName("input");
			for(i = 0; i < checks.length; i++){
				o = checks[i];
				type = o.attributes.getNamedItem("type");
				if(type.nodeValue == 'checkbox'){
					o.checked = obj.checked;
				}
			}
			//checks.checked = obj.checked;
		}
		
		function deleteConfirm(){
		return window.confirm('你确实要删除选中的这些记录吗？');
		}
		</script>
	</head>
	<body>
	<?php if ($this->_tpl_vars['advancedSearch']): ?>
	<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1;">
			<span><a href="excelUpload.html">表格上传</a></span>
			| <span><a href="index.html">主页</a></span>
			| <span><a href="advancedSearch.html">回到高级搜索</a></span>
	</div>
	<?php endif; ?>
	
	<form action="batchDelete.php" method="POST" onsubmit="return deleteConfirm()" >
	
	<?php if ($this->_tpl_vars['fache_materialCode'] || $this->_tpl_vars['fachuan_materialCode'] || $this->_tpl_vars['main_sequenceNumber']): ?>
		<?php if ($this->_tpl_vars['advancedSearch']): ?>
			<span class="exportBtn">
				<input type="submit" value=" 删除选中 "/>
			</span>
			<span class="exportBtn">
				<a href="exportAdvancedTable.php?materialCode=<?php echo $this->_tpl_vars['materialCode']; ?>
&thicknessFrom=<?php echo $this->_tpl_vars['thicknessFrom']; ?>
&thicknessTo=<?php echo $this->_tpl_vars['thicknessTo']; ?>
&widthFrom=<?php echo $this->_tpl_vars['widthFrom']; ?>
&widthTo=<?php echo $this->_tpl_vars['widthTo']; ?>
&lengthFrom=<?php echo $this->_tpl_vars['lengthFrom']; ?>
&lengthTo=<?php echo $this->_tpl_vars['lengthTo']; ?>
&faDateFrom=<?php echo $this->_tpl_vars['faDateFrom']; ?>
&faDateTo=<?php echo $this->_tpl_vars['faDateTo']; ?>
&shipsClassification=<?php echo $this->_tpl_vars['shipsClassification']; ?>
&material=<?php echo $this->_tpl_vars['material']; ?>
&uploadTimeFrom=<?php echo $this->_tpl_vars['uploadTimeFrom']; ?>
&uploadTimeTo=<?php echo $this->_tpl_vars['uploadTimeTo']; ?>

				&filename=<?php echo $this->_tpl_vars['filename']; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber']; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>
&unitPrice=<?php echo $this->_tpl_vars['unitPrice']; ?>
&batchNumber=<?php echo $this->_tpl_vars['batchNumber']; ?>
&purchaseNumber=<?php echo $this->_tpl_vars['purchaseNumber']; ?>
&destination=<?php echo $this->_tpl_vars['destination']; ?>
&storePlace=<?php echo $this->_tpl_vars['storePlace']; ?>
&sequenceNumber=<?php echo $this->_tpl_vars['sequenceNumber']; ?>
&manufactory=<?php echo $this->_tpl_vars['manufactory']; ?>

				&certificateNumber=<?php echo $this->_tpl_vars['certificateNumber']; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['checkoutBatch']; ?>
&materialNumber=<?php echo $this->_tpl_vars['materialNumber']; ?>
&consignmentBatch=<?php echo $this->_tpl_vars['consignmentBatch']; ?>

				&ruku=<?php echo $this->_tpl_vars['ruku']; ?>
&chuku=<?php echo $this->_tpl_vars['chuku']; ?>
&sale=<?php echo $this->_tpl_vars['sale']; ?>
&main=<?php echo $this->_tpl_vars['main']; ?>
&remark=<?php echo $this->_tpl_vars['remark']; ?>
&faNumber=<?php echo $this->_tpl_vars['faNumber']; ?>
" title="点击进行导出结果"> 导出表格 </a>
			</span>
		<?php else: ?>
			<span class="exportBtn">
				<a href="exportSimpleTable.php?stype=<?php echo $this->_tpl_vars['stype']; ?>
&keyname=<?php echo $this->_tpl_vars['keyname']; ?>
" title="点击进行导出结果">导出表格</a>
			</span>
		<?php endif; ?>
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	<table cellpadding="0" cellspacing="0">
		<?php if ($this->_tpl_vars['main_sequenceNumber']): ?>
		<tr>
			<th>批次</th>
			<th>材料代码</th>
			<th>生产厂家</th>
			<th>船级</th>
			<th>材质</th>
			<th>厚</th>
			<th>宽</th>
			<th>长</th>
			<th>数量</th>
			<th>单重</th>
			<th>重量</th>
			<th>备注</th>
			<th>上传时间</th>
			<th>上传文件</th>
			<th>订单号</th>
			<th>订单子项号</th>
			<th>受订单价</th>
			<th>批号</th>
			<th>物料号</th>
			<th>购单号</th>
			<th>目的地</th>
			<th>库存地</th>
			<th>结算</th>
			<th width="6px">证书号</th>
			<th width="6px">结算批号</th>
			<th>发货批次</th>
			<th>阶段</th>
			<?php if ($this->_tpl_vars['advancedSearch']): ?>
				<th width="6px">编辑</th>
				<th width="6px">删除<input type="checkbox" name="i_d" onchange="allChecksChange(this)" /></th>
			<?php endif; ?>
		</tr>
		
		<?php unset($this->_sections['main']);
$this->_sections['main']['name'] = 'main';
$this->_sections['main']['loop'] = is_array($_loop=$this->_tpl_vars['main_sequenceNumber']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['main']['show'] = true;
$this->_sections['main']['max'] = $this->_sections['main']['loop'];
$this->_sections['main']['step'] = 1;
$this->_sections['main']['start'] = $this->_sections['main']['step'] > 0 ? 0 : $this->_sections['main']['loop']-1;
if ($this->_sections['main']['show']) {
    $this->_sections['main']['total'] = $this->_sections['main']['loop'];
    if ($this->_sections['main']['total'] == 0)
        $this->_sections['main']['show'] = false;
} else
    $this->_sections['main']['total'] = 0;
if ($this->_sections['main']['show']):

            for ($this->_sections['main']['index'] = $this->_sections['main']['start'], $this->_sections['main']['iteration'] = 1;
                 $this->_sections['main']['iteration'] <= $this->_sections['main']['total'];
                 $this->_sections['main']['index'] += $this->_sections['main']['step'], $this->_sections['main']['iteration']++):
$this->_sections['main']['rownum'] = $this->_sections['main']['iteration'];
$this->_sections['main']['index_prev'] = $this->_sections['main']['index'] - $this->_sections['main']['step'];
$this->_sections['main']['index_next'] = $this->_sections['main']['index'] + $this->_sections['main']['step'];
$this->_sections['main']['first']      = ($this->_sections['main']['iteration'] == 1);
$this->_sections['main']['last']       = ($this->_sections['main']['iteration'] == $this->_sections['main']['total']);
?>
		<tr class="green">
			<td><?php echo $this->_tpl_vars['main_sequenceNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_materialCode'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_manufactory'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_shipsClassification'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_material'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_thickness'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_width'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_length'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_count'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_unitWeight'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_weight'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_remark'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_uploadTime'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_filename'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_orderNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_orderSubitemNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_unitPrice'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['main_batchNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['main_materialNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['main_purchaseNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_destination'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_storePlace'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php if ($this->_tpl_vars['checkout']): ?>
				<a href="editCheckout.php?materialCode=<?php echo $this->_tpl_vars['main_materialCode'][$this->_sections['main']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['main_orderNumber'][$this->_sections['main']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['main_orderSubitemNumber'][$this->_sections['main']['index']]; ?>
&certificateNumber=<?php echo $this->_tpl_vars['main_certificateNumber'][$this->_sections['main']['index']]; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['main_checkoutBatch'][$this->_sections['main']['index']]; ?>
">已结算</a>
				<?php endif; ?>
			</td>
			<td><?php echo $this->_tpl_vars['main_certificateNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_checkoutBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_consignmentBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td>总表</td>
			<?php if ($this->_tpl_vars['advancedSearch']): ?>
				<td><a href="rowEdit.php?type=main&id=<?php echo $this->_tpl_vars['main_ids'][$this->_sections['main']['index']]; ?>
">编辑</a></td>
				<td><input type="checkbox" name="main_id<?php echo $this->_tpl_vars['main_ids'][$this->_sections['main']['index']]; ?>
" value="<?php echo $this->_tpl_vars['main_ids'][$this->_sections['main']['index']]; ?>
" /></td>
			<?php endif; ?>
		</tr>
		<?php endfor; endif; ?>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['fache_materialCode'] || $this->_tpl_vars['fachuan_materialCode']): ?>
		<tr>
			<th>日期</th>
			<th>材料代码</th>
			<th>车号/船号</th>
			<th>船级</th>
			<th>材质</th>
			<th>厚</th>
			<th>宽</th>
			<th>长</th>
			<th>数量</th>
			<th>单重</th>
			<th>重量</th>
			<th>备注</th>
			<th>上传时间</th>
			<th>上传文件</th>
			<th>订单号</th>
			<th>订单子项号</th>
			<th>受订单价</th>
			<th>批号</th>
			<th>物料号</th>
			<th>购单号</th>
			<th>目的地</th>
			<th>库存地</th>
			<th>结算</th>
			<th width="6px">证书号</th>
			<th width="6px">结算批号</th>
			<th>发货批次</th>
			<th>阶段</th>
			<?php if ($this->_tpl_vars['advancedSearch']): ?>
				<th>编辑</th>
				<th>删除<input type="checkbox" name="i_d" onchange="allChecksChange(this)"></th>
			<?php endif; ?>
		</tr>
		<?php unset($this->_sections['fache']);
$this->_sections['fache']['name'] = 'fache';
$this->_sections['fache']['loop'] = is_array($_loop=$this->_tpl_vars['fache_materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fache']['show'] = true;
$this->_sections['fache']['max'] = $this->_sections['fache']['loop'];
$this->_sections['fache']['step'] = 1;
$this->_sections['fache']['start'] = $this->_sections['fache']['step'] > 0 ? 0 : $this->_sections['fache']['loop']-1;
if ($this->_sections['fache']['show']) {
    $this->_sections['fache']['total'] = $this->_sections['fache']['loop'];
    if ($this->_sections['fache']['total'] == 0)
        $this->_sections['fache']['show'] = false;
} else
    $this->_sections['fache']['total'] = 0;
if ($this->_sections['fache']['show']):

            for ($this->_sections['fache']['index'] = $this->_sections['fache']['start'], $this->_sections['fache']['iteration'] = 1;
                 $this->_sections['fache']['iteration'] <= $this->_sections['fache']['total'];
                 $this->_sections['fache']['index'] += $this->_sections['fache']['step'], $this->_sections['fache']['iteration']++):
$this->_sections['fache']['rownum'] = $this->_sections['fache']['iteration'];
$this->_sections['fache']['index_prev'] = $this->_sections['fache']['index'] - $this->_sections['fache']['step'];
$this->_sections['fache']['index_next'] = $this->_sections['fache']['index'] + $this->_sections['fache']['step'];
$this->_sections['fache']['first']      = ($this->_sections['fache']['iteration'] == 1);
$this->_sections['fache']['last']       = ($this->_sections['fache']['iteration'] == $this->_sections['fache']['total']);
?>
		<tr class="blue">
			<td><?php echo $this->_tpl_vars['fache_facheDate'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_materialCode'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_facheNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_shipsClassification'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_material'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_thickness'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_width'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_length'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_count'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_unitWeight'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_weight'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_remark'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_uploadTime'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_filename'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_orderNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_orderSubitemNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_unitPrice'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_batchNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_materialNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_purchaseNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_destination'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_storePlace'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php if ($this->_tpl_vars['checkout']): ?>
				<a href="editCheckout.php?materialCode=<?php echo $this->_tpl_vars['fache_materialCode'][$this->_sections['fache']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['fache_orderNumber'][$this->_sections['fache']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['fache_orderSubitemNumber'][$this->_sections['fache']['index']]; ?>
&certificateNumber=<?php echo $this->_tpl_vars['fache_certificateNumber'][$this->_sections['fache']['index']]; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['fache_checkoutBatch'][$this->_sections['fache']['index']]; ?>
">已结算</a>
				<?php endif; ?>
			</td>
			<td><?php echo $this->_tpl_vars['fache_certificateNumber'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_checkoutBatch'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_consignmentBatch'][$this->_sections['fache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fache_phase'][$this->_sections['fache']['index']]; ?>
</td>
			<?php if ($this->_tpl_vars['advancedSearch']): ?>
				<td><a href="rowEdit.php?type=fache&id=<?php echo $this->_tpl_vars['fache_ids'][$this->_sections['fache']['index']]; ?>
">编辑</a></td>
				<td><input type="checkbox" name="fache_id<?php echo $this->_tpl_vars['fache_ids'][$this->_sections['fache']['index']]; ?>
" value="<?php echo $this->_tpl_vars['fache_ids'][$this->_sections['fache']['index']]; ?>
" /></td>
			<?php endif; ?>
		</tr>
		<?php endfor; endif; ?>
		
		<?php unset($this->_sections['fachuan']);
$this->_sections['fachuan']['name'] = 'fachuan';
$this->_sections['fachuan']['loop'] = is_array($_loop=$this->_tpl_vars['fachuan_materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fachuan']['show'] = true;
$this->_sections['fachuan']['max'] = $this->_sections['fachuan']['loop'];
$this->_sections['fachuan']['step'] = 1;
$this->_sections['fachuan']['start'] = $this->_sections['fachuan']['step'] > 0 ? 0 : $this->_sections['fachuan']['loop']-1;
if ($this->_sections['fachuan']['show']) {
    $this->_sections['fachuan']['total'] = $this->_sections['fachuan']['loop'];
    if ($this->_sections['fachuan']['total'] == 0)
        $this->_sections['fachuan']['show'] = false;
} else
    $this->_sections['fachuan']['total'] = 0;
if ($this->_sections['fachuan']['show']):

            for ($this->_sections['fachuan']['index'] = $this->_sections['fachuan']['start'], $this->_sections['fachuan']['iteration'] = 1;
                 $this->_sections['fachuan']['iteration'] <= $this->_sections['fachuan']['total'];
                 $this->_sections['fachuan']['index'] += $this->_sections['fachuan']['step'], $this->_sections['fachuan']['iteration']++):
$this->_sections['fachuan']['rownum'] = $this->_sections['fachuan']['iteration'];
$this->_sections['fachuan']['index_prev'] = $this->_sections['fachuan']['index'] - $this->_sections['fachuan']['step'];
$this->_sections['fachuan']['index_next'] = $this->_sections['fachuan']['index'] + $this->_sections['fachuan']['step'];
$this->_sections['fachuan']['first']      = ($this->_sections['fachuan']['iteration'] == 1);
$this->_sections['fachuan']['last']       = ($this->_sections['fachuan']['iteration'] == $this->_sections['fachuan']['total']);
?>
		<tr class="red">
			<td><?php echo $this->_tpl_vars['fachuan_fachuanDate'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_materialCode'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_fachuanNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_shipsClassification'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_material'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_thickness'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_width'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_length'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_count'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_unitWeight'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_weight'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_remark'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_uploadTime'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_filename'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_orderNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_orderSubitemNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_unitPrice'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_batchNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_materialNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_purchaseNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_destination'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_storePlace'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php if ($this->_tpl_vars['checkout']): ?>
				<a href="editCheckout.php?materialCode=<?php echo $this->_tpl_vars['fachuan_materialCode'][$this->_sections['fachuan']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['fachuan_orderNumber'][$this->_sections['fachuan']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['fachuan_orderSubitemNumber'][$this->_sections['fachuan']['index']]; ?>
&certificateNumber=<?php echo $this->_tpl_vars['fachuan_certificateNumber'][$this->_sections['fachuan']['index']]; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['fachuan_checkoutBatch'][$this->_sections['fachuan']['index']]; ?>
">已结算</a>
				<?php endif; ?>
			</td>
			<td><?php echo $this->_tpl_vars['fachuan_certificateNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_checkoutBatch'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_consignmentBatch'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_phase'][$this->_sections['fachuan']['index']]; ?>
</td>
			<?php if ($this->_tpl_vars['advancedSearch']): ?>
				<td><a href="rowEdit.php?type=fachuan&id=<?php echo $this->_tpl_vars['fachuan_ids'][$this->_sections['fachuan']['index']]; ?>
">编辑</a></td>
				<td><input type="checkbox" name="fachuan_id<?php echo $this->_tpl_vars['fachuan_ids'][$this->_sections['fachuan']['index']]; ?>
" value="<?php echo $this->_tpl_vars['fachuan_ids'][$this->_sections['fachuan']['index']]; ?>
" /></td>
			<?php endif; ?>
		</tr>
		<?php endfor; endif; ?>
		<?php endif; ?>

	</table>
	</form>
	</body>
</html>