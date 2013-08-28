<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>程序安装</title>
</head>
<body style="margin: 0 auto; width: 760px; text-align: center;">
<div
	style="font-size: 14px; border: #bbb 1px solid; margin-top: 20px; text-align: left; background-color: #eee; padding: 10px; font-family: arial">
<?php

require ('../includes/MainTableInstall.class.php');
require ('../includes/FacheTableInstall.php');
require ('../includes/FachuanTableInstall.php');
require ('../includes/ConsignmentFileTableInstall.class.php');
require ('../includes/ConsignmentTableInstall.class.php');
require ('../includes/ListFileTableInstall.class.php');
require ('../includes/ListTableInstall.class.php');
require ('../includes/LogTableInstall.class.php');
require ('../includes/MonitorFileTableInstall.class.php');
require ('../includes/MonitorTableInstall.class.php');

if (! isset ( $_POST ['password'] )) {
	?>

			<form method="POST" action="install2.php">
<table>
	<tr>
		<td><label for="username">用户名：</label></td>
		<td><input type="text" name="username" size="20" value="root" /></td>
	</tr>
	<tr>
		<td><label for="password">密码：</label></td>
		<td><input type="password" name="password" size="20" maxlength="100"
			value="" /></td>
	</tr>
	<tr>
		<td><label for="port">端口：</label></td>
		<td><input type="text" name="port" size="20" maxlength="100"
			value="3306" /></td>
	</tr>
	<tr>
		<td><label for="hostname">hostname：</label></td>
		<td><input type="text" name="hostname" size="20" maxlength="100"
			value="localhost" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="安装" /></td>
	</tr>
</table>
</form>

</div>

</body>
</html>

<?php
	die ();
}

$username = $_POST ['username'];
$password = $_POST ['password'];
$hostname = $_POST ['hostname'];
$port = $_POST ['port'];

if ($password == '13666') {
	$password = "";
} else if (empty ( $password )) {
	$password = 'wrong';
}

$db = @mysqli_connect ( $hostname, $username, $password, null, $port );
if (mysqli_connect_errno ()) {
	echo (new DBConnectException ( ));
}

$query = 'drop database if exists `sss`;';
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	throw new DBQueryException ( $query );
}

$query = 'create database `sss`;';
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	throw new DBQueryException ( $query );
}

$query = "grant select, insert, delete, update, alter, create, index, drop on `sss`.*
to admin identified by 'synchronizer';";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	throw new DBQueryException ( $query );
}

echo '数据库创建成功<br />';
echo '开始创建用表...<br />';
new MainTableInstall ( );
new FacheTableInstall ( );
new FachuanTableInstall ( );
new ConsignmentFileTableInstall();
new ConsignmentTableInstall();
new ListFileTableInstall();
new ListTableInstall();
new LogTableInstall();
new MonitorFileTableInstall();
new MonitorTableInstall();
echo '数据库安装成功，您可以正常运行程序了';

?>
</div>

</body>
</html>