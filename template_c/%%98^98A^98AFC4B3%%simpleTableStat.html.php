<?php /* Smarty version 2.6.26, created on 2010-11-19 09:30:47
         compiled from simpleTableStat.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'simpleTableStat.html', 181, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>查询结果</title>
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
	background-color: blue;
	color:white;
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
	<?php if ($this->_tpl_vars['advancedSearch']): ?>
	<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1;">
			<span><a href="excelUpload.html">表格上传</a></span>
			| <span><a href="index.html">主页</a></span>
			| <span><a href="advancedSearch.html">回到高级搜索</a></span>
	</div>
	<?php endif; ?>
	
	<form action="batchDelete.php" method="POST" onsubmit="return deleteConfirm()" >
	
	<?php if ($this->_tpl_vars['fache_materialCode'] || $this->_tpl_vars['fachuan_materialCode'] || $this->_tpl_vars['main_sequenceNumber']): ?>
			<span class="exportBtn">
				<input type="submit" value=" 删除选中 "/>
			</span>
			<span class="exportBtn">
				<a href="exportAdvancedTableStat.php?materialCode=<?php echo $this->_tpl_vars['materialCode']; ?>
&thicknessFrom=<?php echo $this->_tpl_vars['thicknessFrom']; ?>
&thicknessTo=<?php echo $this->_tpl_vars['thicknessTo']; ?>
&widthFrom=<?php echo $this->_tpl_vars['widthFrom']; ?>
&widthTo=<?php echo $this->_tpl_vars['widthTo']; ?>
&lengthFrom=<?php echo $this->_tpl_vars['lengthFrom']; ?>
&lengthTo=<?php echo $this->_tpl_vars['lengthTo']; ?>
&faDateFrom=<?php echo $this->_tpl_vars['faDateFrom']; ?>
&faDateTo=<?php echo $this->_tpl_vars['faDateTo']; ?>
&shipsClassification=<?php echo $this->_tpl_vars['shipsClassification']; ?>
&material=<?php echo $this->_tpl_vars['material']; ?>
&uploadTimeFrom=<?php echo $this->_tpl_vars['uploadTimeFrom']; ?>
&uploadTimeTo=<?php echo $this->_tpl_vars['uploadTimeTo']; ?>
&filename=<?php echo $this->_tpl_vars['filename']; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber']; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>
&unitPrice=<?php echo $this->_tpl_vars['unitPrice']; ?>
&batchNumber=<?php echo $this->_tpl_vars['batchNumber']; ?>
&purchaseNumber=<?php echo $this->_tpl_vars['purchaseNumber']; ?>
&destination=<?php echo $this->_tpl_vars['destination']; ?>
&storePlace=<?php echo $this->_tpl_vars['storePlace']; ?>
&sequenceNumber=<?php echo $this->_tpl_vars['sequenceNumber']; ?>
&manufactory=<?php echo $this->_tpl_vars['manufactory']; ?>

				&certificateNumber=<?php echo $this->_tpl_vars['certificateNumber']; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['checkoutBatch']; ?>
&materialNumber=<?php echo $this->_tpl_vars['materialNumber']; ?>
&consignmentBatch=<?php echo $this->_tpl_vars['consignmentBatch']; ?>

				&ruku=<?php echo $this->_tpl_vars['ruku']; ?>
&chuku=<?php echo $this->_tpl_vars['chuku']; ?>
&sale=<?php echo $this->_tpl_vars['sale']; ?>
&main=<?php echo $this->_tpl_vars['main']; ?>
&remark=<?php echo $this->_tpl_vars['remark']; ?>
&faNumber=<?php echo $this->_tpl_vars['faNumber']; ?>
" title="点击进行导出结果"> 导出表格 </a>
			</span>
	<?php else: ?>
		抱歉没有符合你搜索的条目
	<?php endif; ?>
	
	<table cellpadding="0" cellspacing="0">
		
		<?php if ($this->_tpl_vars['main_sequenceNumber']): ?>
		<tr>
			<th>批次</th>
			<th>日期</th>
			<th>车号/船号</th>
			<th>材料代码</th>
			<th style="size:6em">生产厂家</th>
			<th>船级</th>
			<th>材质</th>
			<th>厚</th>
			<th>宽</th>
			<th>长</th>
			<th>总量</th>
			<th>未入库</th>
			<th>库中</th>
			<th>已销售</th>
			<th>单重</th>
			<th>备注</th>
			<th>上传时间</th>
			<th>上传文件</th>
			<th>订单号</th>
			<th>订单子项号</th>
			<th>受订单价</th>
			<th>批号</th>
			<th width="6px">物料号</th>
			<th>购单号</th>
			<th>目的地</th>
			<th>库存地</th>
			<th>结算</th>
			<th width="6px">证书号</th>
			<th width="6px">结算批号</th>
			<th width="6px">发货批次</th>
			<th style="width:5em">详细</th>
			<th style="width:5em">删除<input type="checkbox" name="i_d" onchange="allChecksChange(this)" /></th>
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
		<tr <?php if ($this->_tpl_vars['main_sumCount'][$this->_sections['main']['index']] == $this->_tpl_vars['main_soldCount'][$this->_sections['main']['index']]): ?> class="blue" title="此项完成"
			<?php elseif ($this->_tpl_vars['main_unRukuCount'][$this->_sections['main']['index']] == 0 && $this->_tpl_vars['main_soldCount'][$this->_sections['main']['index']] < $this->_tpl_vars['main_sumCount'][$this->_sections['main']['index']]): ?> class="chocolate" title="全部入库未全部出库"
			<?php elseif ($this->_tpl_vars['main_unRukuCount'][$this->_sections['main']['index']] > 0 && $this->_tpl_vars['main_unRukuCount'][$this->_sections['main']['index']] < $this->_tpl_vars['main_sumCount'][$this->_sections['main']['index']]): ?> class="brown" title="部分入库"
			<?php endif; ?>
			>
			<td><?php echo $this->_tpl_vars['main_sequenceNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php unset($this->_sections['a']);
$this->_sections['a']['name'] = 'a';
$this->_sections['a']['loop'] = is_array($_loop=$this->_tpl_vars['main_date'][$this->_sections['main']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['a']['show'] = true;
$this->_sections['a']['max'] = $this->_sections['a']['loop'];
$this->_sections['a']['step'] = 1;
$this->_sections['a']['start'] = $this->_sections['a']['step'] > 0 ? 0 : $this->_sections['a']['loop']-1;
if ($this->_sections['a']['show']) {
    $this->_sections['a']['total'] = $this->_sections['a']['loop'];
    if ($this->_sections['a']['total'] == 0)
        $this->_sections['a']['show'] = false;
} else
    $this->_sections['a']['total'] = 0;
if ($this->_sections['a']['show']):

            for ($this->_sections['a']['index'] = $this->_sections['a']['start'], $this->_sections['a']['iteration'] = 1;
                 $this->_sections['a']['iteration'] <= $this->_sections['a']['total'];
                 $this->_sections['a']['index'] += $this->_sections['a']['step'], $this->_sections['a']['iteration']++):
$this->_sections['a']['rownum'] = $this->_sections['a']['iteration'];
$this->_sections['a']['index_prev'] = $this->_sections['a']['index'] - $this->_sections['a']['step'];
$this->_sections['a']['index_next'] = $this->_sections['a']['index'] + $this->_sections['a']['step'];
$this->_sections['a']['first']      = ($this->_sections['a']['iteration'] == 1);
$this->_sections['a']['last']       = ($this->_sections['a']['iteration'] == $this->_sections['a']['total']);
?>
			<?php echo $this->_tpl_vars['main_date'][$this->_sections['main']['index']][$this->_sections['a']['index']]; ?>
 <br>
			<?php endfor; endif; ?>
			</td>
			<td><?php unset($this->_sections['b']);
$this->_sections['b']['name'] = 'b';
$this->_sections['b']['loop'] = is_array($_loop=$this->_tpl_vars['main_faNumber'][$this->_sections['main']['index']]) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['b']['show'] = true;
$this->_sections['b']['max'] = $this->_sections['b']['loop'];
$this->_sections['b']['step'] = 1;
$this->_sections['b']['start'] = $this->_sections['b']['step'] > 0 ? 0 : $this->_sections['b']['loop']-1;
if ($this->_sections['b']['show']) {
    $this->_sections['b']['total'] = $this->_sections['b']['loop'];
    if ($this->_sections['b']['total'] == 0)
        $this->_sections['b']['show'] = false;
} else
    $this->_sections['b']['total'] = 0;
if ($this->_sections['b']['show']):

            for ($this->_sections['b']['index'] = $this->_sections['b']['start'], $this->_sections['b']['iteration'] = 1;
                 $this->_sections['b']['iteration'] <= $this->_sections['b']['total'];
                 $this->_sections['b']['index'] += $this->_sections['b']['step'], $this->_sections['b']['iteration']++):
$this->_sections['b']['rownum'] = $this->_sections['b']['iteration'];
$this->_sections['b']['index_prev'] = $this->_sections['b']['index'] - $this->_sections['b']['step'];
$this->_sections['b']['index_next'] = $this->_sections['b']['index'] + $this->_sections['b']['step'];
$this->_sections['b']['first']      = ($this->_sections['b']['iteration'] == 1);
$this->_sections['b']['last']       = ($this->_sections['b']['iteration'] == $this->_sections['b']['total']);
?>
			<?php echo $this->_tpl_vars['main_faNumber'][$this->_sections['main']['index']][$this->_sections['b']['index']]; ?>
 <br>
			<?php endfor; endif; ?>
			</td>
			<td><?php echo $this->_tpl_vars['main_materialCode'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_manufactory'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_shipsClassification'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_material'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_thickness'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_width'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_length'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_sumCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_unRukuCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_kuzhongCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_soldCount'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_unitWeight'][$this->_sections['main']['index']]; ?>
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
			<td><?php echo $this->_tpl_vars['main_unitPrice'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['main_batchNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['main_materialNumber'][$this->_sections['main']['index']])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15) : smarty_modifier_truncate($_tmp, 15)); ?>
</td>
			<td><?php echo $this->_tpl_vars['main_purchaseNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_destination'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_storePlace'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php if ($this->_tpl_vars['main_checkout']): ?>
				<a href="editCheckout.php?materialCode=<?php echo $this->_tpl_vars['main_materialCode'][$this->_sections['main']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['main_orderNumber'][$this->_sections['main']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['main_orderSubitemNumber'][$this->_sections['main']['index']]; ?>
&certificateNumber=<?php echo $this->_tpl_vars['main_certificateNumber'][$this->_sections['main']['index']]; ?>
&checkoutBatch=<?php echo $this->_tpl_vars['main_checkoutBatch'][$this->_sections['main']['index']]; ?>
">已结算</a>
				<?php endif; ?>
			</td>
			<td><?php echo $this->_tpl_vars['main_certificateNumber'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_checkoutBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><?php echo $this->_tpl_vars['main_consignmentBatch'][$this->_sections['main']['index']]; ?>
</td>
			<td><a href="search.php?stype=materialCode&keyname=<?php echo $this->_tpl_vars['main_materialCode'][$this->_sections['main']['index']]; ?>
&orderNumber=<?php echo $this->_tpl_vars['main_orderNumber'][$this->_sections['main']['index']]; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['main_orderSubitemNumber'][$this->_sections['main']['index']]; ?>
" target="_blank">详细</a></td>
			<td><input type="checkbox" name="main_id<?php echo $this->_tpl_vars['main_ids'][$this->_sections['main']['index']]; ?>
" value="<?php echo $this->_tpl_vars['main_ids'][$this->_sections['main']['index']]; ?>
" /></td>
		</tr>
		<?php endfor; endif; ?>
		<?php endif; ?>
		
		
		
	</table>
	</form>
	</body>
</html>