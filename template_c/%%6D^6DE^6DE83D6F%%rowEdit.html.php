<?php /* Smarty version 2.6.26, created on 2010-07-26 09:46:40
         compiled from rowEdit.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>编辑</title>
		<link type="text/css" rel="stylesheet" href="../css/default.css" />
		<style>
.comment{
color:#8A95A1;
font-weight:bold;
font-style: italic;
}
		</style>
	</head>
	<body>
		<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1; text-align:left;">
			<span><a href="excelUpload.html">表格上传</a></span>
			 | <span><a href="index.html">主页</a></span>
		</div>

		<table width="100%">
			<tr valign="bottom">
			<td nowrap="" width="25%">
				<img id="logo" src="../img/SSS(small).png" height="80" align="left" />
			</td>
			<td>
				<div id="shoulder">
				<b style="font-size:16px;">编辑</b>
				</div>
			</td>
			</tr>
		</table>


		<form action="saveRow.php" method="POST" style="line-height:2em" id="advSearchForm">
		<table width="100%" cellpadding="0" cellspacing="0" align="left">
		
			<?php if ($this->_tpl_vars['fache']): ?>
			<tr valign="top">
				<td>
				<label>发车日期：</label>
				</td>
				<td><input tyep="text" name="facheDate" maxlength="10" value="<?php echo $this->_tpl_vars['facheDate']; ?>
" /><br />
			<span class="comment">(日期格式为： YYYY/MM/DD,比如要表达2007年8月1日，输入2007/08/01或2007-08-01)</span></td>
			</tr>

			<tr>
				<td>
				<label>车号：</label>
				</td>
				<td><input tyep="text" name="facheNumber" maxlength="10" value="<?php echo $this->_tpl_vars['facheNumber']; ?>
" /></td>
			</tr>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['fachuan']): ?>
			<tr valign="top">
				<td>
				<label>发船日期：</label>
				</td>
				<td>从<input tyep="text" name="fachuanDate" maxlength="10" value="<?php echo $this->_tpl_vars['fachuanDate']; ?>
" /><br />
			<span class="comment">(日期格式为： YYYY/MM/DD,比如要表达2007年8月1日，输入2007/08/01)</span></td>
			</tr>

			<tr>
				<td>
				<label>船号：</label>
				</td>
				<td><input tyep="text" name="fachuanNumber" maxlength="10" value="<?php echo $this->_tpl_vars['fachuanNumber']; ?>
" /></td>
			</tr>
			<?php endif; ?>
					
			<tr style="background-color:#cbdced;">
				<td width="20%"><label for="materialCode">材料代码：</label></td>
				<td><input type="text" name="materialCode" maxlength="32" size="32" value="<?php echo $this->_tpl_vars['materialCode']; ?>
" /></td>
			</tr>

			<?php if ($this->_tpl_vars['main']): ?>
			<tr>
				<td>
				<label>批次：</label>
				</td>
				<td><input tyep="text" name="sequenceNumber" maxlength="10" value="<?php echo $this->_tpl_vars['sequenceNumber']; ?>
" /></td>
			</tr>
			<tr>
				<td>
				<label>生产厂家：</label>
				</td>
				<td><input tyep="text" name="manufactory" maxlength="60" value="<?php echo $this->_tpl_vars['manufactory']; ?>
" /></td>
			</tr>
			<?php endif; ?>
			
			<tr>
				<td><label for="shipsClassification">船级：</label></td>
				<td><input type="text" name="shipsClassification" maxlength="10" value="<?php echo $this->_tpl_vars['shipsClassification']; ?>
" /></td>
			</tr>

			<tr>
				<td><label for="material">材质：</label></td>
				<td><input type="text" name="material" maxlength="10" value="<?php echo $this->_tpl_vars['material']; ?>
" /></td>
			</tr>

			<tr style="background-color:#cbdced;" valign="top">
				<td><label>尺寸： </label></td>
				<td><label for="thickness">厚：</label><input type="text" name="thickness" maxlength="10" size=10 value="<?php echo $this->_tpl_vars['thickness']; ?>
" /><br />
					<label for="width">宽：</label><input type="text" name="width" maxlength="10" size="10" value="<?php echo $this->_tpl_vars['width']; ?>
" /><br />
				<label for="length">长：</label><input type="text" name="length" maxlength="10" size="10" value="<?php echo $this->_tpl_vars['length']; ?>
" /><span class="comment">（一定要填写数字）</span></td>
			</tr>

			<tr>
				<td>
				<label>数量：</label>
				</td>
				<td><input tyep="text" name="count" maxlength="10" value="<?php echo $this->_tpl_vars['count']; ?>
" /></td>
			</tr>
			<tr>
				<td>
				<label>单重：</label>
				</td>
				<td><input tyep="text" name="unitWeight" maxlength="10" value="<?php echo $this->_tpl_vars['unitWeight']; ?>
" /></td>
			</tr>
			
			<tr>
				<td>
				<label>订单号：</label>
				</td>
				<td><input tyep="text" name="orderNumber" maxlength="10" value="<?php echo $this->_tpl_vars['orderNumber']; ?>
" /></td>
			</tr>
			
			<tr>
				<td>
				<label>订单子项号：</label>
				</td>
				<td><input tyep="text" name="orderSubitemNumber" maxlength="60" value="<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="remark1">备注1：</label></td>
				<td><input type="text" name="remark1" maxlength="255" value="<?php echo $this->_tpl_vars['remark1']; ?>
" /></td>
			</tr>

			<tr>
				<td><label for="remark2">备注2：</label></td>
				<td><input type="text" name="remark2" maxlength="255" value="<?php echo $this->_tpl_vars['remark2']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="remark3">备注3：</label></td>
				<td><input type="text" name="remark3" maxlength="255" value="<?php echo $this->_tpl_vars['remark3']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="remark4">备注4：</label></td>
				<td><input type="text" name="remark4" maxlength="255" value="<?php echo $this->_tpl_vars['remark4']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="remark5">备注5：</label></td>
				<td><input type="text" name="remark5" maxlength="255" value="<?php echo $this->_tpl_vars['remark5']; ?>
" /></td>
			</tr>
			
			<tr>
				<td>
				<label>受订单价：</label>
				</td>
				<td><input tyep="text" name="unitPrice" maxlength="60" value="<?php echo $this->_tpl_vars['unitPrice']; ?>
" /></td>
			</tr>
			<tr>
				<td>
				<label>批号：</label>
				</td>
				<td><input tyep="text" name="batchNumber" maxlength="60" value="<?php echo $this->_tpl_vars['batchNumber']; ?>
" /></td>
			</tr>
			<tr>
				<td>
				<label>购单号：</label>
				</td>
				<td><input tyep="text" name="purchaseNumber" maxlength="60" value="<?php echo $this->_tpl_vars['purchaseNumber']; ?>
" /></td>
			</tr>
			<tr>
				<td>
				<label>目的地：</label>
				</td>
				<td><input tyep="text" name="destination" maxlength="60" value="<?php echo $this->_tpl_vars['destination']; ?>
" /></td>
			</tr>
			<tr>
				<td>
				<label>库存地：</label>
				</td>
				<td><input tyep="text" name="storePlace" maxlength="60" value="<?php echo $this->_tpl_vars['storePlace']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="certificateNumber">证书号：</label></td>
				<td><input type="text" name="certificateNumber" maxlength="255" value="<?php echo $this->_tpl_vars['certificateNumber']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="checkoutBatch">结算批号：</label></td>
				<td><input type="text" name="checkoutBatch" maxlength="255" value="<?php echo $this->_tpl_vars['checkoutBatch']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="materialNumber">物料号：</label></td>
				<td><input type="text" name="materialNumber" maxlength="255" value="<?php echo $this->_tpl_vars['materialNumber']; ?>
" /></td>
			</tr>
			
			<tr>
				<td><label for="consignmentBatch">发货批次：</label></td>
				<td><input type="text" name="consignmentBatch" maxlength="255" value="<?php echo $this->_tpl_vars['consignmentBatch']; ?>
" /></td>
			</tr>
			
			<?php if ($this->_tpl_vars['fache']): ?>
			<tr>
				<td><label for="phase">阶段：</label></td>
				<td><select name="phase">
				<option <?php if ($this->_tpl_vars['phase'] == '入库'): ?>selected<?php endif; ?>>入库</option>
				<option <?php if ($this->_tpl_vars['phase'] == '出库'): ?>selected<?php endif; ?>>出库</option>
				<option <?php if ($this->_tpl_vars['phase'] == '销售'): ?>selected<?php endif; ?>>销售</option>
				</select>
				</td>
			</tr>
			<?php endif; ?>

		</table>
		<input type="hidden" value="<?php echo $this->_tpl_vars['type']; ?>
" name="type" />
		<input type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
" name="id" />
		<input type="submit" value="提交修改" style="clear:left; padding:3px 6px 3px 6px;" />
		</form>
	</body>
</html>