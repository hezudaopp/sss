<?php /* Smarty version 2.6.26, created on 2010-09-26 10:19:18
         compiled from clear.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>清理界面</title>
<link type="text/css" rel="stylesheet" href="../css/default.css" />
<script type="text/javascript" language="Javascript">
		function cannotRunAlert(){
			window.alert('这个功能已经被程序员取消了');
			return false;
		}
		</script>
</head>

<body>
<div class="wrapper">
<h1>清理界面</h1>
<hr />

<?php if ($this->_tpl_vars['feedback']): ?>
<div class="links"><?php echo $this->_tpl_vars['feedback']; ?>
</div>
<?php endif; ?>

<p>管理员应该经常运行这里的清理命令，来及时清理我们的应用程序遗留的数据。</p>
<p>
<form action="clear.php" method="POST"
	onsubmit="return cannotRunAlert()"><input type="hidden"
	value="tempLog" name='type'><input type="submit"
	value="清理临时数据库" style="color: blue; padding: 5px;" /></form>
<br />
临时数据库中的数据只在程序运行的时候才有用，程序运行完后就成了垃圾数据，建议管理员每周清理一次或者两次，但注意不能在其他人正在上传表格的时候运行这里的命令，否则会导致程序无法正确运行。
</p>
<p>
<form action="clear.php" method="POST"><input type="hidden"
	value="tempExcel" name='type'><input type="submit"
	value="清理临时的excel文件" style="color: blue; padding: 5px;" /></form>
<br />
这里的文件是用户上传和下载excel文件时留在服务器的临时文件，时间久了会占用大量硬盘空间，而这些文件对于已运行完当次提交的服务器是基本没有用处的，建议用户每个月可以清理一次，同样注意，不能在其他用户上传、下载表格的时候进行清理。
</p>
<p>
<form action="clear.php" method="POST"
	onsubmit="return cannotRunAlert()"><input type="hidden"
	value="successed" name='type'><input type="submit"
	value="清理数据库中已完成发车（或发船）的记录" style="color: blue; padding: 5px;" /></form>
<br />
这个视个人情况而定，用户提交的总表的所有数据都存放在了数据库中，在发车和发船的时候，应用程序自动跟以前的记录进行比对，如果总表中的记录已经全部发车（或发船）完成，这些记录对于数据库应该没有用处了，用户可以有选择性的进行删除，程序清理时只清理那些已经完成的记录。另外，经常清理对程序的运行速度会有所帮助。
</p>
<!--<div class="links">
		<form action="phpApps/planFileUploadAction.php" method="post" enctype="multipart/form-data" onsubmit="return check_planFields();">
		<label for="planFile">上传导入总表：</label><br />
		<input name="planFile"  id="planFile" size="38" style="width: 90%;" type="file" /><br />
		<input type="submit" value=" 上传 " />
		</form>
		
		<form action="phpApps/truckFileUploadAction.php" method="post" enctype="multipart/form-data" onsubmit="return check_truckFields();">
		<label for="truckFile">上传发车Excel表格：</label><br />
		<input name="truckFile" id="truckFile" size="38" style="width: 90%;" type="file" /><br />
		<input type="submit" value=" 上传 " />
		</form>
		
		<form action="phpApps/shipFileUploadAction.php" method="post" enctype="multipart/form-data" onsubmit="return check_shipFields();">
		<label for="shipFile">上传发船Excel表格：</label><br />
		<input name="shipFile" id="shipFile" size="38" style="width: 90%;" type="file" /><br />
		<input type="submit" value=" 上传 " />
		</form>
		</div>-->

<div><a
	style="color: white; display: block; float: right; background-color: blue"
	href="index.html"><--回到主页</a><br />
<a
	style="color: white; display: block; float: right; background-color: blue"
	href="excelUpload.html"><--继续上传</a></div>

<div style="clear: both"></div>
</div>
</body>
</html>