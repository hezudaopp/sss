<?php /* Smarty version 2.6.26, created on 2010-10-25 10:07:06
         compiled from multipleTable.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'multipleTable.html', 139, false),)), $this); ?>
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
	clear: both;
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

.redBgd{
	background-color: red;
	color: white;
}

.blue{
	color:blue;
}

.sum th{
	background-color:#8A95A1;
	color:white;
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

	<script type="text/javascript" language="Javascript">
	function deleteConfirm(){
		return window.confirm('你确实要删除这一项吗？');
	}
	</script>
	</head>
	<body>

	<?php if ($this->_tpl_vars['inFache_materialCode'] || $this->_tpl_vars['outFache_materialCode'] || $this->_tpl_vars['fachuan_materialCode'] || $this->_tpl_vars['main_sequenceNumber'] || $this->_tpl_vars['directFache_materialCode']): ?>
		<?php if ($this->_tpl_vars['jiapian']): ?>
		<?php elseif ($this->_tpl_vars['chineseChars']): ?>
		<?php else: ?>
		<span class="exportBtn">
			<?php if ($this->_tpl_vars['haveOrderNumber']): ?>
				<a href="exportMultipleTable.php?keyname=<?php echo $this->_tpl_vars['keyname']; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber']; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>
" title="点击进行导出结果">导出表格</a>
			<?php else: ?>
				<a href="exportMultipleTable.php?keyname=<?php echo $this->_tpl_vars['keyname']; ?>
" title="点击进行导出结果">导出表格</a>
			<?php endif; ?>
		</span>
		<?php endif; ?>
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>


	<table cellpadding="0" cellspacing="0">
	<?php if ($this->_tpl_vars['main_sequenceNumber']): ?>
		<tr>
			<th style="width:4em" rowspan="<?php echo $this->_tpl_vars['planRowspan']; ?>
">总表</th>
			<th style="width:10em">批次</th>
			<th style="width:20em">材料代码</th>
			<th style="width:10em">生产厂家</th>
			<th style="width:5em">船级</th>
			<th style="width:5em">材质</th>
			<th style="width:6em">厚</th>
			<th style="width:6em">宽</th>
			<th style="width:6em">长</th>
			<th style="width:3em">数量</th>
			<th style="width:8em">单重</th>
			<th style="width:8em">重量</th>
			<th style="width:10em">备注</th>
			<th style="width:10em">上传时间</th>
			<th style="width:10em">上传文件</th>
			<th style="width:10em">订单号</th>
			<th style="width:5em">订单子项号</th>
			<th style="width:6em">受订单价</th>
			<th style="width:6em">批号</th>
			<th style="width:6em">物料号</th>
			<th style="width:6em">购单号</th>
			<th style="width:6em">目的地</th>
			<th style="width:6em">库存地</th>
			<th style="width:6em">证书号</th>
			<th style="width:6em">结算批号</th>
			<th style="width:6em">发货批次</th>
			<th style="width:5em">编辑</th>
			<th style="width:5em">删除</th>
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
			<td><?php echo $this->_tpl_vars['main_certificateNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_checkoutBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_consignmentBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><a href="rowEdit.php?type=main&id=<?php echo $this->_tpl_vars['main_id'][$this->_sections['main']['index']]; ?>
" title="点击进入编辑页面" target="_blank" >编辑</a></td>
			<td><form action="rowDelete.php" method="POST" onsubmit="return deleteConfirm()">
				<input type="hidden" name="type" value="main" />
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['main_id'][$this->_sections['main']['index']]; ?>
" />
				<input type="submit" value="删除" title="点击删除这一行" />
				</form>
			</td>
		</tr>
		<?php endfor; endif; ?>
		<tr class="sum">
			<th>小结：</th>
			<th colspan="3">总数量</th>
			<td><?php echo $this->_tpl_vars['planSumCount']; ?>
</td>
			<th colspan="3">总重量</th>
			<td colspan="2"><?php echo $this->_tpl_vars['planSumWeight']; ?>
</td>
		</tr>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['inFache_materialCode']): ?>
		<tr>
			<th rowspan="<?php echo $this->_tpl_vars['rukuRowspan']; ?>
">入库</th>
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
			<th>证书号</th>
			<th>结算批号</th>
			<th>发货批次</th>
			<th>阶段</th>
			<th>编辑</th>
			<th>删除</th>
		</tr>
		<?php unset($this->_sections['inFache']);
$this->_sections['inFache']['name'] = 'inFache';
$this->_sections['inFache']['loop'] = is_array($_loop=$this->_tpl_vars['inFache_materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['inFache']['show'] = true;
$this->_sections['inFache']['max'] = $this->_sections['inFache']['loop'];
$this->_sections['inFache']['step'] = 1;
$this->_sections['inFache']['start'] = $this->_sections['inFache']['step'] > 0 ? 0 : $this->_sections['inFache']['loop']-1;
if ($this->_sections['inFache']['show']) {
    $this->_sections['inFache']['total'] = $this->_sections['inFache']['loop'];
    if ($this->_sections['inFache']['total'] == 0)
        $this->_sections['inFache']['show'] = false;
} else
    $this->_sections['inFache']['total'] = 0;
if ($this->_sections['inFache']['show']):

            for ($this->_sections['inFache']['index'] = $this->_sections['inFache']['start'], $this->_sections['inFache']['iteration'] = 1;
                 $this->_sections['inFache']['iteration'] <= $this->_sections['inFache']['total'];
                 $this->_sections['inFache']['index'] += $this->_sections['inFache']['step'], $this->_sections['inFache']['iteration']++):
$this->_sections['inFache']['rownum'] = $this->_sections['inFache']['iteration'];
$this->_sections['inFache']['index_prev'] = $this->_sections['inFache']['index'] - $this->_sections['inFache']['step'];
$this->_sections['inFache']['index_next'] = $this->_sections['inFache']['index'] + $this->_sections['inFache']['step'];
$this->_sections['inFache']['first']      = ($this->_sections['inFache']['iteration'] == 1);
$this->_sections['inFache']['last']       = ($this->_sections['inFache']['iteration'] == $this->_sections['inFache']['total']);
?>
		<tr class="blue">
			<td><?php echo $this->_tpl_vars['inFache_facheDate'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_materialCode'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_facheNumber'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_shipsClassification'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_material'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_thickness'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_width'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_length'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_count'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_unitWeight'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_weight'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_remark'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_uploadTime'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_filename'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_orderNumber'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_orderSubitemNumber'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_unitPrice'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['inFache_batchNumber'][$this->_sections['inFache']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['inFache_materialNumber'][$this->_sections['inFache']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_purchaseNumber'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_destination'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_storePlace'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_certificateNumber'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_checkoutBatch'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_consignmentBatch'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['inFache_phase'][$this->_sections['inFache']['index']]; ?>
</td>
			<td><a href="rowEdit.php?type=fache&id=<?php echo $this->_tpl_vars['inFache_id'][$this->_sections['inFache']['index']]; ?>
" title="点击进入编辑页面" target="_blank">编辑</a></td>
			<td><form action="rowDelete.php" method="POST" onsubmit="return deleteConfirm()">
				<input type="hidden" name="type" value="fache" />
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['inFache_id'][$this->_sections['inFache']['index']]; ?>
" />
				<input type="submit" value="删除" title="点击删除这一行" />
				</form>
			</td>
		</tr>
		<?php endfor; endif; ?>

		<tr class="sum"
			<?php if ($this->_tpl_vars['inSumCount'] < $this->_tpl_vars['outSumCount']): ?>
				style="background-color:red; color=white" title="入库数小于出库数"
			<?php endif; ?> >
			<th>小结：</th>
			<th colspan="3">总数量</th>
			<td><?php echo $this->_tpl_vars['inSumCount']; ?>
</td>
			<th colspan="3">总重量</th>
			<td colspan="2"><?php echo $this->_tpl_vars['inSumWeight']; ?>
</td>
		</tr>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['outFache_materialCode'] || $this->_tpl_vars['fachuan_materialCode']): ?>
		<tr>
			<th rowspan="<?php echo $this->_tpl_vars['chukuRowspan']; ?>
">出库</th>
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
			<th>证书号</th>
			<th>结算批号</th>
			<th>发货批次</th>
			<th>阶段</th>
			<th>编辑</th>
			<th>删除</th>
		</tr>

		<?php unset($this->_sections['outFache']);
$this->_sections['outFache']['name'] = 'outFache';
$this->_sections['outFache']['loop'] = is_array($_loop=$this->_tpl_vars['outFache_materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['outFache']['show'] = true;
$this->_sections['outFache']['max'] = $this->_sections['outFache']['loop'];
$this->_sections['outFache']['step'] = 1;
$this->_sections['outFache']['start'] = $this->_sections['outFache']['step'] > 0 ? 0 : $this->_sections['outFache']['loop']-1;
if ($this->_sections['outFache']['show']) {
    $this->_sections['outFache']['total'] = $this->_sections['outFache']['loop'];
    if ($this->_sections['outFache']['total'] == 0)
        $this->_sections['outFache']['show'] = false;
} else
    $this->_sections['outFache']['total'] = 0;
if ($this->_sections['outFache']['show']):

            for ($this->_sections['outFache']['index'] = $this->_sections['outFache']['start'], $this->_sections['outFache']['iteration'] = 1;
                 $this->_sections['outFache']['iteration'] <= $this->_sections['outFache']['total'];
                 $this->_sections['outFache']['index'] += $this->_sections['outFache']['step'], $this->_sections['outFache']['iteration']++):
$this->_sections['outFache']['rownum'] = $this->_sections['outFache']['iteration'];
$this->_sections['outFache']['index_prev'] = $this->_sections['outFache']['index'] - $this->_sections['outFache']['step'];
$this->_sections['outFache']['index_next'] = $this->_sections['outFache']['index'] + $this->_sections['outFache']['step'];
$this->_sections['outFache']['first']      = ($this->_sections['outFache']['iteration'] == 1);
$this->_sections['outFache']['last']       = ($this->_sections['outFache']['iteration'] == $this->_sections['outFache']['total']);
?>
		<tr class="blue">
			<td><?php echo $this->_tpl_vars['outFache_facheDate'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_materialCode'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_facheNumber'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_shipsClassification'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_material'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_thickness'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_width'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_length'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_count'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_unitWeight'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_weight'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_remark'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_uploadTime'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_filename'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_orderNumber'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_orderSubitemNumber'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_unitPrice'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['outFache_batchNumber'][$this->_sections['outFache']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['outFache_materialNumber'][$this->_sections['outFache']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_purchaseNumber'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_destination'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_storePlace'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_certificateNumber'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_checkoutBatch'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_consignmentBatch'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['outFache_phase'][$this->_sections['outFache']['index']]; ?>
</td>
			<td><a href="rowEdit.php?type=fache&id=<?php echo $this->_tpl_vars['outFache_id'][$this->_sections['outFache']['index']]; ?>
" title="点击进入编辑页面" target="_blank">编辑</a></td>
			<td><form action="rowDelete.php" method="POST" onsubmit="return deleteConfirm()">
				<input type="hidden" name="type" value="fache" />
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['outFache_id'][$this->_sections['outFache']['index']]; ?>
" />
				<input type="submit" value="删除" title="点击删除这一行" />
				</form>
			</td>
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
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fachuan_batchNumber'][$this->_sections['fachuan']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fachuan_materialNumber'][$this->_sections['fachuan']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_purchaseNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_destination'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_storePlace'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_certificateNumber'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_checkoutBatch'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['fachuan_consignmentBatch'][$this->_sections['fachuan']['index']]; ?>
</td>
			<td>发船</td>
			<td><a href="rowEdit.php?type=fachuan&id=<?php echo $this->_tpl_vars['fachuan_id'][$this->_sections['fachuan']['index']]; ?>
" title="点击进入编辑页面" target="_blank">编辑</a></td>
			<td><form action="rowDelete.php" method="POST" onsubmit="return deleteConfirm()">
				<input type="hidden" name="type" value="fachuan" />
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['fachuan_id'][$this->_sections['fachuan']['index']]; ?>
" />
				<input type="submit" value="删除" title="点击删除这一行" />
				</form>
			</td>
			<td></td>
		</tr>
		<?php endfor; endif; ?>

		<tr class="sum" <?php if ($this->_tpl_vars['inSumCount'] < $this->_tpl_vars['outSumCount']): ?>
				style="background-color:red; color=white" title="入库数小于出库数"
			<?php endif; ?> >
			<th>小结：</th>
			<th colspan="3">总数量</th>
			<td><?php echo $this->_tpl_vars['outSumCount']; ?>
</td>
			<th colspan="3">总重量</th>
			<td colspan="2"><?php echo $this->_tpl_vars['outSumWeight']; ?>
</td>
		</tr>
	<?php endif; ?>


	<?php if ($this->_tpl_vars['directFache_materialCode']): ?>
		<tr>
			<th rowspan="<?php echo $this->_tpl_vars['directRowspan']; ?>
">销售</th>
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
			<th>证书号</th>
			<th>结算批号</th>
			<th>发货批次</th>
			<th>阶段</th>
			<th>编辑</th>
			<th>删除</th>
		</tr>

		<?php unset($this->_sections['directFache']);
$this->_sections['directFache']['name'] = 'directFache';
$this->_sections['directFache']['loop'] = is_array($_loop=$this->_tpl_vars['directFache_materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['directFache']['show'] = true;
$this->_sections['directFache']['max'] = $this->_sections['directFache']['loop'];
$this->_sections['directFache']['step'] = 1;
$this->_sections['directFache']['start'] = $this->_sections['directFache']['step'] > 0 ? 0 : $this->_sections['directFache']['loop']-1;
if ($this->_sections['directFache']['show']) {
    $this->_sections['directFache']['total'] = $this->_sections['directFache']['loop'];
    if ($this->_sections['directFache']['total'] == 0)
        $this->_sections['directFache']['show'] = false;
} else
    $this->_sections['directFache']['total'] = 0;
if ($this->_sections['directFache']['show']):

            for ($this->_sections['directFache']['index'] = $this->_sections['directFache']['start'], $this->_sections['directFache']['iteration'] = 1;
                 $this->_sections['directFache']['iteration'] <= $this->_sections['directFache']['total'];
                 $this->_sections['directFache']['index'] += $this->_sections['directFache']['step'], $this->_sections['directFache']['iteration']++):
$this->_sections['directFache']['rownum'] = $this->_sections['directFache']['iteration'];
$this->_sections['directFache']['index_prev'] = $this->_sections['directFache']['index'] - $this->_sections['directFache']['step'];
$this->_sections['directFache']['index_next'] = $this->_sections['directFache']['index'] + $this->_sections['directFache']['step'];
$this->_sections['directFache']['first']      = ($this->_sections['directFache']['iteration'] == 1);
$this->_sections['directFache']['last']       = ($this->_sections['directFache']['iteration'] == $this->_sections['directFache']['total']);
?>
		<tr class="blue">
			<td><?php echo $this->_tpl_vars['directFache_facheDate'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_materialCode'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_facheNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_shipsClassification'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_material'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_thickness'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_width'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_length'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_count'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_unitWeight'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_weight'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_remark'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_uploadTime'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_filename'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_orderNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_orderSubitemNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_unitPrice'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_batchNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_materialNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_purchaseNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_destination'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_storePlace'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_certificateNumber'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_checkoutBatch'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_consignmentBatch'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['directFache_phase'][$this->_sections['directFache']['index']]; ?>
</td>
			<td><a href="rowEdit.php?type=fache&id=<?php echo $this->_tpl_vars['directFache_id'][$this->_sections['directFache']['index']]; ?>
" title="点击进入编辑页面" target="_blank">编辑</a></td>
			<td><form action="rowDelete.php" method="POST" onsubmit="return deleteConfirm()">
				<input type="hidden" name="type" value="fache" />
				<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['directFache_id'][$this->_sections['directFache']['index']]; ?>
" />
				<input type="submit" value="删除" title="点击删除这一行" />
				</form>
			</td>
			<td></td>
		</tr>
		<?php endfor; endif; ?>

		<tr class="sum">
			<th>小结：</th>
			<th colspan="3">总数量</th>
			<td><?php echo $this->_tpl_vars['directSumCount']; ?>
</td>
			<th colspan="3">总重量</th>
			<td colspan="2"><?php echo $this->_tpl_vars['directSumWeight']; ?>
</td>
		</tr>
	<?php endif; ?>

	</table>
	</body>
</html>