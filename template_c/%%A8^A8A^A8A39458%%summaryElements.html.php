<?php /* Smarty version 2.6.26, created on 2010-07-07 09:59:06
         compiled from summaryElements.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>汇总上传文件列表</title>
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
	clear: both;
	border-top: 1px solid #cbdced;
	border-left: 1px solid #cbdced;
}

body {
	font-size: 14px;
}

.green {
	color: green;
}

.red {
	color: red;
}

.blue {
	color: blue;
}

.exportBtn {
	margin-right: 30px;
	margin-top: 2px;
	margin-bottom: 2px;
	float: right;
	display: block;
	padding: 1px;
}

span[class=exportBtn]:hover {
	background-color: #8A95A1;
	color: white;
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
<div id="sbar"
	style="width: 100%; padding: 0px 0px 4px 0px; border-bottom: 1px solid #c9d7f1;">
<span><a href="excelUpload.html">表格上传</a></span> | <span><a
	href="index.html">主页</a></span></div>
<form action="summaryElementDelete.php" method="POST"
	onsubmit="return deleteConfirm()"><span class="exportBtn"><input
	type="submit" value=" 删除 " /></span> <span class="exportBtn"><a
	href="summaryElementAdd.html"> 添加新汇总因素 </a> </span>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<th style="width: 20%">文件名</th>
		<th style="width: 55%">备注</th>
		<th style="width: 15%">时间</th>
		<th style="width: 2em">删除 <input type="checkbox" name="i_d"
			onchange="allChecksChange(this)" /></th>
	</tr>
	<?php unset($this->_sections['summaryElement']);
$this->_sections['summaryElement']['name'] = 'summaryElement';
$this->_sections['summaryElement']['loop'] = is_array($_loop=$this->_tpl_vars['ids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<td><a href="fileSummaryElement.php?id=<?php echo $this->_tpl_vars['ids'][$this->_sections['summaryElement']['index']]; ?>
&filename=<?php echo $this->_tpl_vars['filenames'][$this->_sections['summaryElement']['index']]; ?>
"><?php echo $this->_tpl_vars['filenames'][$this->_sections['summaryElement']['index']]; ?>
</a></td>
		<td><?php echo $this->_tpl_vars['records'][$this->_sections['summaryElement']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['times'][$this->_sections['summaryElement']['index']]; ?>
</td>
		<td align="center"><input name="del<?php echo $this->_tpl_vars['ids'][$this->_sections['summaryElement']['index']]; ?>
"
		checked="chekced" value="<?php echo $this->_tpl_vars['ids'][$this->_sections['summaryElement']['index']]; ?>
" type="checkbox" /></td>
	</tr>
	<?php endfor; endif; ?>
</table>
</form>
</body>
</html>