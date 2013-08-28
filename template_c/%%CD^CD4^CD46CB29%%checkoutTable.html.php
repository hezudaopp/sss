<?php /* Smarty version 2.6.26, created on 2011-02-15 13:25:23
         compiled from checkoutTable.html */ ?>
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
	background-color: red;
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
	href="index.html">主页</a></span> | <span><a href="checkoutInput.php">回结算查询页面</a></span>
</div>
<?php if ($this->_tpl_vars['chukuDate']): ?>
<span class="exportBtn">
			<a href="exportCheckoutTable.php?fromDate=<?php echo $this->_tpl_vars['fromDate']; ?>
&toDate=<?php echo $this->_tpl_vars['toDate']; ?>
&type=<?php echo $this->_tpl_vars['type']; ?>
" title="点击进行导出结果">导出表格</a>
</span >
<?php else: ?> 抱歉没有符合你搜索的条目 <?php endif; ?>

<table cellpadding="0" cellspacing="0">
	<tr>
		<th style="width: 11em">日期</th>
		<th style="width: 5em">车号/船号</th>
		<th style="width: 10em">材料代码</th>
		<th style="width: 5em">船级</th>
		<th style="width: 5em">材质</th>
		<th style="width: 4em">厚</th>
		<th style="width: 4em">宽</th>
		<th style="width: 4em">长</th>
		<th style="width: 3em">数量</th>
		<th style="width: 4em">单重</th>
		<th style="width: 4em">重量</th>
		<th style="width: 6em">订单号</th>
		<th style="width: 6em">订单子项号</th>
		<th style="width: 6em">受订单价</th>
		<th style="width: 6em">购单号</th>
		<th style="width: 6em">批号</th>
		<th style="width: 6em">物料号</th>
		<th style="width: 6em">发货批次</th>
		<th style="width: 6em">库存地</th>
		<th style="width: 6em">目的地</th>
		<th style="width: 6em">结算批号</th>
		<th style="width: 6em">证书号</th>
		<th style="width: 6em">文件名</th>
		<th style="width: 12em">备注</th>
		
	</tr>

	<?php unset($this->_sections['time']);
$this->_sections['time']['name'] = 'time';
$this->_sections['time']['loop'] = is_array($_loop=$this->_tpl_vars['chukuNumber']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['time']['show'] = true;
$this->_sections['time']['max'] = $this->_sections['time']['loop'];
$this->_sections['time']['step'] = 1;
$this->_sections['time']['start'] = $this->_sections['time']['step'] > 0 ? 0 : $this->_sections['time']['loop']-1;
if ($this->_sections['time']['show']) {
    $this->_sections['time']['total'] = $this->_sections['time']['loop'];
    if ($this->_sections['time']['total'] == 0)
        $this->_sections['time']['show'] = false;
} else
    $this->_sections['time']['total'] = 0;
if ($this->_sections['time']['show']):

            for ($this->_sections['time']['index'] = $this->_sections['time']['start'], $this->_sections['time']['iteration'] = 1;
                 $this->_sections['time']['iteration'] <= $this->_sections['time']['total'];
                 $this->_sections['time']['index'] += $this->_sections['time']['step'], $this->_sections['time']['iteration']++):
$this->_sections['time']['rownum'] = $this->_sections['time']['iteration'];
$this->_sections['time']['index_prev'] = $this->_sections['time']['index'] - $this->_sections['time']['step'];
$this->_sections['time']['index_next'] = $this->_sections['time']['index'] + $this->_sections['time']['step'];
$this->_sections['time']['first']      = ($this->_sections['time']['iteration'] == 1);
$this->_sections['time']['last']       = ($this->_sections['time']['iteration'] == $this->_sections['time']['total']);
?>
	<tr class="green">
		<td><?php echo $this->_tpl_vars['chukuDate'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['chukuNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['materialCode'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['ships'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['material'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['thickness'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['width'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['length'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['count'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['unitWeight'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['weight'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['orderNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['orderSubitemNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['unitPrice'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['purchaseNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['batchNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['materialNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['consignmentBatch'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['storePlace'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['destination'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['checkoutBatch'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['certificateNumber'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['filename'][$this->_sections['time']['index']]; ?>
</td>
		<td><?php echo $this->_tpl_vars['remark'][$this->_sections['time']['index']]; ?>
</td>
			</tr>
	<?php endfor; endif; ?>

</table>
</body>
</html>