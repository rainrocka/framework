<?php
namespace Rainrock\Framework\kernel\db;

use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\Rock;
use Rainrock\Framework\kernel\base\CLog;
class DBMysqlpdo extends DBConn{

	public function connect()
	{
		parent::connect();
		if(!class_exists('PDO'))die('操作数据库的php的扩展PDO不存在');
		try {
			$this->conn = @new \PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbbase.'', $this->dbuser, $this->dbpass);
		} catch (\PDOException $e) {
			$this->conn 	= null;
			$errs = $e->getMessage();
			$bool = true;
			if(!$this->createbool)$bool = $this->createDatabase($errs, $this->dbbase);
			if($bool){
				CLog::error('mysqlpdo:'.$errs);
				exit();
			}
		}
		$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->query("SET NAMES '$this->dbencode'");
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
	
	public function close(){
		parent::close();
		$this->conn = null;
	}
	
	public function insert_id()
	{
		return $this->conn->lastInsertId();
	}
}