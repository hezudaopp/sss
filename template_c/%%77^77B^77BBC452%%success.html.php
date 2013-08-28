<?php /* Smarty version 2.6.26, created on 2010-07-26 09:27:19
         compiled from success.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($this->_tpl_vars['successTitle']): ?> <?php echo $this->_tpl_vars['successTitle']; ?>
 <?php else: ?> 上传成功
<?php endif; ?></title>
</head>
<body style="margin: 0 auto; width: 760px; text-align: center;">
<div
	style="font-size: 14px; border: #bbb 1px solid; margin-top: 20px; text-align: left; background-color: #eee; padding: 10px; font-family: arial; float: left;">
<h1 style="display: block; color: black;"><?php if ($this->_tpl_vars['successTitle']): ?>
<?php echo $this->_tpl_vars['successTitle']; ?>
 <?php else: ?> 上传成功 <?php endif; ?></h1>
<hr />
<p style="color: green; line-height: 1.5em;"><?php echo $this->_tpl_vars['successMsg']; ?>
</p>
<p style="color: purple; line-height: 1.5em;"><?php echo $this->_tpl_vars['warnMsg']; ?>
</p>

<div style="float: right; font-size: 16px;"><a href="index.html">回到主页</a><br />
<?php if ($this->_tpl_vars['successTitle'] == '删除记录成功'): ?> <a href="log.php">回到查看记录页面</a> <?php elseif ($this->_tpl_vars['successType'] == 'monitorDelete' || $this->_tpl_vars['successType'] == 'deleteInMonitor'): ?>
<a href="monitors.php"> 回到监控页面 </a><br /><a href="monitorAdd.html">回监控上传页面</a>
 <?php elseif ($this->_tpl_vars['successType'] == 'monitorUpload'): ?> 
 <a href="monitors.php"> 回到监控页面 </a><br /><a href="monitorAdd.html">回监控上传页面</a>
<?php elseif ($this->_tpl_vars['successType'] == 'listUpload'): ?> 
<a href="checkoutList.php"> 回到结算清单页面 </a><br /><a href="checkoutInput.php">回结算页面</a>
<?php elseif ($this->_tpl_vars['successType'] == 'listDelete' || $this->_tpl_vars['successType'] == 'deleteInList'): ?>
<a href="checkoutList.php"> 回到结算清单页面 </a>
<?php elseif ($this->_tpl_vars['successType'] == 'consignmentUpload'): ?> <a href="consignments.php"> 回到发货批次页面 </a>
<?php elseif ($this->_tpl_vars['successType'] == 'consignmentDelete' || $this->_tpl_vars['successType'] == 'deleteInConsignment'): ?>
<a href="consignments.php"> 回到发货批次页面 </a>
<br />
<br /> 
<?php else: ?> <a href="excelUpload.html">回到上传页面</a> <?php endif; ?></div>
	
<div style="clear: both;"></div>
</div>
</body>
</html>