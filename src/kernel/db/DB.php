<?php
namespace Rainrock\Framework\kernel\db;

use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\Rock;

class DB{
	
	private static $dbConn = array();
	
	/**
	*	创建数据库链接
	*/
	public static function createDb($type='')
	{
		if($type=='')$type = Base::getConfig('db_type');
		if($type=='mysqli'){
			$obj = new DBMysqli();
		}else if($type=='mysqlpdo'){
			$obj = new DBMysqlpdo();
		}else if($type=='sqlite'){
			$obj = new DBSqlite();	
		}else{
			$obj = new DBConn();
		}
		self::$dbConn[] = $obj;
		return $obj;
	}
	
	
	/**
	*	关闭数据库
	*/
	public static function closeDb()
	{
		foreach(self::$dbConn as $db)$db->close();
		self::$dbConn = array();
	}
	
	/**
	*	得到数据库对象
	*/
	public static function get()
	{
		if(!isset(self::$dbConn[0]))self::createDb();
		return self::$dbConn[0];
	}
	
	/**
	*	字段信息
	*/
	public static function fieldsString($rs, $type)
	{
		$str = "`".$rs['fields']."` ".$rs['dbtype']."";
		$val = $rs['dev'];
		if($val===null){
			$str .= " DEFAULT NULL";
		}else{
			if($type=='sqlite'){
				if(!Rock::isempt($val))$str .= " DEFAULT $val";
			}else{
				$str .= " DEFAULT '$val'";
			}
		}
		$val = $rs['name'];
		$dav = Rock::arrvalue($rs,'data');
		if($dav)$val .= '@'.$dav.'';
		if($type != 'sqlite' && $val)$str .= " COMMENT '$val'";
		return $str;
	}
}