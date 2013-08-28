<?php /* Smarty version 2.6.14, created on 2009-08-14 02:50:10
         compiled from batchSearch.html */ ?>
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
.green{
	color:green;
}

.red{
	color:white;
	background-color: red;
}

.blue{
	color:white;
	background-color: blue;
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
	</head>
	<body>
	<?php if ($this->_tpl_vars['batchSearch']): ?>
	<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1;">
			<span><a href="excelUpload.html">表格上传</a></span>
			| <span><a href="index.html">主页</a></span>
			| <span><a href="batchSearch.html">回到批量查询页面</a></span>
	</div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['fache_materialCode'] || $this->_tpl_vars['fachuan_materialCode'] || $this->_tpl_vars['main_sequenceNumber']): ?>
		<span class="exportBtn">
				<a href="batchSearchExport.php">全部删除</a>
		</span>
		<span class="exportBtn">
				<a href="exportSimpleTable.php?stype=<?php echo $this->_tpl_vars['stype']; ?>
&keyname=<?php echo $this->_tpl_vars['keyname']; ?>
" title="点击进行导出结果">导出表格</a>
		</span>
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	
	<table cellpadding="0" cellspacing="0">
		<?php if ($this->_tpl_vars['main_sequenceNumber']): ?>
		<tr>
			<th style="width:14em">材料代码</th>
			<th style="width:5em">船级</th>
			<th style="width:5em">材质</th>
			<th style="width:6em">厚</th>
			<th style="width:6em">宽</th>
			<th style="width:6em">长</th>
			<th style="width:6em">类别</th>
			<th style="width:10em">厂家/车船号</th>
			<th style="width:10em">批次</th>
			<th style="width:3em">数量</th>
			<th style="width:8em">单重</th>
			<th style="width:8em">重量</th>
			<th style="width:8em">备注</th>
			<th style="width:8em">上传时间</th>
			<th style="width:8em">上传文件</th>
			<th style="width:10em">订单号</th>
			<th style="width:5em">订单子项号</th>
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
		<tr <?php if ($this->_tpl_vars['colors'][$this->_sections['main']['index']] == 'blue'): ?>class="blue" title="全部完成"<?php elseif ($this->_tpl_vars['colors'][$this->_sections['main']['index']] == 'red'): ?>class="red" title="出现错误"<?php endif; ?> >
			<td><?php echo $this->_tpl_vars['main_materialCode'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_manufactory'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_material'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_thickness'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_width'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_length'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['type'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_shipsClassification'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_sequenceNumber'][$this->_sections['main']['index']]; ?>
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
			<th>阶段</th>
			<th></th>
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
			<td><?php echo $this->_tpl_vars['fache_phase'][$this->_sections['fache']['index']]; ?>
</td>
			<td></td>
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
			<td></td>
			<td></td>
		</tr>
		<?php endfor; endif; ?>
		<?php endif; ?>

	</table>
	
	</body>
</html>