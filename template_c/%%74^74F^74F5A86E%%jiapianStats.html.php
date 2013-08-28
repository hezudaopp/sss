<?php /* Smarty version 2.6.14, created on 2008-10-18 15:03:15
         compiled from jiapianStats.html */ ?>
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
	background-color:red;
	color: white;
}

.blue{
	background-color: blue;
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
		<script type="text/javascript"  language="Javascript">
		function checkForDelete(mc){
			return window.confirm('确实要删除所有订单号，订单子项号为这样的加片记录吗？');
		}
		</script>
	</head>
	<body>
	<?php if ($this->_tpl_vars['noFile']): ?>
	当前文件条目为空
	<?php else: ?>
			<span class="exportBtn">
					<a href="exportJiapianStatTable.php?showFilename=true" title="导出带文件名的结果">导出带文件名的表格</a>
			</span>
			<span class="exportBtn">
					<a href="exportJiapianStatTable.php" title="点击进行导出结果">导出表格</a>
			</span>
			
	<div class="clear"></div>
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:20em">材料代码</th>
			<th style="width:5em">船级</th>
			<th style="width:5em">材质</th>
			<th style="width:6em">厚</th>
			<th style="width:6em">宽</th>
			<th style="width:6em">长</th>
			<th style="width:6em">总量</th>
			<th style="width:6em">未入库</th>
			<th style="width:6em">库中</th>
			<th style="width:6em">已销售</th>
			<th style="width:6em">订单号</th>
			<th style="width:6em">订单子项号</th>
			<th style="width:6em">详细</th>
			<th style="width:6em">删除</th>
		</tr>
		<?php unset($this->_sections['item']);
$this->_sections['item']['name'] = 'item';
$this->_sections['item']['loop'] = is_array($_loop=$this->_tpl_vars['material']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<tr <?php if ($this->_tpl_vars['sumCount'][$this->_sections['item']['index']] == $this->_tpl_vars['sold'][$this->_sections['item']['index']]): ?> class="blue" title="此项完成" <?php endif; ?>>
			<td>加片</td>
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
			<td <?php if ($this->_tpl_vars['sumCount'][$this->_sections['item']['index']] < 0): ?>class="red"<?php endif; ?> ><?php echo $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]; ?>
</td>
			<td <?php if ($this->_tpl_vars['unRuku'][$this->_sections['item']['index']] < 0): ?>class="red" title="未入库的数量不能小于0"<?php endif; ?> ><?php echo $this->_tpl_vars['unRuku'][$this->_sections['item']['index']]; ?>
</td>
			<td <?php if ($this->_tpl_vars['kuzhong'][$this->_sections['item']['index']] < 0): ?>
				class="red" title="库中存货量不能小于0"
				<?php elseif ($this->_tpl_vars['kuzhong'][$this->_sections['item']['index']] > $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?>
				class="red" title="库中存货量不能大于总量"
				<?php endif; ?> >
			<?php echo $this->_tpl_vars['kuzhong'][$this->_sections['item']['index']]; ?>

			</td>
			<td
				<?php if ($this->_tpl_vars['sold'][$this->_sections['item']['index']] < 0): ?>
					class="red"
				<?php elseif ($this->_tpl_vars['sold'][$this->_sections['item']['index']] > $this->_tpl_vars['sumCount'][$this->_sections['item']['index']]): ?>
					class="red" title="销售量不能大于总量"
				<?php endif; ?> >
			<?php echo $this->_tpl_vars['sold'][$this->_sections['item']['index']]; ?>

			</td>
			<td>
				<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>

			</td>
			<td>
				<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>

			</td>
			<td><a href="search.php?stype=jiapian&orderNumber=<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>
" target="_blank">详细</a></td>
			<td>
				<form action="deleteJiapian.php" method="POST" onsubmit="return checkForDelete('<?php echo $this->_tpl_vars['materialCode'][$this->_sections['item']['index']]; ?>
')">
				<input type="hidden" name="orderNumber" value="<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['item']['index']]; ?>
" />
				<input type="hidden" name="orderSubitemNumber" value="<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['item']['index']]; ?>
" />
				<input type='submit' value="删除" />
				</form>
			</td>
			</tr>
		<?php endfor; endif; ?>
		</table>
	<?php endif; ?>
	</body>
</html>