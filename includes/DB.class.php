<?php

require_once('DB.conf.php');
require_once('exceptions/DBExceptions.class.php');
/**
 * 封装了一个数据库访问的单件类
 * 其中封装的是：
 * 1．	单件模式，保证数据库的连接只有一个
 * 2．	数据库的连接
 * 3．	数据库的错误处理
 * 4．	提供数据库的无关性支持（要求SQL语句是标准的）
 *
 * （4 未实现） 
 */

class DB{
	/**
	 * 数据库连接对象,如果连接的是mysql，那么就是mysqli对象
	 *
	 * @var mysqli
	 */
	private static $dbHandler;
	
	/**
	 * 数据库查询返回的结果
	 *
	 * @var mysqli_result
	 */
	private static $result = null;
	
	/**
	 * 封装数据库的连接，查询后的错误处理
	 *
	 * @param string $query
	 * @return mysqli_result
	 */
	public static function query($query){
		
		//如果数据库未连接，先打开数据库
		if(self::$dbHandler == null){
			$con = @new mysqli(hostname,username,passwd,dbname);
			if(mysqli_connect_errno()){
				throw new DBConnectException();
			}else{
				self::$dbHandler = $con;
				
				//设置字符集
				$charsetQuery = 'set names \'utf8\'';
				self::$dbHandler->query($charsetQuery);
				if(self::$dbHandler->errno){
					throw new DBQueryException($charsetQuery);
				}
			}
		}
		
		self::$result = @self::$dbHandler->query($query);
		//如果数据库查询结果出错，抛出异常
		//if(self::$result === null){
		if(self::$dbHandler->errno){
			throw new DBQueryException($query);
		}else{
			return self::$result;
		}
	}

	/**
	 * 返回最近插入的行产生的id
	 *
	 * @return int
	 */
	public static function insert_id(){
		return self::$dbHandler->insert_id;
	}
	
	/**
	 * 返回数据库连接错误的信息
	 *
	 * @return string
	 */
	public static function connect_error(){
		return mysqli_connect_error();
	}
	
	/**
	 * 返回数据库查询错误的信息
	 *
	 * @return string
	 */
	public static function error(){
		return self::$dbHandler->error;
	}
	
	/**
	 * 获得查询结果
	 *
	 * @return mysqli_result
	 */
	public static function getResult(){
		return self::$result;
	}
	
	
	/**
	 * 返回最近更新数据库影响的数据行数
	 *
	 * @return int
	 */
	public static function affected_rows(){
		return self::$dbHandler->affected_rows;
	}
	
	public static function num_rows(){
		return mysqli_num_rows(self::$result);
	}
	
	public static function beginTransaction(){
		self::query("begin;");
		//self::query('set transaction isolation level REPEATABLE READ');
	}
	
	public static function rollback(){
		self::query("rollback");
	}
	
	public static function commit(){
		self::query("commit");
	}
	
}
?>