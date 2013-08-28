<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改船级的长度，最长限制改成10</title>
</head>
<body style="margin: 0 auto; width: 760px; text-align: center;">
<div
	style="font-size: 14px; border: #bbb 1px solid; margin-top: 20px; text-align: left; background-color: #eee; padding: 10px; font-family: arial">
<?php
require ('../includes/exceptions/DBExceptions.class.php');
require ('../includes/DB.class.php');

if (! isset ( $_POST ['password'] )) {
	?>

			<form method="POST" action="install-change-shipsClassification.php">
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
echo '开始修改船级的长度...<br />';
$query = "use sss;";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	die ( '查询语句错误：' . $query . ',错误原因：' . mysqli_error ( $db ) . '<br />' );
}

$query = "alter table sss_fache change shipsClassification shipsClassification varchar(10);";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	die ( '查询语句错误：' . $query . ',错误原因：' . mysqli_error ( $db ) . '<br />' );
}

$query = "alter table sss_fachuan change shipsClassification shipsClassification varchar(10);";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	die ( '查询语句错误：' . $query . ',错误原因：' . mysqli_error ( $db ) . '<br />' );
}

$query = "alter table sss_list change shipsClassification shipsClassification varchar(10);";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	die ( '查询语句错误：' . $query . ',错误原因：' . mysqli_error ( $db ) . '<br />' );
}

$query = "alter table sss_main change shipsClassification shipsClassification varchar(10);";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	die ( '查询语句错误：' . $query . ',错误原因：' . mysqli_error ( $db ) . '<br />' );
}

$query = "alter table sss_monitor change shipsClassification shipsClassification varchar(10);";
@$db->query ( $query );
if (mysqli_errno ( $db )) {
	die ( '查询语句错误：' . $query . ',错误原因：' . mysqli_error ( $db ) . '<br />' );
}

echo '修改完成<br />';

?>
</div>

</body>
</html>