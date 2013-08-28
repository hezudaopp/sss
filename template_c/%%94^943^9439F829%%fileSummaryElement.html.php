<?php /* Smarty version 2.6.26, created on 2010-07-07 09:59:08
         compiled from fileSummaryElement.html */ ?>
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
	
	<?php if ($this->_tpl_vars['manufactory']): ?>
		<span><?php echo $this->_tpl_vars['filename']; ?>
</span>
		<span class="exportBtn">
				<a href="exportFileSummaryElementTable.php?id=<?php echo $this->_tpl_vars['id']; ?>
" title="点击进行导出结果">导出表格</a>
		</span>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:14em">厂家</th>
			<th style="width:5em">库存地</th>
			<th style="width:5em">目的地</th>
			<th style="width:6em">备注</th>
		</tr>
		
		<?php unset($this->_sections['summaryElement']);
$this->_sections['summaryElement']['name'] = 'summaryElement';
$this->_sections['summaryElement']['loop'] = is_array($_loop=$this->_tpl_vars['manufactory']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['summaryElement']['show'] = true;
$this->_sections['summaryElement']['max'] = $this->_sections['summaryElement']['loop'];
$this->_sections['summaryElement']['step'] = 1;
$this->_sections['summaryElement']['start'] = $this->_sections['summaryElement']['step'] > 0 ? 0 : $this->_sections['summaryElement']['loop']-1;
if ($this->_sections['summaryElement']['show']) {
    $this->_sections['summaryElement']['total'] = $this->_sections['summaryElement']['loop'];
    if ($this->_sections['summaryElement']['total'] == 0)
        $this->_sections['summaryElement']['show'] = false;
} else
    $this->_sections['summaryElement']['total'] = 0;
if ($this->_sections['summaryElement']['show']):

            for ($this->_sections['summaryElement']['index'] = $this->_sections['summaryElement']['start'], $this->_sections['summaryElement']['iteration'] = 1;
                 $this->_sections['summaryElement']['iteration'] <= $this->_sections['summaryElement']['total'];
                 $this->_sections['summaryElement']['index'] += $this->_sections['summaryElement']['step'], $this->_sections['summaryElement']['iteration']++):
$this->_sections['summaryElement']['rownum'] = $this->_sections['summaryElement']['iteration'];
$this->_sections['summaryElement']['index_prev'] = $this->_sections['summaryElement']['index'] - $this->_sections['summaryElement']['step'];
$this->_sections['summaryElement']['index_next'] = $this->_sections['summaryElement']['index'] + $this->_sections['summaryElement']['step'];
$this->_sections['summaryElement']['first']      = ($this->_sections['summaryElement']['iteration'] == 1);
$this->_sections['summaryElement']['last']       = ($this->_sections['summaryElement']['iteration'] == $this->_sections['summaryElement']['total']);
?>
		<tr>
			<td><?php echo $this->_tpl_vars['manufactory'][$this->_sections['summaryElement']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['storePlace'][$this->_sections['summaryElement']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['destination'][$this->_sections['summaryElement']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['remark'][$this->_sections['summaryElement']['index']]; ?>
</td>
		</tr>
		<?php endfor; endif; ?>

	</table>
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	
	</body>
</html>