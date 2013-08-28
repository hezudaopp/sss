<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>程序安装</title>
	</head>
	<body style="margin:0 auto; width:760px; text-align:center;">
		<div style="font-size:14px; 
			border:#bbb 1px solid;
		   	margin-top:20px;
		   	text-align:left;
		 	background-color:#eee;
			padding:10px;
			font-family:arial">
<?php
  
require_once '../includes/DB.class.php';
if(!isset($_POST['password'])){
?>

			<form method="POST" action="install.php">
				<table>
					<tr>
						<td><label for="username">用户名：</label></td>
						<td><input type="text" name="username" size="20" value="root" /></td>
					</tr>
					<tr>
						<td><label for="password">密码：</label></td>
						<td><input type="password" name="password" size="20" maxlength="100" value="" /></td>
					</tr>
					<tr>
						<td><label for="port">端口：</label></td>
						<td><input type="text" name="port" size="20" maxlength="100" value="3306" /></td>
					</tr>
					<tr>
						<td><label for="hostname">hostname：</label></td>
						<td><input type="text" name="hostname" size="20" maxlength="100" value="localhost" /></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" value="安装" />
						</td>
					</tr>
				</table>
			</form>
			
		</div>
		 
	</body>
</html>

<?php
die();
}

$username = $_POST['username'];
$password = $_POST['password'];
$hostname = $_POST['hostname'];
$port = $_POST['port'];

if($password == '13666'){
	$password = "";
}else if(empty($password)){
	$password = 'wrong';
}

$db = @mysqli_connect($hostname, $username, $password, "sss", $port);
if(mysqli_connect_errno()){
	echo(new DBConnectException());
}


$query = "grant select, insert, delete, update, alter, create, index, drop on `sss`.*
to admin identified by 'synchronizer';";
@$db->query($query);
if(mysqli_errno($db)){
	throw new DBQueryException($query);
}

$query = 'ALTER TABLE sss_log ADD `content2` text NULL after `content`;';
@$db->query($query);
if(mysqli_errno($db)){
	throw new DBQueryException($query);
}

echo '数据库修改成功<br />';


?>
		 </div>
		 
	</body>
</html>