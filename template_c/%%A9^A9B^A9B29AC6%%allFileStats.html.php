<?php /* Smarty version 2.6.26, created on 2010-11-09 05:29:05
         compiled from allFileStats.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlencode', 'allFileStats.html', 113, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查询结果</title>
<style>
td {
	border-right: 1px solid #cbdced;
	border-bottom: 1px solid #cbdced;
	padding: 3px;
}

th {
	background-color: #cbdced;
	padding: 3px;
	border-right: 1px solid #fff;
	border-bottom: 1px solid #fff;
}

table {
	border-top: 1px solid #cbdced;
	border-left: 1px solid #cbdced;
}

body {
	font-size: 14px;
}

.red {
	color: white;
	background-color: red;
}

.blue {
	color: white;
	background-color: blue;
}

.clear {
	clear: both;
}

.exportBtn {
	margin-right: 30px;
	margin-top: 2px;
	margin-bottom: 2px;
	float: right;
	display: block;
	padding: 1px;
	clear: both;
}

span[class=exportBtn]:hover {
	background-color: #8A95A1;
	color: white;
}
</style>
<script type="text/javascript" language="Javascript">
		function checkForDelete(){
			return window.confirm('确定要删除这个文件中所有"材料代码+订单号+订单子项号"的所有信息（也包括加片和汉字）吗？');
		}
		function checkForUpdate(){
			return window.confirm('确定要更新这个文件中所有"批号，物料号，目的地，库存地，结算批号，证书号"的所有信息吗？');
		}
		</script>
</head>
<body>
<?php if ($this->_tpl_vars['noFile']): ?> 没有任何总表文件信息 <?php else: ?>
<span>第三列的“条目总数”是文件中“材料代码+订单号+订单子项号”确定的不同条目的个数。<br />
如果点击最后一列的删除的话，会删除对应的这个文件中包含的所有“材料代码+订单子项号+订单号”的信息，包括发车，发船文件中的相应的信息。<br />
</span>
<a href="?all=true">详细物流状况</a>
(会非常慢，显示每个文件的完成数量和未完成数量)
<span class="exportBtn"> <?php if ($this->_tpl_vars['all'] == true): ?> <a
	href="exportAllFileStatsTable.php?all=true" title="点击进行导出结果">导出表格</a>
<?php else: ?> <a href="exportAllFileStatsTable.php" title="点击进行导出结果">导出表格</a>
<?php endif; ?> </span>
<div class="clear"></div>
<table cellpadding="0" cellspacing="0">
	<tr>
		<th style="min-width:  20em">文件名</th>
		<th style="width: 10em">上传时间</th>
		<th style="width: 7em">条目总数</th>
		<th style="width: 7em">数量</th>
		<th style="width: 7em">重量</th>
		<?php if ($this->_tpl_vars['all'] == true): ?>
		<th style="width: 5em">已完成</th>
		<th style="width: 5em">未完成</th>
		<?php endif; ?>
		<th style="width: 5em">详细</th>
		<th style="width: 5em">删除</th>
		<th style="width: 5em">更新</th>
	</tr>
	<?php unset($this->_sections['file']);
$this->_sections['file']['name'] = 'file';
$this->_sections['file']['loop'] = is_array($_loop=$this->_tpl_vars['filename']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['file']['show'] = true;
$this->_sections['file']['max'] = $this->_sections['file']['loop'];
$this->_sections['file']['step'] = 1;
$this->_sections['file']['start'] = $this->_sections['file']['step'] > 0 ? 0 : $this->_sections['file']['loop']-1;
if ($this->_sections['file']['show']) {
    $this->_sections['file']['total'] = $this->_sections['file']['loop'];
    if ($this->_sections['file']['total'] == 0)
        $this->_sections['file']['show'] = false;
} else
    $this->_sections['file']['total'] = 0;
if ($this->_sections['file']['show']):

            for ($this->_sections['file']['index'] = $this->_sections['file']['start'], $this->_sections['file']['iteration'] = 1;
                 $this->_sections['file']['iteration'] <= $this->_sections['file']['total'];
                 $this->_sections['file']['index'] += $this->_sections['file']['step'], $this->_sections['file']['iteration']++):
$this->_sections['file']['rownum'] = $this->_sections['file']['iteration'];
$this->_sections['file']['index_prev'] = $this->_sections['file']['index'] - $this->_sections['file']['step'];
$this->_sections['file']['index_next'] = $this->_sections['file']['index'] + $this->_sections['file']['step'];
$this->_sections['file']['first']      = ($this->_sections['file']['iteration'] == 1);
$this->_sections['file']['last']       = ($this->_sections['file']['iteration'] == $this->_sections['file']['total']);
?>
	<tr>
		<td><a href="filenameEdit.php?filename=<?php echo $this->_tpl_vars['filename'][$this->_sections['file']['index']]; ?>
"> <?php echo $this->_tpl_vars['filename'][$this->_sections['file']['index']]; ?>
</a></td>
		<td><?php echo $this->_tpl_vars['uploadTime'][$this->_sections['file']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['count'][$this->_sections['file']['index']]; ?>
<!--(加片：<?php echo $this->_tpl_vars['jiapianCount'][$this->_sections['file']['index']]; ?>
)--></td>
		<td><?php echo $this->_tpl_vars['allCount'][$this->_sections['file']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['allWeight'][$this->_sections['file']['index']]; ?>
</td>
		<!-- $error = 0表示没有错误；
				$error = -1 表示有销售大于总数的错误；
				$error = 1 表示全部完成 -->
		<?php if ($this->_tpl_vars['all'] == true): ?> <?php if ($this->_tpl_vars['error'][$this->_sections['file']['index']] == 1): ?>
		<td colspan="2" class="blue">已全部完成</td>
		<?php elseif ($this->_tpl_vars['error'][$this->_sections['file']['index']] == 0): ?>
		<td><?php echo $this->_tpl_vars['finished'][$this->_sections['file']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['unfinished'][$this->_sections['file']['index']]; ?>
</td>
		<?php elseif ($this->_tpl_vars['error'][$this->_sections['file']['index']] == -1): ?>
		<td class="red" title="有些条目已销数大于总数，我们把它归为未完成的这类"><?php echo $this->_tpl_vars['finished'][$this->_sections['file']['index']]; ?>
</td>
		<td class="red" title="有些条目已销数大于总数，我们把它归为未完成的这类"><?php echo $this->_tpl_vars['unfinished'][$this->_sections['file']['index']]; ?>
</td>
		<?php endif; ?> <?php endif; ?>
		<td><a href="fileStatSearch.php?filename=<?php echo ((is_array($_tmp=$this->_tpl_vars['filename'][$this->_sections['file']['index']])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
"
		title="点击查看物流详细">详细</a></td>
		<td>
		<form action="fileDelete.php" method="POST"
			onsubmit="return checkForDelete()"><input type="hidden"
			name="filename" value="<?php echo $this->_tpl_vars['filename'][$this->_sections['file']['index']]; ?>
" /> <input
			type='submit' value="删除" /></form>
		</td>
		<td>
		<form action="fileUpdate.php" method="POST"
			onsubmit="return checkForUpdate()"><input type="hidden"
			name="filename" value="<?php echo $this->_tpl_vars['filename'][$this->_sections['file']['index']]; ?>
" /> <input
			type='submit' value="更新" /></form>
		</td>
	</tr>
	<?php endfor; endif; ?>
</table>
<?php endif; ?>

</body>
</html>