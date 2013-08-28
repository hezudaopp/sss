<?php /* Smarty version 2.6.14, created on 2008-01-24 21:48:08
         compiled from confirm.html */ ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>进行确认</title>
	</head>
	<body style="margin:0 auto; width:760px; text-align:center;">
		<div style="font-size:14px; 
			border:#bbb 1px solid;
		   	margin-top:20px;
		 	background-color:#eee;
			padding:10px;
			font-family:arial;
			text-align:center;">
<p style="text-align:left;">如果这个文件没有出现问题，请务必点击这个“确认”或者“忽略”按钮，数据才能提交到数据库，否则，选择取消。<br />
<b style="color:green">确认按钮：只提交那些表格中非紫色，非红色的部分；</b><br />
<b style="color:purple">忽略按钮：提交表格中所有非红色的部分</b><br />
<div style="background-color:white; width:90%; text-align:left; border:1px #ccc solid; padding:5px; margin-bottom:20px;">
<ol type="I"><li><span style="color:red">红色出现在以下情况：上传发车表格的时候，这个表格中有些材料代码在数据库中找不到，或者某些记录的发车数量比总表中的数量还要多</span></li>
<li>
<span style="color:purple">紫色出现的情况：上传总表的时候，数据库中已经存在和这个文件中某些材料代码相同的行；或者在上传发车数据的时候，这里的某些材料代码在数据库中可以查得到，但是厚宽长不一样；</span></li>
<li>
<span style="color:green">绿色出现的情况：正常的情况，只是用来跟以前的数据作对比</span></li>
</ol>

</p>

<table cellspacing="0" width="100%">
<tr>
<td width="30%" align="center">
<form action="confirm.php" method="POST">
<input type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
" name="id" />
<input type="hidden" value="ok" name="type" />
<input style="padding:20px; font-size:18px;" type="submit" value="确认" />
</form>
</td>

<?php if ($this->_tpl_vars['haveRed']): ?>
<td width="30%" align="center">
<form action="confirm.php" method="POST">
<input type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
" name="id" />
<input type="hidden" value="ignore" name="type" />
<input style="padding:20px; font-size:18px;" type="submit" value="忽略" />
</form>
</td>
<?php endif; ?>

<td width="30%" align="center">
<form action="confirm.php" method="POST">
<input type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
" name="id" />
<input type="hidden" value="cancel" name="type" />
<input style="padding:20px; font-size:18px;" type="submit" value="取消" />
</form>
</td>
</tr>
</table>
<br />
<div style="clear:both; height:1px"></div>
		 </div>
		 <script>
		 window.open('../upload/<?php echo $this->_tpl_vars['filename']; ?>
');
		 </script>
	</body>
</html>