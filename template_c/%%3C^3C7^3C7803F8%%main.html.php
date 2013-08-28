<?php /* Smarty version 2.6.26, created on 2010-10-25 06:36:58
         compiled from main.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>查询结果</title>
		<link type="text/css" rel="stylesheet" href="../css/default.css" />
		<script type="text/javascript" language="Javascript">

        function getWinHeight() //函数：获取尺寸
        {
        	var winHeight = 0;
             /*//获取窗口高度
             if (window.innerHeight)
                   winHeight = window.innerHeight;
             else if ((document.body) && (document.body.clientHeight))
                   winHeight = document.body.clientHeight;*/
            //通过深入Document内部对body进行检测，获取窗口大小
             if (document.documentElement  && document.documentElement.clientHeight &&
                                                  document.documentElement.clientWidth)
             {
                 winHeight = document.documentElement.clientHeight;
             }
			return winHeight;
        }

        function iframeReheight(){
        	var winHeight = getWinHeight();
        	var iframe = document.getElementById('searchResult');
        	var iframeHeight = winHeight - 180;
        	var heightAttr = iframeHeight + "px";
        	iframe.setAttribute('height', heightAttr);
        }

        window.onload = iframeReheight;
		window.onresize = iframeReheight;
		</script>
	</head>
	<body style="text-align:left;">
		<div id="sbar" style="width:100%; padding: 0px 0px 4px 0px; border-bottom:1px solid #c9d7f1;">
			<span style="float:right;">状态导出：<a href="exportWeidongTable.php">未动</a>
			 | <a href="exportKuzhongTable.php">库中</a>
			 | <a href="exportUnfinishedTable.php">未完成</a>
			 | <a href="exportCheckoutTable.php?type=unsettled">完成未结算</a>
			 | <a href="exportCheckoutTable.php?type=settled">完成已结算</a></span>
			<span><a href="excelUpload.html">表格上传</a></span>
			| <span><a href="index.html">回到首页</a></span>
		</div>

		<div id="searchArea">
			<a href="index.html" title="回到首页" style="border:none"><img id="logo" src="../img/SSS(small).png" height="80px;" style="border:none;" align="left" /></a>
			<div>
			<br />
			<form action="search.php" method="GET">
			<input type="text" autocomplete="off" maxlength=2048 id="keyname" name="keyname" size=45 title="输入要查询的关键字" value="<?php echo $this->_tpl_vars['keyname']; ?>
" style="padding:3px;">
			<input name=btnS type=submit value="SSS 查询" style="padding:3px 5px 3px 5px; margin:3px;" />
			<a href="advancedSearch.html">高级搜索</a>
			| <a href="search.php?stype=filestat&keyname=物流状况">物流状况</a>
	 		| <a href="search.php?stype=jiapian">加片</a>
	 		| <a href="search.php?stype=biangang">扁钢/舾装</a>
			| <a href="search.php?stype=chineseChars">汉字材料代码</a>
			| <a href="summary.php">汇总表格生成</a>
			
			<br />
								<input type="radio" value="materialCode" name="stype" <?php if ($this->_tpl_vars['materialCodeCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按材料代码</label>
<!--
								<input type="radio" value="faNumber" name="stype" <?php if ($this->_tpl_vars['faNumberCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按车号/船号</label>

								<input type="radio" value="shipsClassification" name="stype" <?php if ($this->_tpl_vars['shipsClassificationCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按船级</label>

								<input type="radio" value="material" name="stype" <?php if ($this->_tpl_vars['materialCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按材质</label>

								<input type="radio" value="size" name="stype" <?php if ($this->_tpl_vars['sizeCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按尺寸</label>

								<input type="radio" value="faDate" name="stype" <?php if ($this->_tpl_vars['faDateCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按发车/发船时间</label>

								<input type="radio" value="uploadTime" name="stype" <?php if ($this->_tpl_vars['uploadTimeCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按上传时间</label>
-->
								<input type="radio" value="filename" name="stype" <?php if ($this->_tpl_vars['filenameCheck']): ?>checked<?php endif; ?> />
								<label for="stype">按上传文件名</label>
			</form>
			</div>
		</div>
		<div id="shoulder">
		<span class="pageComment" style="display:none">
			约有<?php echo $this->_tpl_vars['rowNumber']; ?>
项，当前是<?php echo $this->_tpl_vars['start']; ?>
-<?php echo $this->_tpl_vars['end']; ?>
项
		</span>
		<?php echo $this->_tpl_vars['searchType']; ?>

		</div>

		<!-- begin the table -->
		<iframe scrolling="auto" id="searchResult" style="margin:auto; width:98%; border:3px #cbdced solid; margin-top:20px;" src=
		"<?php if ($this->_tpl_vars['simple']): ?>
		simpleTable.php?stype=<?php echo $this->_tpl_vars['stype']; ?>
&keyname=<?php echo $this->_tpl_vars['keyname']; ?>

		<?php elseif ($this->_tpl_vars['filestat']): ?>
		fileStatSearch.php
		<?php elseif ($this->_tpl_vars['jiapianStats']): ?>
		jiapianStats.php
		<?php elseif ($this->_tpl_vars['jiapianDetail']): ?>
		jiapianStats.php?orderNumber=<?php echo $this->_tpl_vars['orderNumber']; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>

		<?php elseif ($this->_tpl_vars['biangangStats']): ?>
		biangangStats.php
		<?php elseif ($this->_tpl_vars['chineseCharsStats']): ?>
		chineseCharsStats.php
		<?php elseif ($this->_tpl_vars['chineseCharsDetail']): ?>
		chineseCharsStats.php?materialCode=<?php echo $this->_tpl_vars['materialCode']; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber']; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>

		<?php elseif ($this->_tpl_vars['haveOrderNumber']): ?>
		multipleTable.php?stype=<?php echo $this->_tpl_vars['stype']; ?>
&keyname=<?php echo $this->_tpl_vars['keyname']; ?>
&orderNumber=<?php echo $this->_tpl_vars['orderNumber']; ?>
&orderSubitemNumber=<?php echo $this->_tpl_vars['orderSubitemNumber']; ?>

		<?php else: ?>
		multipleTable.php?stype=<?php echo $this->_tpl_vars['stype']; ?>
&keyname=<?php echo $this->_tpl_vars['keyname']; ?>

		<?php endif; ?>">
		</iframe>
	</body>
</html>