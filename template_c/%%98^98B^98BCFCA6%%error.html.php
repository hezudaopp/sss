<?php /* Smarty version 2.6.26, created on 2010-10-26 16:19:22
         compiled from error.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($this->_tpl_vars['errTitle']): ?><?php echo $this->_tpl_vars['errTitle']; ?>
<?php else: ?>上传失败<?php endif; ?></title>
</head>
<body style="margin: 0 auto; width: 760px; text-align: center;">
<div
	style="font-size: 14px; border: #bbb 1px solid; margin-top: 20px; text-align: left; background-color: #eee; padding: 10px; font-family: arial; float: left;">
<h1 style="text-align: left"><?php if ($this->_tpl_vars['errTitle']): ?><?php echo $this->_tpl_vars['errTitle']; ?>
<?php else: ?>上传失败<?php endif; ?></h1>
<hr />

<p style="color: red; line-height: 1.5em;"><?php if ($this->_tpl_vars['errTitle']): ?><?php else: ?>出现错误导致上传失败，请按照下面提示修改后重新上传：<br />
<?php endif; ?> <?php echo $this->_tpl_vars['errMsg']; ?>
</p>

<div style="float: right; font-size: 16px;"><a href="index.html">回到主页</a><br />
<?php if ($this->_tpl_vars['errorType'] == 'monitorUpload'): ?> <a href="monitorAdd.html">回到监控上传页面</a>
<?php elseif ($this->_tpl_vars['errorType'] == 'monitorDelete' || $this->_tpl_vars['errorType'] == 'deleteInMonitor'): ?> <a href="monitors.php">回到监控页面</a>
<?php elseif ($this->_tpl_vars['errorType'] == 'listDelete' || $this->_tpl_vars['errorType'] == 'deleteInList'): ?> <a href="checkoutList.php">回到结算清单页面</a>
<?php elseif ($this->_tpl_vars['errorType'] == 'listUpload'): ?> <a href="checkoutList.php">回到结算清单页面</a>
<?php elseif ($this->_tpl_vars['errorType'] == 'consignmentDelete' || $this->_tpl_vars['errorType'] == 'deleteInConsignment'): ?> <a href="consignments.php">回到发货批次页面</a>
<?php elseif ($this->_tpl_vars['errorType'] == 'consignmentUpload'): ?> <a href="consignments.php">回到发货批次页面</a> 
<?php else: ?> 
<a href="excelUpload.html">回到上传页面</a> <?php endif; ?>
</div>
<div style="clear: both;"></div>
</div>

</body>
</html>