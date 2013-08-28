<?php /* Smarty version 2.6.26, created on 2010-07-27 10:27:30
         compiled from listAllFiles.html */ ?>
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

.green {
	color: green;
}

.red {
	color: red;
}

.blue {
	color: blue;
}

.sum th {
	background-color: #8A95A1;
	color: white;
}
</style>

<script type="text/javascript" language="Javascript">
	function deleteConfirm(){
		return window.confirm('你确实要删除这个文件中所有信息吗？');
	}
	</script>
</head>
<body>
<table cellpadding="0" cellspacing="0">
	<tr>
		<th style="width: 15em">上传文件</th>
		<th style="width: 15em">上传时间</th>
		<th style="width: 10em">条目总数</th>
		<th style="width: 5em">详细</th>
		<th style="width: 5em">删除</th>
	</tr>

	<?php unset($this->_sections['main']);
$this->_sections['main']['name'] = 'main';
$this->_sections['main']['loop'] = is_array($_loop=$this->_tpl_vars['main_filename']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<tr class="green">
		<td><?php echo $this->_tpl_vars['main_filename'][$this->_sections['main']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['main_uploadTime'][$this->_sections['main']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['main_count'][$this->_sections['main']['index']]; ?>
</td>
		<td><a href="search.php?stype=filename&keyname=<?php echo $this->_tpl_vars['main_filename'][$this->_sections['main']['index']]; ?>
"
		title="点击查看文件中的条目" target="_blank" >详细</a></td>
		<td>
		<form action="onlyFileDelete.php" method="POST"
			onsubmit="return deleteConfirm()"><input type="hidden"
			name="type" value="main" /> <input type="hidden" name="filename"
			value="<?php echo $this->_tpl_vars['main_filename'][$this->_sections['main']['index']]; ?>
" /> <input type="hidden"
			name="uploadTime" value="<?php echo $this->_tpl_vars['main_uploadTime'][$this->_sections['main']['index']]; ?>
" /> <input
			type="submit" value="删除" title="点击删除这个文件" /></form>
		</td>
	</tr>
	<?php endfor; endif; ?> <?php unset($this->_sections['fache']);
$this->_sections['fache']['name'] = 'fache';
$this->_sections['fache']['loop'] = is_array($_loop=$this->_tpl_vars['fache_filename']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<td><?php echo $this->_tpl_vars['fache_filename'][$this->_sections['fache']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fache_uploadTime'][$this->_sections['fache']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fache_count'][$this->_sections['fache']['index']]; ?>
</td>
		<td><a href="search.php?stype=filename&keyname=<?php echo $this->_tpl_vars['fache_filename'][$this->_sections['fache']['index']]; ?>
"
		title="点击查看文件中的条目" target="_blank" >详细</a></td>
		<td>
		<form action="onlyFileDelete.php" method="POST"
			onsubmit="return deleteConfirm()"><input type="hidden"
			name="type" value="fache" /> <input type="hidden" name="filename"
			value="<?php echo $this->_tpl_vars['fache_filename'][$this->_sections['fache']['index']]; ?>
" /> <input type="hidden"
			name="uploadTime" value="<?php echo $this->_tpl_vars['fache_uploadTime'][$this->_sections['fache']['index']]; ?>
" /> <input
			type="submit" value="删除" title="点击删除这个文件" /></form>
		</td>
	</tr>
	<?php endfor; endif; ?> <?php unset($this->_sections['fachuan']);
$this->_sections['fachuan']['name'] = 'fachuan';
$this->_sections['fachuan']['loop'] = is_array($_loop=$this->_tpl_vars['fachuan_filename']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<td><?php echo $this->_tpl_vars['fachuan_filename'][$this->_sections['fachuan']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fachuan_uploadTime'][$this->_sections['fachuan']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fachuan_count'][$this->_sections['fachuan']['index']]; ?>
</td>
		<td><a href="search.php?stype=filename&keyname=<?php echo $this->_tpl_vars['fachuan_filename'][$this->_sections['fachuan']['index']]; ?>
"
		title="点击查看文件中的条目" target="_blank" >详细</a></td>
		<td>
		<form action="onlyFileDelete.php" method="POST"
			onsubmit="return deleteConfirm()"><input type="hidden"
			name="type" value="fachuan" /> <input type="hidden" name="filename"
			value="<?php echo $this->_tpl_vars['fachuan_filename'][$this->_sections['fachuan']['index']]; ?>
" /> <input
			type="hidden" name="uploadTime" value="<?php echo $this->_tpl_vars['fachuan_uploadTime'][$this->_sections['fachuan']['index']]; ?>
"
		/> <input type="submit" value="删除" title="点击删除这个文件" /></form>
		</td>
	</tr>
	<?php endfor; endif; ?>

</table>
</body>
</html>