<?php /* Smarty version 2.6.26, created on 2010-09-26 10:28:44
         compiled from summaries.html */ ?>
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
<form action="summaryDelete.php" method="POST"
	onsubmit="return deleteConfirm()"><span class="exportBtn"><input
	type="submit" value=" 删除 " /></span> <span class="exportBtn"><a
	href="summaryAdd.html"> 添加新汇总 </a> </span>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<th style="width: 20%">文件名</th>
		<th style="width: 55%">备注</th>
		<th style="width: 15%">时间</th>
		<th style="width: 2em">删除 <input type="checkbox" name="i_d"
			onchange="allChecksChange(this)" /></th>
	</tr>
	<?php unset($this->_sections['summary']);
$this->_sections['summary']['name'] = 'summary';
$this->_sections['summary']['loop'] = is_array($_loop=$this->_tpl_vars['ids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<td><a href="fileSummary.php?id=<?php echo $this->_tpl_vars['ids'][$this->_sections['summary']['index']]; ?>
&filename=<?php echo $this->_tpl_vars['filenames'][$this->_sections['summary']['index']]; ?>
"><?php echo $this->_tpl_vars['filenames'][$this->_sections['summary']['index']]; ?>
</a></td>
		<td><?php echo $this->_tpl_vars['records'][$this->_sections['summary']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['times'][$this->_sections['summary']['index']]; ?>
</td>
		<td align="center"><input name="del<?php echo $this->_tpl_vars['ids'][$this->_sections['summary']['index']]; ?>
"
		checked="chekced" value="<?php echo $this->_tpl_vars['ids'][$this->_sections['summary']['index']]; ?>
" type="checkbox" /></td>
	</tr>
	<?php endfor; endif; ?>
</table>
</form>
</body>
</html>