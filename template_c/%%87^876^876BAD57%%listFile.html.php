<?php /* Smarty version 2.6.26, created on 2010-10-26 09:30:55
         compiled from listFile.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'listFile.html', 146, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>批量查询结果</title>
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

.red {
	color: white;
	background-color: red;
}

.green {
	color: white;
	background-color: green;
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

.lightGreen {
	color: black;
	background-color: #cbdced;
}

.yellow {
	color: black;
	background-color: yellow;
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
<?php if ($this->_tpl_vars['batchSearch']): ?>
<div id="sbar"
	style="width: 100%; padding: 0px 0px 4px 0px; border-bottom: 1px solid #c9d7f1;">
<span><a href="excelUpload.html">表格上传</a></span> | <span><a
	href="index.html">主页</a></span> | <span><a href="batchSearch.html">回到批量查询页面</a></span>
</div>
<?php endif; ?> <?php if ($this->_tpl_vars['materialCode']): ?>

<form action="listItemsDelete.php" method="POST"
	onsubmit="return deleteConfirm()"><span class="exportBtn"><input
	type="submit" value="删除" /></span>
	
	<span class="exportBtn"> <a href="exportFileListTable.php?id=<?php echo $this->_tpl_vars['id']; ?>
"
title="点击进行导出结果">导出表格</a> </span>
<span class="exportBtn">
</span>

<table cellpadding="0" cellspacing="0">
	<tr>
		<th style="width: 10em">材料代码</th>
		<th style="width: 8em">出库日期</th>
		<th style="width: 6em">车号|船号</th>
		<th style="width: 5em">船级</th>
		<th style="width: 5em">材质</th>
		<th style="width: 6em">厚</th>
		<th style="width: 6em">宽</th>
		<th style="width: 6em">长</th>
		<th style="width: 6em">数量</th>
		<th style="width: 6em">单重</th>
		<th style="width: 6em">重量</th>
		<th style="min-width: 12em">文件名</th>
		<th style="width: 10em">订单号</th>
		<th style="width: 5em">订单子项号</th>
		<th style="width: 6em">受订单价</th>
		<th style="width: 6em">批号</th>
		<th style="width: 6em">购单号</th>
		<th style="width: 6em">目的地</th>
		<th style="width: 6em">库存地</th>
		<th style="width: 6em">证书号</th>
		<th style="width: 6em">结算批号</th>
		<th style="width: 5em">备注</th>
		<th style="width: 2em">删除 <input type="checkbox" name="i_d" checked="checked"
			onchange="allChecksChange(this)" /></th>
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
			<td><?php echo $this->_tpl_vars['chukuDate'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['chukuNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['shipsClassification'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['material'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['thickness'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['width'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['length'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['count'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['unitWeight'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['weight'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['filename'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['unitPrice'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['batchNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['purchaseNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['destination'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['storePlace'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['certificateNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['checkoutBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['remarks'][$this->_sections['main']['index']]; ?>
</td>
			<td align="center"><input name="del<?php echo $this->_tpl_vars['materialCode'][$this->_sections['main']['index']]; ?>
_<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
_<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
" value="<?php echo $this->_tpl_vars['materialCode'][$this->_sections['main']['index']]; ?>
_<?php echo $this->_tpl_vars['orderNumber'][$this->_sections['main']['index']]; ?>
_<?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['main']['index']]; ?>
" checked="chekced" type="checkbox" /></td>
		</tr>
	<?php endfor; endif; ?>

</table>
</form>

<?php else: ?> 抱歉没有符合你搜索的条目 <?php endif; ?>

</body>
</html>