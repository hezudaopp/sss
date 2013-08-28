<?php /* Smarty version 2.6.14, created on 2009-07-29 16:05:20
         compiled from fileConsignment.html */ ?>
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

.green{
	color:white;
	background-color: green;
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

		</style>
		<script type="text/javascript">	
			function deleteConfirm(){
				return window.confirm('你确实要删除这些发货批次条目吗？');
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
		<span class="exportBtn">
				<a href="exportFileConsignmentTable.php?id=<?php echo $this->_tpl_vars['id']; ?>
" title="点击进行导出结果">导出表格</a>
		</span>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:8em">材料代码</th>
			<th style="width:5em">船号</th>
			<th style="width:6em">分段号</th>
			<th style="width:6em">订单号</th>
			<th style="width:8em">订单子项号</th>
			<th style="width:7em">购单号</th>
			<th style="width:7em">物料号</th>
			<th style="width:8em">发货批次</th>
			<th style="width:8em">备注</th>
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
		<tr>
			<td><a href="multipleTable.php?keyname=<?php echo $this->_tpl_vars['materialCode'][$this->_sections['main']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
">
			<?php echo $this->_tpl_vars['materialCode'][$this->_sections['main']['index']]; ?>
</a></td>
			<td><?php echo $this->_tpl_vars['shipNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['subsectionNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['purchaseNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['materialNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['consignmentBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['remark'][$this->_sections['main']['index']]; ?>
</td>
		</tr>
		<?php endfor; endif; ?>

	</table>
	
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	
	</body>
</html>