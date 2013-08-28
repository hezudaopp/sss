<?php /* Smarty version 2.6.26, created on 2010-11-15 10:06:56
         compiled from fileMonitor.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'fileMonitor.html', 150, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>批量查询结果</title>
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

.red{
	color:white;
	background-color: red;
}

.blue{
	color:white;
	background-color: blue;
}

.darkgreen{
	background-color: darkgreen;
	color:white;
}

.green{
	background-color: green;
	color:white;
}

.sienna{
	background-color:sienna;
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

.lightGreen{
	color:black;
	background-color: #cbdced;
}
.yellow{
	color: black;
	background-color: yellow;
}

a:link{
	color: red;
}

		</style>
		<script type="text/javascript">	
			function deleteConfirm(){
				return window.confirm('你确实要删除被监控的所有条目吗？');
			}
		
		</script>
	</head>
	<body>
	<?php if ($this->_tpl_vars['batchSearch']): ?>
	<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1;">
			<span><a href="excelUpload.html">表格上传</a></span>
			| <span><a href="index.html">主页</a></span>
			| <span><a href="batchSearch.html">回到批量查询页面</a></span>
	</div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['materialCode']): ?>
		<span><?php echo $this->_tpl_vars['filename']; ?>
</span>
		<span class="exportBtn">
				<a href="exportFileMonitorTable.php?id=<?php echo $this->_tpl_vars['id']; ?>
" title="点击进行导出结果">导出表格</a>
		</span>
		<span class="exportBtn">
				<form action="deleteItemsInFileMonitor.php" method="GET" onsubmit="return deleteConfirm()" >
					<input type="hidden" id="id" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
" />
					<input type="submit" title="点击删除所有监控的条目" value=" 删除全部 "/>
				</form>
		</span>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:14em">材料代码</th>
			<th style="width:5em">船级</th>
			<th style="width:5em">材质</th>
			<th style="width:6em">厚</th>
			<th style="width:6em">宽</th>
			<th style="width:6em">长</th>
			<th style="width:10em">订单号</th>
			<th style="width:5em">订单子项号</th>
			<th style="width:6em">受订单价</th>
			<th style="max-width:6em">批号</th>
			<th style="max-width:6em">物料号</th>
			<th style="width:6em">购单号</th>
			<th style="width:6em">目的地</th>
			<th style="width:6em">库存地</th>
			<th style="width:6em">证书号</th>
			<th style="width:6em">结算清单</th>
			<th style="width:6em">发货批次</th>
			<th style="width:6em">总量</th>
			<th style="width:6em">未入库</th>
			<th style="width:6em">库中</th>
			<th style="width:6em">销售</th>
		</tr>
		
		<?php unset($this->_sections['main']);
$this->_sections['main']['name'] = 'main';
$this->_sections['main']['loop'] = is_array($_loop=$this->_tpl_vars['materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<tr <?php if ($this->_tpl_vars['soldCount'][$this->_sections['main']['index']] == $this->_tpl_vars['sumCount'][$this->_sections['main']['index']]): ?> class="blue" title="完成"
			<?php elseif ($this->_tpl_vars['unRukuCount'][$this->_sections['main']['index']] == 0 && $this->_tpl_vars['soldCount'][$this->_sections['main']['index']] < $this->_tpl_vars['sumCount'][$this->_sections['main']['index']]): ?> class="green" title="全部入库未全部出库"
			<?php elseif ($this->_tpl_vars['unRukuCount'][$this->_sections['main']['index']] > 0 && $this->_tpl_vars['unRukuCount'][$this->_sections['main']['index']] < $this->_tpl_vars['sumCount'][$this->_sections['main']['index']]): ?> class="darkgreen" title="部分入库"
			<?php endif; ?>	
		>
			<td><a href="multipleTable.php?keyname=<?php echo $this->_tpl_vars['materialCode'][$this->_sections['main']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
">
			<?php echo $this->_tpl_vars['materialCode'][$this->_sections['main']['index']]; ?>
</a></td>
			<td><?php echo $this->_tpl_vars['shipsClassification'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['material'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['thickness'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['width'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['length'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['unitPrice'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['batchNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['materialNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['purchaseNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['destination'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['storePlace'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['certificateNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['checkoutBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['consignmentBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['sumCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['unRukuCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['kuzhongCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['soldCount'][$this->_sections['main']['index']]; ?>
</td>
		</tr>
		<?php endfor; endif; ?>

	</table>

	<hr>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:14em">材料代码</th>
			<th style="width:5em">船级</th>
			<th style="width:5em">材质</th>
			<th style="width:6em">厚</th>
			<th style="width:6em">宽</th>
			<th style="width:6em">长</th>
			<th style="width:10em">订单号</th>
			<th style="width:5em">订单子项号</th>
			<th style="width:6em">受订单价</th>
			<th style="max-width:6em">批号</th>
			<th style="max-width:6em">物料号</th>
			<th style="width:6em">购单号</th>
			<th style="width:6em">目的地</th>
			<th style="width:6em">库存地</th>
			<th style="width:6em">证书号</th>
			<th style="width:6em">结算清单</th>
			<th style="width:6em">发货批次</th>
		</tr>
		
		<?php unset($this->_sections['main']);
$this->_sections['main']['name'] = 'main';
$this->_sections['main']['loop'] = is_array($_loop=$this->_tpl_vars['notInMainMaterialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<tr>
			<td><?php echo $this->_tpl_vars['notInMainMaterialCode'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainshipsClassification'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainMaterial'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainThickness'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainWidth'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainLength'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainOrderNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainOrderSubitemNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainUnitPrice'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainBatchNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainMaterialNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainPurchaseNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainDestination'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainStorePlace'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainCertificateNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainCheckoutBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['notInMainConsignmentBatch'][$this->_sections['main']['index']]; ?>
</td>
		</tr>
		<?php endfor; endif; ?>

	</table>
	
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	
	</body>
</html>