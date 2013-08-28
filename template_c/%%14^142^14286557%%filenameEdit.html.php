<?php /* Smarty version 2.6.26, created on 2010-09-08 14:58:44
         compiled from filenameEdit.html */ ?>
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
		</table>


		<form action="saveFilename.php" method="POST" style="line-height:2em">
		<input type="hidden" name="oldFilename" value="<?php echo $this->_tpl_vars['filename']; ?>
"/>
		<div style="float: left; width: 5em;"><label for="filename">文件名：</label></div>
		<div style="float: left; width: 20em;"><input style="width:20em" type="text" name="filename" maxlength="255" value="<?php echo $this->_tpl_vars['filename']; ?>
" /></div>
		<div style="float: left; width: 10em;"><input type="submit" value="提交修改" style="clear:left; padding:3px 6px 3px 6px;" /></div>
		</form>
	</body>
</html>