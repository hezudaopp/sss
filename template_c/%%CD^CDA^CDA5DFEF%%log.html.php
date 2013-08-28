<?php /* Smarty version 2.6.26, created on 2010-09-26 10:21:45
         compiled from log.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>修改记录</title>
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

.export{
	margin-right:30px;
	margin-top:2px;
	margin-bottom:2px;
	float:left;
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
	<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1;">
			<span><a href="excelUpload.html">表格上传</a></span>
			| <span><a href="index.html">主页</a></span></span>
	</div>
	
	<?php if ($this->_tpl_vars['errMsg']): ?>
			<p style="color:red; line-height:1.5em;"><?php echo $this->_tpl_vars['errMsg']; ?>
</p>
	<?php endif; ?>
	<form action="exportLog.php" method="post">
	<div class="export">
				起始日期:<input type="text" name="fromDate"/>
	</div>
	<div class="export">
				结束日期:<input type="text" name="toDate"/>
	</div>
	<div class="export">
				<input type="submit" value=" 导出记录 "/>
	</div>
	</form>
	<form action="logDelete.php" method="POST" onsubmit="return deleteConfirm()" >
	<span class="exportBtn">
				<input type="submit" value=" 删除选中 "/>
	</span>
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<th style="width:20%">时间</th>
			<th style="width:5%">操作</th>
			<th style="width:50%">记录</th>
			<th style="width:10%">删除<input type="checkbox" name="i_d" onchange="allChecksChange(this)">
		</tr>
		
		<?php unset($this->_sections['log']);
$this->_sections['log']['name'] = 'log';
$this->_sections['log']['loop'] = is_array($_loop=$this->_tpl_vars['ids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['log']['show'] = true;
$this->_sections['log']['max'] = $this->_sections['log']['loop'];
$this->_sections['log']['step'] = 1;
$this->_sections['log']['start'] = $this->_sections['log']['step'] > 0 ? 0 : $this->_sections['log']['loop']-1;
if ($this->_sections['log']['show']) {
    $this->_sections['log']['total'] = $this->_sections['log']['loop'];
    if ($this->_sections['log']['total'] == 0)
        $this->_sections['log']['show'] = false;
} else
    $this->_sections['log']['total'] = 0;
if ($this->_sections['log']['show']):

            for ($this->_sections['log']['index'] = $this->_sections['log']['start'], $this->_sections['log']['iteration'] = 1;
                 $this->_sections['log']['iteration'] <= $this->_sections['log']['total'];
                 $this->_sections['log']['index'] += $this->_sections['log']['step'], $this->_sections['log']['iteration']++):
$this->_sections['log']['rownum'] = $this->_sections['log']['iteration'];
$this->_sections['log']['index_prev'] = $this->_sections['log']['index'] - $this->_sections['log']['step'];
$this->_sections['log']['index_next'] = $this->_sections['log']['index'] + $this->_sections['log']['step'];
$this->_sections['log']['first']      = ($this->_sections['log']['iteration'] == 1);
$this->_sections['log']['last']       = ($this->_sections['log']['iteration'] == $this->_sections['log']['total']);
?>
		<tr <?php if ($this->_tpl_vars['types'][$this->_sections['log']['index']] == 'edit'): ?>class="green"<?php elseif ($this->_tpl_vars['types'][$this->_sections['log']['index']] == 'delete'): ?> class="red" <?php endif; ?> >
			<td><?php echo $this->_tpl_vars['times'][$this->_sections['log']['index']]; ?>
</td>
			<td><?php if ($this->_tpl_vars['types'][$this->_sections['log']['index']] == 'edit'): ?>修改<?php elseif ($this->_tpl_vars['types'][$this->_sections['log']['index']] == 'delete'): ?> 删除 <?php else: ?> 添加 <?php endif; ?></td>
			<td><?php echo $this->_tpl_vars['contents'][$this->_sections['log']['index']]; ?>
<?php if ($this->_tpl_vars['types'][$this->_sections['log']['index']] == 'edit'): ?><br><?php echo $this->_tpl_vars['contents2'][$this->_sections['log']['index']]; ?>
<?php endif; ?></td>
			<td align="center"><input type="checkbox" name="id<?php echo $this->_tpl_vars['ids'][$this->_sections['log']['index']]; ?>
" value="<?php echo $this->_tpl_vars['ids'][$this->_sections['log']['index']]; ?>
" /></td>
		</tr>
		<?php endfor; endif; ?>

	</table>
	
	</form>
	</body>
</html>