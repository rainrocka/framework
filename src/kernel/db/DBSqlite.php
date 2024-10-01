<?php
namespace Rainrock\Framework\kernel\db;

use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\Rock;
class DBSqlite extends DBConn{
	
	protected $dbtype = 'sqlite'; 

	public function connect()
	{
		if(!class_exists('PDO'))die('操作数据库的php的扩展PDO不存在');
		try {
			$path = $this->createDatabase('','');
			$this->conn = @new \PDO('sqlite:'.$path.'');
		} catch (\PDOException $e) {
			$this->conn 	= null;
			exit($e->getMessage());
		}
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$this->query("SET NAMES '$this->dbencode'");
	}
	
	/**
	*	数据库创建数据库
	*/
	protected function createDatabase($emsg, $obase)
	{
		$this->createbool = true;
		$paths= ''.PACKPATH.'/database/'.$this->dbbase.'.db';
		$path = ''.ROOT_PATM.'/'.$paths.'';
		if(!file_exists($path))Rock::createFile($paths,'');
		if(!file_exists($path))die('无法创建sqlite数据库文件:'.$paths.'');
		return $path;
	}
	
	public function query($sql){
		parent::query($sql);
		if(!$this->conn)return false;
		try {
			$roboll = $this->conn->query($sql);
		} catch (\PDOException $e) {
			$roboll = false;
			$this->setError($e->getMessage(), $sql);
		}
		return $roboll;
	}
	
	protected function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)? \PDO::FETCH_ASSOC : \PDO::FETCH_NUM;
		return $result->fetch($result_type);
	}
	
	public function close()
	{
		parent::close();
		$this->conn = null;
	}
	
	public function insert_id()
	{
		return $this->conn->lastInsertId();
	}
	
	
	/**
	*	获取所有表
	*/
	public function getAlltable($base='')
	{
		$sql 	= "SELECT `name` FROM `sqlite_master` WHERE `type`='table'";
		return $this->getalls($sql, function($row){
			return $row['name'];
		});
	}
	
	/**
	*	获取所有字段
	*/
	public function getAllFields($table, $base='')
	{
		$sql 	= "PRAGMA table_info('$table')";
		return $this->getalls($sql, function($row){
			$dev = $row['dflt_value'];
			if(!$dev && !is_numeric($dev))$dev='';
			if($dev=='NULL')$dev = NULL;
			return array(
				'name' 	=> '',
				'fields'=> $row['name'],
				'dev'	=> $dev,
				'dbtype'=> $row['type'],
			);
		});
	}
}