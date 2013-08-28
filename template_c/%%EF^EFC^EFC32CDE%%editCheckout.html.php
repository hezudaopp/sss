<?php /* Smarty version 2.6.26, created on 2010-09-26 10:30:02
         compiled from editCheckout.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>结算修改</title>
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
				<b style="font-size:16px;">结算修改</b>
				</div>
			</td>
			</tr>
		</table>


		<form action="changeCheckout.php" method="POST" style="line-height:2em" id="advSearchForm">
		<table width="30%" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td>
				<label>材料代码：</label>
				</td>
				<td>
				<label><?php echo $this->_tpl_vars['materialCode']; ?>
</label>
				</td>
			</tr>
			<tr>
				<td>
				<label>订单号：</label>
				</td>
				<td>
				<label><?php echo $this->_tpl_vars['orderNumber']; ?>
</label>
				</td>
			</tr>
			<tr>
				<td>
				<label>订单子项号：</label>
				</td>
				<td>
				<label><?php echo $this->_tpl_vars['orderSubitemNumber']; ?>
</label>
				</td>
			</tr>
			<tr>
				<td>
				<label>证书号：</label>
				</td>
				<td>
				<label><?php echo $this->_tpl_vars['certificateNumber']; ?>
</label>
				</td>
			</tr>
			<tr>
				<td>
				<label>结算批次：</label>
				</td>
				<td>
				<label><?php echo $this->_tpl_vars['checkoutBatch']; ?>
</label>
				</td>
			</tr>
			<tr>
				<td>
				<input type="radio" name="group" value="unsettled" checked="checked"> 未结算
				</td>
				<td>
				<input type="radio" name="group" value="settled"> 已结算
				</td>
			</tr>
		</table>
		<input type="hidden" value="<?php echo $this->_tpl_vars['type']; ?>
" name="type" />
		<input type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
" name="id" />
		<input type="hidden" value="<?php echo $this->_tpl_vars['materialCode']; ?>
" name="materialCode" />
		<input type="hidden" value="<?php echo $this->_tpl_vars['orderNumber']; ?>
" name="orderNumber" />
		<input type="hidden" value="<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>
" name="orderSubitemNumber" />
		<input type="submit" value="提交修改" style="clear:left; padding:3px 6px 3px 6px;" />
		</form>
	</body>
</html>