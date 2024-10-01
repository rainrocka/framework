<?php
namespace Rainrock\Framework\kernel\db;

use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\CLog;
class DBMysqli extends DBConn{


	public function connect()
	{
		parent::connect();
		if(!class_exists('mysqli'))die('操作数据库的php的扩展mysqli不存在');
		try {
			$this->conn 	= @new \mysqli($this->dbhost,$this->dbuser, $this->dbpass, $this->dbbase);
		}catch(\RuntimeException $e){}
		if (mysqli_connect_errno()){
			$this->conn 	= null;
			$errs = mysqli_connect_error();
			$bool = true;
			if(!$this->createbool)$bool = $this->createDatabase($errs, $this->dbbase);
			if($bool){
				CLog::error('mysqli:'.$errs);
				exit();
			}
		}else{
			$this->query("SET NAMES '$this->dbencode'");
		}
		
	}
	
	public function insert_id()
	{
		return $this->conn->insert_id;
	}
	
	protected function fetch_array($result, $type = 0)
	{
		$result_type = ($type==0)?MYSQLI_ASSOC:MYSQLI_NUM;
		return $result->fetch_array($result_type);
	}
	
	public function query($sql){
		parent::query($sql);
		if(!$this->conn)return false;
		$roboll = $this->conn->query($sql);
		if(!$roboll)$this->setError($this->conn->error, $sql);
		return $roboll;
	}
	
	public function close(){
		parent::close();
		if($this->conn)$this->conn->close();
		$this->conn = null;
	}
}