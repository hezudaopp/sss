<?php /* Smarty version 2.6.26, created on 2010-09-26 10:22:57
         compiled from checkoutInput.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>结算</title>
<link type="text/css" rel="stylesheet" href="../css/default.css" />
<style>
.comment {
	color: #8A95A1;
	font-weight: bold;
	font-style: italic;
}
</style>
<script type="text/javascript">
		//Ext.BLANK_IMAGE_URL = "../ext2.0/resources/images/default/s.gif";
		
		function get_nonblank(id) {
          var entry = document.getElementById(id).value;
          return entry.replace(/^\s+$/,""); //remove whitespace
        }
		
        function check_monitorUploadFields() {
          var file = get_nonblank("monitorUpload");
          if (file == "") {
            alert("请选择要上传的文件.");
            return false;
          }
          return true;
        }
        
        function fillDate(obj, lastMonth){
        	var date = new Date();
        	if(lastMonth){
        		var month = date.getMonth();
        	}else{
        		var month = date.getMonth() + 1;
        	}
        	
        	var year = date.getYear() + 1900;
        	var date = date.getDate();
        	var dateStr = year + "/" + month + "/" + date;
        	obj.attributes.getNamedItem("value").nodeValue = dateStr;
        }
		
        function noimplemented(){
        	alert("这部分功能还未完成，现在无法使用！请等下次升级后再使用");
        	return false;
        }
		</script>
</head>

<body>
<div class="wrapper">
<h1>结算</h1>
<hr />
<div class="links">
<table style="width: 100%;">
	<tr>
		<td>
		<form action="checkout.php" method="post"
			enctype="multipart/form-data"><?php if ($this->_tpl_vars['errMsg']): ?>
		<p style="color: red; line-height: 1.5em;"><?php echo $this->_tpl_vars['errMsg']; ?>
</p>
		<?php endif; ?> <label for="fromDate">从哪天：</label> <input type="text"
			size="20" name="fromDate" value="<?php echo $this->_tpl_vars['fromDate']; ?>
"
		ondblclick="fillDate(this, true)"/><br />
		<label for="toDate">到哪天：</label> <input type="text" size="20"
			name="toDate" value="<?php echo $this->_tpl_vars['toDate']; ?>
" ondblclick="fillDate(this,
		false)" /><br />
		<input type="radio" name="group" value="unsettled" checked="checked"> 未结算
		<input type="radio" name="group" value="settled"> 已结算
		<input type="radio" name="group" value="both"> 全部<br>
		<input type="submit" value=" 查看结果 " /></form>
		</td>
	</tr>
</table>
</div>
<div><a
	style="color: white; display: block; float: right; background-color: blue"
	href="index.html" title="回到首页">-->回主页</a><br />
</div>

<div style="clear: both"></div>
</div>
</body>
</html>