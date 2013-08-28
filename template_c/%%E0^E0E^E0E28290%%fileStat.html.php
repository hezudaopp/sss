<?php /* Smarty version 2.6.26, created on 2010-10-25 10:09:36
         compiled from fileStat.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'fileStat.html', 151, false),)), $this); ?>
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

.red{
	background-color:red;
	color: white;
}

.blue{
	background-color: blue;
	color:white;
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

a:link{
	color: red;
}
		</style>
		<script type="text/javascript"  language="Javascript">
		function checkForDelete(mc, orderNo, orderSubNo){
			return window.confirm('确实要删除所有材料代码为“' + mc + '”,订单号为“' + orderNo + '”，订单子项号为“' + orderSubNo + '”的记录吗？');
		}
		</script>
	</head>
	<body>
	<?php if ($this->_tpl_vars['noFile']): ?>
	当前文件条目为空
	<?php else: ?>
	<?php echo $this->_tpl_vars['filename']; ?>

	<span class="exportBtn">
			<a href="exportFileStatTable.php?filename=<?php echo $this->_tpl_vars['filename']; ?>
" title="点击进行导出结果">导出表格</a>
	</span>
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:12em">材料代码</th>
			<th style="width:5em">船级</th>
			<th style="width:5em">材质</th>
			<th style="width:6em">厚</th>
			<th style="width:6em">宽</th>
			<th style="width:6em">长</th>
			<th style="width:6em">总量</th>
			<th style="width:6em">未入库</th>
			<th style="width:6em">库中</th>
			<th style="width:6em">销售</th>
			<th style="width:6em">订单号</th>
			<th style="width:6em">订单子项号</th>
			<th style="width:6em">受订单价</th>
			<th style="width:6em">批号</th>
			<th style="width:6em">物料号</th>
			<th style="width:6em">购单号</th>
			<th style="width:6em">目的地</th>
			<th style="width:6em">库存地</th>
			<th style="width:6em">结算</th>
			<th style="width:6em">证书号</th>
			<th style="width:6em">结算批号</th>
			<th style="width:6em">发货批次</th>
			<th style="width:6em">详细</th>
			<th style="width:6em">删除</th>
		</tr>
		<?php unset($this->_sections['item']);
$this->_sections['item']['name'] = 'item';
$this->_sections['item']['loop'] = is_array($_loop=$this->_tpl_vars['materialCode']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['item']['show'] = true;
$this->_sections['item']['max'] = $this->_sections['item']['loop'];
$this->_sections['item']['step'] = 1;
$this->_sections['item']['start'] = $this->_sections['item']['step'] > 0 ? 0 : $this->_sections['item']['loop']-1;
if ($this->_sections['item']['show']) {
    $this->_sections['item']['total'] = $this->_sections['item']['loop'];
    if ($this->_sections['item']['total'] == 0)
        $this->_sections['item']['show'] = false;
} else
    $this->_sections['item']['total'] = 0;
if ($this->_sections['item']['show']):

            for ($this->_sections['item']['index'] = $this->_sections['item']['start'], $this->_sections['item']['iteration'] = 1;
                 $this->_sections['item']['iteration'] <= $this->_sections['item']['total'];
                 $this->_sections['item']['index'] += $this->_sections['item']['step'], $this->_sections['item']['iteration']++):
$this->_sections['item']['rownum'] = $this->_sections['item']['iteration'];
$this->_sections['item']['index_prev'] = $this->_sections['item']['index'] - $this->_sections['item']['step'];
$this->_sections['item']['index_next'] = $this->_sections['item']['index'] + $this->_sections['item']['step'];
$this->_sections['item']['first']      = ($this->_sections['item']['iteration'] == 1);
$this->_sections['item']['last']       = ($this->_sections['item']['iteration'] == $this->_sections['item']['total']);
?>
		<tr <?php if ($this->_tpl_vars['sumCount'][$this->_sections['item']['index']] == $this->_tpl_vars['sold'][$this->_sections['item']['index']]): ?> class="blue" title="此项完成"
			<?php elseif ($this->_tpl_vars['unRuku'][$this->_sections['item']['index']] == 0 && $this->_tpl_vars['sold'][$this->_sections['item']['index']] < $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?> class="green" title="全部入库未全部出库"
			<?php elseif ($this->_tpl_vars['unRuku'][$this->_sections['item']['index']] > 0 && $this->_tpl_vars['unRuku'][$this->_sections['item']['index']] < $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?> class="darkgreen" title="部分入库"<?php endif; ?>
			>
			<td><?php echo $this->_tpl_vars['materialCode'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['shipsClassification'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['material'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['thickness'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['width'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['length'][$this->_sections['item']['index']]; ?>
</td>
			<td <?php if ($this->_tpl_vars['sumCount'][$this->_sections['item']['index']] < 0): ?>class="red" title='总表数量怎么可能小于0呢' <?php endif; ?> ><?php echo $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]; ?>
</td>
			<td <?php if ($this->_tpl_vars['unRuku'][$this->_sections['item']['index']] < 0): ?>class="red" title="未入库的数量不能小于0"<?php endif; ?> ><?php echo $this->_tpl_vars['unRuku'][$this->_sections['item']['index']]; ?>
</td>
			<td <?php if ($this->_tpl_vars['kuzhong'][$this->_sections['item']['index']] < 0): ?>
				class="red" title="库中存货量不能小于0"
				<?php elseif ($this->_tpl_vars['kuzhong'][$this->_sections['item']['index']] > $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?>
				class="red" title="库中存货量不能大于总量"
				<?php elseif ($this->_tpl_vars['kuzhong'][$this->_sections['item']['index']] + $this->_tpl_vars['sold'][$this->_sections['item']['index']] > $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?>
				class="red" title="库中和销售总数不能大于总量"
				<?php endif; ?> >
			<?php echo $this->_tpl_vars['kuzhong'][$this->_sections['item']['index']]; ?>

			</td>
			<td
				<?php if ($this->_tpl_vars['sold'][$this->_sections['item']['index']] < 0): ?>
					class="red"
				<?php elseif ($this->_tpl_vars['sold'][$this->_sections['item']['index']] > $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?>
					class="red" title="销售量不能大于总量"
				<?php elseif ($this->_tpl_vars['kuzhong'][$this->_sections['item']['index']] + $this->_tpl_vars['sold'][$this->_sections['item']['index']] > $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?>
					class="red" title="库中和销售总数不能大于总量"
				<?php endif; ?> >
			<?php echo $this->_tpl_vars['sold'][$this->_sections['item']['index']]; ?>

			</td>
			<td>
				<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>

			</td>
			<td>
				<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>

			</td>
			<td><?php echo $this->_tpl_vars['unitPrice'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['batchNumber'][$this->_sections['item']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['materialNumber'][$this->_sections['item']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['purchaseNumber'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['destination'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['storePlace'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php if ($this->_tpl_vars['certificateNumber'][$this->_sections['item']['index']]): ?>
				<a style="color: lime" href="editCheckout.php?materialCode=<?php echo $this->_tpl_vars['materialCode'][$this->_sections['item']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>
&certificateNumber=<?php echo $this->_tpl_vars['certificateNumber'][$this->_sections['item']['index']]; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['checkoutBatch'][$this->_sections['item']['index']]; ?>
">已结算</a>	
				<?php endif; ?>
			</td> 
			<td><?php echo $this->_tpl_vars['certificateNumber'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['checkoutBatch'][$this->_sections['item']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['consignmentBatch'][$this->_sections['item']['index']]; ?>
</td>
			<td>
				<a <?php if ($this->_tpl_vars['sumCount'][$this->_sections['item']['index']] == $this->_tpl_vars['sold'][$this->_sections['item']['index']]): ?>style="color: white;"<?php endif; ?> href="search.php?stype=materialCode&keyname=<?php echo $this->_tpl_vars['materialCode'][$this->_sections['item']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>
" target="_blank">详细</a>
			</td>
			<td>
				<form action="deleteByMaterialCodeAndOrders.php" method="POST" onsubmit="return checkForDelete('<?php echo $this->_tpl_vars['materialCode'][$this->_sections['item']['index']]; ?>
', '<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>
', '<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>
')">
				<input type="hidden" value="<?php echo $this->_tpl_vars['materialCode'][$this->_sections['item']['index']]; ?>
" name="materialCode" />
				<input type="hidden" value="<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>
" name="orderNumber" />
				<input type="hidden" value="<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>
" name="orderSubitemNumber" />
				<input type='submit' value="删除" />
				</form>
			</td>
			</tr>
		<?php endfor; endif; ?>
		</table>
	<?php endif; ?>
	</body>
</html>