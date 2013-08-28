<?php /* Smarty version 2.6.26, created on 2010-10-26 09:22:32
         compiled from fileSummary.html */ ?>
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

.brown{
	background-color: brown;
	color:white;
}

.chocolate{
	background-color: chocolate;
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
	color: black;
	backgroud-color:white;
}

		</style>
		<script type="text/javascript">	
			function deleteConfirm(){
				return window.confirm('你确实要删除该文件的所有条目吗？');
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
	
	<?php if ($this->_tpl_vars['shipNumber']): ?>
		<span><?php echo $this->_tpl_vars['filename']; ?>
</span>
		<span class="exportBtn">
				<a href="exportFileSummaryTable.php?id=<?php echo $this->_tpl_vars['id']; ?>
" title="点击进行导出结果">导出表格</a>
		</span>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:14em">船号</th>
			<th style="width:5em">分段号</th>
			<th style="width:5em">发货批次</th>
			<th style="width:6em">备注</th>
		</tr>
		
		<?php unset($this->_sections['summary']);
$this->_sections['summary']['name'] = 'summary';
$this->_sections['summary']['loop'] = is_array($_loop=$this->_tpl_vars['shipNumber']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['summary']['show'] = true;
$this->_sections['summary']['max'] = $this->_sections['summary']['loop'];
$this->_sections['summary']['step'] = 1;
$this->_sections['summary']['start'] = $this->_sections['summary']['step'] > 0 ? 0 : $this->_sections['summary']['loop']-1;
if ($this->_sections['summary']['show']) {
    $this->_sections['summary']['total'] = $this->_sections['summary']['loop'];
    if ($this->_sections['summary']['total'] == 0)
        $this->_sections['summary']['show'] = false;
} else
    $this->_sections['summary']['total'] = 0;
if ($this->_sections['summary']['show']):

            for ($this->_sections['summary']['index'] = $this->_sections['summary']['start'], $this->_sections['summary']['iteration'] = 1;
                 $this->_sections['summary']['iteration'] <= $this->_sections['summary']['total'];
                 $this->_sections['summary']['index'] += $this->_sections['summary']['step'], $this->_sections['summary']['iteration']++):
$this->_sections['summary']['rownum'] = $this->_sections['summary']['iteration'];
$this->_sections['summary']['index_prev'] = $this->_sections['summary']['index'] - $this->_sections['summary']['step'];
$this->_sections['summary']['index_next'] = $this->_sections['summary']['index'] + $this->_sections['summary']['step'];
$this->_sections['summary']['first']      = ($this->_sections['summary']['iteration'] == 1);
$this->_sections['summary']['last']       = ($this->_sections['summary']['iteration'] == $this->_sections['summary']['total']);
?>
		<tr>
			<td><?php echo $this->_tpl_vars['shipNumber'][$this->_sections['summary']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['subNumber'][$this->_sections['summary']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['consignmentBatch'][$this->_sections['summary']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['remark'][$this->_sections['summary']['index']]; ?>
</td>
		</tr>
		<?php endfor; endif; ?>

	</table>
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	
	</body>
</html>