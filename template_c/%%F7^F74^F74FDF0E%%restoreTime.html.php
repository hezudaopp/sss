<?php /* Smarty version 2.6.26, created on 2010-07-06 08:57:10
         compiled from restoreTime.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>查看滞留时间</title>
		<link type="text/css" rel="stylesheet" href="../css/default.css" />
<!--		<link type="text/css" rel="stylesheet" href="../ext2.0/resources/css/ext-all.css" />
		<link type="text/css" rel="stylesheet" href="../ext2.0/resources/css/ext-patch.css" />
		
	
		<script type="text/javascript" src="../ext2.0/adapter/ext/ext-base.js"></script>
		<script type="text/javascript" src="../ext2.0/ext-all-debug.js"></script>
		<script type="text/javascript" src="../ext2.0/source/locale/ext-lang-zh_CN.js"></script>
-->
		<style>
.comment{
color:#8A95A1;
font-weight:bold;
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
		<h1>查看滞留时间</h1><hr />
		<div class="links">
		<table style="width:100%;">
		<tr>
		<td>
		<form action="restoreTime.php" method="post" enctype="multipart/form-data">
		<?php if ($this->_tpl_vars['errMsg']): ?>
			<p style="color:red; line-height:1.5em;"><?php echo $this->_tpl_vars['errMsg']; ?>
</p>
		<?php endif; ?>
		
		<label for="fromDate">从哪天：</label>
		<input type="text" size="20" name="fromDate" value="<?php echo $this->_tpl_vars['fromDate']; ?>
"  ondblclick="fillDate(this, true)"/><!--<span class="comment">（可以空着，空着时表示所有出库中最早的那一天）</span>--><br />
		<label for="toDate">到哪天：</label>
		<input type="text" size="20" name="toDate" value="<?php echo $this->_tpl_vars['toDate']; ?>
" ondblclick="fillDate(this, false)" /><!--<span class="comment">（可以空着，空的时候就表示所有出库中最晚的那一天）</span>--><br />
		<label for="storePlace">库存地：</label>
		<input type="text" size="20" name="storePlace" value="<?php echo $this->_tpl_vars['storePlace']; ?>
" /><br />
		<label for="destination">目的地：</label>
		<input type="text" size="20" name="destination" value="<?php echo $this->_tpl_vars['destination']; ?>
" /><br />
		
		<input type="checkbox" value="1" name="export" checked>直接导出表格<br />
		<input type="submit" value=" 查看结果 " />
		</form>
</td></tr>
		</table>
		</div>
		<div><a style="color:white; display:block; float:right; background-color:blue" href="index.html" title="回到首页">-->回主页</a><br /></div>
		
		<div style="clear:both"></div>
	</div>
	</body>
</html>