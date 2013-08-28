<?php /* Smarty version 2.6.26, created on 2010-11-03 14:49:20
         compiled from summary.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'summary.html', 187, false),)), $this); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>汇总表格生成</title>
<link type="text/css" rel="stylesheet" href="../css/default.css" />
<style type="text/css">
.choose-box {
	display:block;
	padding-top:10px;
}
</style>
<script type="text/javascript" src="../js/XMLHttpRequest.js">
</script>
<script type="text/javascript">
	function addOption(select,option,url){
		var shipNumber = document.getElementById(select).value;
		type = option;
		sendRequest(url+shipNumber);
	}

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

	function sub(){
		var radio = document.getElementsByName("row");
		for(j=0;j<radio.length;j++){
			if(radio[j].checked){
				if(j==0){
					document.form1.action = "exportSummaryConsignmentBatch.php";
				}else if(j==1){
					document.form1.action = "exportSummarySubNumber.php";
				}else if(j==2){
					document.getElementById("Date_Month").name="Date_Months[]";
					document.form1.action = "exportSummaryDate.php";
				}else{
					document.form1.action = "exportSummaryShipNumber.php";
				}
			}
		}
	}
</script>
</head>
    <body>
    <div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1; text-align:left;">
			<span><a href="excelUpload.html">表格上传</a></span>
			<span><a href="index.html">主页</a></span>
	</div>
	<form method="post" name="form1" onsubmit="sub();">
	<div style="text-align: left;font-size: 20px; color: blue;"> 横向选项：
	<input type="checkbox" name="i_d" onchange="allChecksChange(this)">
	</div>
	<div class="choose-box">
		<table border="1px">
		<tbody>
		<tr>
			<td colspan="4" align="center">订货数量</td>
			<td colspan="6" align="center">入库</td>
		</tr>
		<tr>
			<td>总计<input value="订货数量" id="main" name="main" type="checkbox"></td>
			<td>鲅鱼圈厚板<input value="鲅鱼圈厚板" id="main_manu_bahou" name="main_manu_bahou" type="checkbox"></td>
			<td>鞍钢中板<input value="鞍钢中板" id="main_manu_anzhong" name="main_manu_anzhong" type="checkbox"></td>
			<td>鞍钢厚板<input value="鞍钢厚板" id="main_manu_anhou" name="main_manu_anhou" type="checkbox"></td>
			<td>总计<input value="总计" id="ruku" name="ruku" type="checkbox"></td>
			<td>鞍山加工中心<input value="鞍山加工中心" id="ruku_store_anjia" name="ruku_store_anjia" type="checkbox"></td>
			<td>营口港务局<input value="营口港务局" id="ruku_store_yinggan" name="ruku_store_yinggan" type="checkbox"></td>
			<td>鲅鱼圈厚板<input value="鲅鱼圈厚板" id="ruku_manu_bahou" name="ruku_manu_bahou" type="checkbox"></td>
			<td>鞍钢中板<input value="鞍钢中板" id="ruku_manu_anzhong" name="ruku_manu_anzhong" type="checkbox"></td>
			<td>鞍钢厚板<input value="鞍钢厚板" id="ruku_manu_anhou" name="ruku_manu_anhou" type="checkbox"></td>
		</tr>
		<tr>
			<td colspan="10" align="center">出库</td>
		</tr>
		<tr>
			<td>总计<input value="出库" id="chuku" name="chuku" type="checkbox"></td>
			<td>鲅鱼圈厚板<input value="鲅鱼圈厚板" id="chuku_manu_bahou" name="chuku_manu_bahou" type="checkbox"></td>
			<td>鞍钢中板<input value="鞍钢中板" id="chuku_manu_anzhong" name="chuku_manu_anzhong" type="checkbox"></td>
			<td>鞍钢厚板<input value="鞍钢厚板" id="chuku_manu_anhou" name="chuku_manu_anhou" type="checkbox"></td>
			<td>鞍山加工中心<input value="鞍山加工中心" id="chuku_store_anjia" name="chuku_store_anjia" type="checkbox"></td>
			<td>营口港务局<input value="营口港务局" id="chuku_store_yinggan" name="chuku_store_yinggan" type="checkbox"></td>
			<td>二部<input value="二部" id="chuku_dest_erbu" name="chuku_dest_erbu" type="checkbox"></td>
			<td>内业<input value="内业" id="chuku_dest_neiye" name="chuku_dest_neiye" type="checkbox"></td>
			<td>老厂<input value="老厂" id="chuku_dest_laochang" name="chuku_dest_laochang" type="checkbox"></td>
			<td>大连<input value="大连" id="chuku_dest_dalian" name="chuku_dest_dalian" type="checkbox"></td>
			<td>发车<input value="发车" id="chuku_meth_fache" name="chuku_meth_fache" type="checkbox"></td>
			<td>发船<input value="发船" id="chuku_meth_fachuan" name="chuku_meth_fachuan" type="checkbox"></td>
		</tr>
		<tr>
			<td colspan="10" align="center">销售</td>
		</tr>
		<tr>
			<td>总计<input value="销售" id="sale" name="sale" type="checkbox"></td>
			<td>鲅鱼圈厚板<input value="鲅鱼圈厚板" id="sale_manu_bahou" name="sale_manu_bahou" type="checkbox"></td>
			<td>鞍钢中板<input value="鞍钢中板" id="sale_manu_anzhong" name="sale_manu_anzhong" type="checkbox"></td>
			<td>鞍钢厚板<input value="鞍钢厚板" id="sale_manu_anhou" name="sale_manu_anhou" type="checkbox"></td>
			<td>二部<input value="二部" id="sale_dest_erbu" name="sale_dest_erbu" type="checkbox"></td>
			<td>内业<input value="内业" id="sale_dest_neiye" name="sale_dest_neiye" type="checkbox"></td>
			<td>老厂<input value="老厂" id="sale_dest_laochang" name="sale_dest_laochang" type="checkbox"></td>
			<td>大连<input value="大连" id="sale_dest_dalian" name="sale_dest_dalian" type="checkbox"></td>
		</tr>
		<tr>
			<td colspan="6" align="center">库中</td>
			<td colspan="4" align="center">欠交</td>
		</tr>
		<tr>
			<td>总计<input value="库中" id="kuzhong" name="kuzhong" type="checkbox"></td>
			<td>鞍山加工中心<input value="鞍山加工中心" id="kuzhong_store_anjia" name="kuzhong_store_anjia" type="checkbox"></td>
			<td>营口港务局<input value="营口港务局" id="kuzhong_store_yinggan" name="kuzhong_store_yinggan" type="checkbox"></td>
			<td>鲅鱼圈厚板<input value="鲅鱼圈厚板" id="kuzhong_manu_bahou" name="kuzhong_manu_bahou" type="checkbox"></td>
			<td>鞍钢中板<input value="鞍钢中板" id="kuzhong_manu_anzhong" name="kuzhong_manu_anzhong" type="checkbox"></td>
			<td>鞍钢厚板<input value="鞍钢厚板" id="kuzhong_manu_anhou" name="kuzhong_manu_anhou" type="checkbox"></td>
			<td>总计<input value="欠交" id="unRuku" name="unRuku" type="checkbox"></td>
			<td>鲅鱼圈厚板<input value="鲅鱼圈厚板" id="unRuku_manu_bahou" name="unRuku_manu_bahou" type="checkbox"></td>
			<td>鞍钢中板<input value="鞍钢中板" id="unRuku_manu_anzhong" name="unRuku_manu_anzhong" type="checkbox"></td>
			<td>鞍钢厚板<input value="鞍钢厚板" id="unRuku_manu_anhou" name="unRuku_manu_anhou" type="checkbox"></td>
		</tr>
		</tbody>
		</table>
		
		<hr />
		<div style="text-align: left;font-size: 20px; color: blue;"> 纵向选项：</div>
		<table border="1px">
		<tbody>
		<tr>
		<td class="name">
		<input value="consignmentBatch" name="row" type="radio" checked="checked">船号/发货批次: 
		</td>
		<td>
		<select id="shipNumber_consignmentBatch" name="shipNumber_consignmentBatch" onchange="addOption('shipNumber_consignmentBatch','consignmentBatchs','../phpApps/addConsignmentBatch.php?shipNumber=')">
			<option value="1" selected="selected">船号</option>
			<?php unset($this->_sections['shipNumber']);
$this->_sections['shipNumber']['name'] = 'shipNumber';
$this->_sections['shipNumber']['loop'] = is_array($_loop=$this->_tpl_vars['shipNumbers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['shipNumber']['show'] = true;
$this->_sections['shipNumber']['max'] = $this->_sections['shipNumber']['loop'];
$this->_sections['shipNumber']['step'] = 1;
$this->_sections['shipNumber']['start'] = $this->_sections['shipNumber']['step'] > 0 ? 0 : $this->_sections['shipNumber']['loop']-1;
if ($this->_sections['shipNumber']['show']) {
    $this->_sections['shipNumber']['total'] = $this->_sections['shipNumber']['loop'];
    if ($this->_sections['shipNumber']['total'] == 0)
        $this->_sections['shipNumber']['show'] = false;
} else
    $this->_sections['shipNumber']['total'] = 0;
if ($this->_sections['shipNumber']['show']):

            for ($this->_sections['shipNumber']['index'] = $this->_sections['shipNumber']['start'], $this->_sections['shipNumber']['iteration'] = 1;
                 $this->_sections['shipNumber']['iteration'] <= $this->_sections['shipNumber']['total'];
                 $this->_sections['shipNumber']['index'] += $this->_sections['shipNumber']['step'], $this->_sections['shipNumber']['iteration']++):
$this->_sections['shipNumber']['rownum'] = $this->_sections['shipNumber']['iteration'];
$this->_sections['shipNumber']['index_prev'] = $this->_sections['shipNumber']['index'] - $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['index_next'] = $this->_sections['shipNumber']['index'] + $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['first']      = ($this->_sections['shipNumber']['iteration'] == 1);
$this->_sections['shipNumber']['last']       = ($this->_sections['shipNumber']['iteration'] == $this->_sections['shipNumber']['total']);
?>
			<option value="<?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
"><?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
</option>
			<?php endfor; endif; ?>
		</select>
		</td>
		<td>
		<select id="consignmentBatchs" name="consignmentBatchs[]" class="input-text" multiple="multiple">
			<option value="" selected="selected">发货批次</option>
		</select>
		</td>
		<td>包含汉字<input value="包含汉字" id="consignmentBatchs_hanzi" name="consignmentBatchs_hanzi" type="checkbox" checked="checked"></td>
		</tr>
		<tr>
		<td class="name">
		<input value="subNumber" name="row" type="radio">船号/分段号: 
		</td>
		<td>
		<select id="shipNumber_subNumber" name="shipNumber_subNumber" onchange="addOption('shipNumber_subNumber','subNumbers','../phpApps/addSubNumber.php?shipNumber=')">
			<option value="" selected="selected">船号</option>
			<?php unset($this->_sections['shipNumber']);
$this->_sections['shipNumber']['name'] = 'shipNumber';
$this->_sections['shipNumber']['loop'] = is_array($_loop=$this->_tpl_vars['shipNumbers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['shipNumber']['show'] = true;
$this->_sections['shipNumber']['max'] = $this->_sections['shipNumber']['loop'];
$this->_sections['shipNumber']['step'] = 1;
$this->_sections['shipNumber']['start'] = $this->_sections['shipNumber']['step'] > 0 ? 0 : $this->_sections['shipNumber']['loop']-1;
if ($this->_sections['shipNumber']['show']) {
    $this->_sections['shipNumber']['total'] = $this->_sections['shipNumber']['loop'];
    if ($this->_sections['shipNumber']['total'] == 0)
        $this->_sections['shipNumber']['show'] = false;
} else
    $this->_sections['shipNumber']['total'] = 0;
if ($this->_sections['shipNumber']['show']):

            for ($this->_sections['shipNumber']['index'] = $this->_sections['shipNumber']['start'], $this->_sections['shipNumber']['iteration'] = 1;
                 $this->_sections['shipNumber']['iteration'] <= $this->_sections['shipNumber']['total'];
                 $this->_sections['shipNumber']['index'] += $this->_sections['shipNumber']['step'], $this->_sections['shipNumber']['iteration']++):
$this->_sections['shipNumber']['rownum'] = $this->_sections['shipNumber']['iteration'];
$this->_sections['shipNumber']['index_prev'] = $this->_sections['shipNumber']['index'] - $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['index_next'] = $this->_sections['shipNumber']['index'] + $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['first']      = ($this->_sections['shipNumber']['iteration'] == 1);
$this->_sections['shipNumber']['last']       = ($this->_sections['shipNumber']['iteration'] == $this->_sections['shipNumber']['total']);
?>
			<option value="<?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
"><?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
</option>
			<?php endfor; endif; ?>
		</select>
		</td>
		<td>
		<select id="subNumbers" name="subNumbers[]" class="input-text" multiple="multiple">
			<option value="" selected="selected">分段号</option>
		</select>
		</td>
		<td>包含汉字<input value="包含汉字" id="subNumbers_hanzi" name="subNumbers_hanzi" type="checkbox" checked="checked"></td>
		</tr>
		
		<tr>
		<td class="name">
		<input value="3" value="date" name="row" type="radio">船号/日期 : 
		</td>
		<td>
		<select id="shipNumber_date" name="shipNumber_date">
			<option value="1" selected="selected">船号</option>
			<?php unset($this->_sections['shipNumber']);
$this->_sections['shipNumber']['name'] = 'shipNumber';
$this->_sections['shipNumber']['loop'] = is_array($_loop=$this->_tpl_vars['shipNumbers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['shipNumber']['show'] = true;
$this->_sections['shipNumber']['max'] = $this->_sections['shipNumber']['loop'];
$this->_sections['shipNumber']['step'] = 1;
$this->_sections['shipNumber']['start'] = $this->_sections['shipNumber']['step'] > 0 ? 0 : $this->_sections['shipNumber']['loop']-1;
if ($this->_sections['shipNumber']['show']) {
    $this->_sections['shipNumber']['total'] = $this->_sections['shipNumber']['loop'];
    if ($this->_sections['shipNumber']['total'] == 0)
        $this->_sections['shipNumber']['show'] = false;
} else
    $this->_sections['shipNumber']['total'] = 0;
if ($this->_sections['shipNumber']['show']):

            for ($this->_sections['shipNumber']['index'] = $this->_sections['shipNumber']['start'], $this->_sections['shipNumber']['iteration'] = 1;
                 $this->_sections['shipNumber']['iteration'] <= $this->_sections['shipNumber']['total'];
                 $this->_sections['shipNumber']['index'] += $this->_sections['shipNumber']['step'], $this->_sections['shipNumber']['iteration']++):
$this->_sections['shipNumber']['rownum'] = $this->_sections['shipNumber']['iteration'];
$this->_sections['shipNumber']['index_prev'] = $this->_sections['shipNumber']['index'] - $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['index_next'] = $this->_sections['shipNumber']['index'] + $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['first']      = ($this->_sections['shipNumber']['iteration'] == 1);
$this->_sections['shipNumber']['last']       = ($this->_sections['shipNumber']['iteration'] == $this->_sections['shipNumber']['total']);
?>
			<option value="<?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
"><?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
</option>
			<?php endfor; endif; ?>
		</select>
		</td>
		<td>
		<?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['time'],'start_year' => "-5",'end_year' => "+1",'display_days' => false,'display_months' => false), $this);?>

		</td>
		<td>
		<?php echo smarty_function_html_select_date(array('id' => 'Date_Month','time' => $this->_tpl_vars['time'],'display_days' => false,'display_years' => false,'multiple' => 'multiple'), $this);?>

		</td>
		</tr>
		
		<tr>
		<td class="name">
		<input value="3" value="date" name="row" type="radio">船号 : 
		</td>
		<td>
		<select id="shipNumber_shipNumber" name="shipNumber_shipNumber[]" multiple="multiple">
			<option value="1" selected="selected">船号</option>
			<?php unset($this->_sections['shipNumber']);
$this->_sections['shipNumber']['name'] = 'shipNumber';
$this->_sections['shipNumber']['loop'] = is_array($_loop=$this->_tpl_vars['shipNumbers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['shipNumber']['show'] = true;
$this->_sections['shipNumber']['max'] = $this->_sections['shipNumber']['loop'];
$this->_sections['shipNumber']['step'] = 1;
$this->_sections['shipNumber']['start'] = $this->_sections['shipNumber']['step'] > 0 ? 0 : $this->_sections['shipNumber']['loop']-1;
if ($this->_sections['shipNumber']['show']) {
    $this->_sections['shipNumber']['total'] = $this->_sections['shipNumber']['loop'];
    if ($this->_sections['shipNumber']['total'] == 0)
        $this->_sections['shipNumber']['show'] = false;
} else
    $this->_sections['shipNumber']['total'] = 0;
if ($this->_sections['shipNumber']['show']):

            for ($this->_sections['shipNumber']['index'] = $this->_sections['shipNumber']['start'], $this->_sections['shipNumber']['iteration'] = 1;
                 $this->_sections['shipNumber']['iteration'] <= $this->_sections['shipNumber']['total'];
                 $this->_sections['shipNumber']['index'] += $this->_sections['shipNumber']['step'], $this->_sections['shipNumber']['iteration']++):
$this->_sections['shipNumber']['rownum'] = $this->_sections['shipNumber']['iteration'];
$this->_sections['shipNumber']['index_prev'] = $this->_sections['shipNumber']['index'] - $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['index_next'] = $this->_sections['shipNumber']['index'] + $this->_sections['shipNumber']['step'];
$this->_sections['shipNumber']['first']      = ($this->_sections['shipNumber']['iteration'] == 1);
$this->_sections['shipNumber']['last']       = ($this->_sections['shipNumber']['iteration'] == $this->_sections['shipNumber']['total']);
?>
			<option value="<?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
"><?php echo $this->_tpl_vars['shipNumbers'][$this->_sections['shipNumber']['index']]; ?>
</option>
			<?php endfor; endif; ?>
		</select>
		</td>
		</tr>
		
		</tbody>
		</table>
	</div>
	<input type="submit" value="导出汇总表格" name="submit">
	</form>
    </body>
</html>