<?php
namespace Rainrock\Framework\kernel\db;


use Rainrock\Framework\kernel\base\Rock;
use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\CLog;
class DBConn implements DBInterface{
	
	protected $conn			= null;
	protected $createbool	= false;
	protected $linkbool		= false;
	protected $dbbase,$dbhost,$dbuser,$dbpass,$dbencode,$dbperfix,$dbtype = 'mysql';
	
	public $nowSql			= '';
	public $isError			= false;
	private $msgerror		= '';
	private $msgerrorall	= '';
	
	public function __construct()
	{
		$this->dbbase 	= Base::getConfig('db_base');
		$this->dbhost 	= Base::getConfig('db_host');
		$this->dbuser 	= Base::getConfig('db_user');
		$this->dbpass 	= Base::getConfig('db_pass');
		$this->dbencode = Base::getConfig('db_encode');
		$this->dbperfix = Base::getConfig('db_perfix');
	}
	
	/**
	*	数据库创建数据库
	*/
	protected function createDatabase($emsg, $obase)
	{
		$this->createbool = true;
		$xybae= 'information_schema';
		$base = $this->dbbase;
		if(Rock::contain($emsg, "Unknown database '{$base}'") && $base && $this->dbuser=='root' && $base != $xybae){
			$sql = "CREATE DATABASE `{$base}` DEFAULT CHARACTER SET {$this->dbencode} COLLATE {$this->dbencode}_general_ci;";
			$ndb = DB::createDb();
			$ndb->setConfig(array('base' => $xybae));
			$bo = $ndb->query($sql);
			if($bo){
				CLog::show("create database `{$base}` success");
				$this->connect();
			}
			return false;
		}else{
			return true;
		}
	}
	
	/**
	*	设置信息
	*/
	public function setConfig($arr)
	{
		if(isset($arr['base']))$this->dbbase = $arr['base'];
	}
	
	public function connect()
	{
		$this->linkbool = true;
	}


	/**
	*	执行sql
	*/
	public function query($sql){
		if($this->conn == null && !$this->linkbool)$this->connect();
		if($this->conn == null && $this->linkbool){CLog::error('无法连接数据库');exit;}
		$this->nowSql = $sql;
	}
	
	/**
	*	获取自增id
	*/
	public function insert_id(){return 0;}
	
	/**
	*	关闭数据库
	*/
	public function close()
	{
		if($this->isError){
			CLog::createLog($this->msgerrorall, ''.$this->dbtype.'_error_');
		}
	}
	
	protected function fetch_array($result, $type = 0){return false;}
	
	/**
	*	数据库类型
	*/
	public function getDbtype()
	{
		return $this->dbtype;
	}
	
	/**
	*	表名
	*/
	public function getDbperfix($tab='')
	{
		$str = $this->dbperfix;
		if($tab)$str.=$tab;
		return $str;
	}
	
	/**
	*	数据库编码类型
	*/
	public function getDbencode()
	{
		return $this->dbencode;
	}
	
	/**
	*	table加`
	*/
	public function chuliTable($str){
		$str = str_replace('[Q]', $this->dbperfix, $str);
		if(!Rock::contain($str, ' ') && !Rock::contain($str, '`'))$str = '`'.$str.'`';
		return $str;
	}
	
	/**
	*	字段处理
	*/
	public function chuliFields($str){
		if(!$str || $str=='*')return $str;
        if(Rock::contain($str, ',') && !Rock::contain($str, '`')){
			$arr 	= explode(',', $str);
			$nstr	= '';
			foreach($arr as $st1){
				$nstr .=',`'.$st1.'`';
			}
            $str 	= substr($nstr, 1);
        }
		return $str;
	}
	
	/**
	*	创建sql
	*/
	public function createSql($arr)
	{
		$where 	= $table = $order =$group = '';
		$limit  = $page = 0;$fields	= '*';
		if(isset($arr['table']))$table=$arr['table'];
		if(isset($arr['where']))$where=$arr['where'];
		if(isset($arr['order']))$order=$arr['order'];
		if(isset($arr['limit']))$limit=(int)$arr['limit'];
		if(isset($arr['page']))$page =(int)$arr['page'];
		if(isset($arr['group']))$group=$arr['group'];
		if(isset($arr['fields']))$fields=$arr['fields'];
		$table	= $this->chuliTable($table);
		$fields	= $this->chuliFields($fields);
		$sql	= "SELECT $fields FROM $table";
		if($where)$sql.=" WHERE $where";
		if($order)$sql.=" ORDER BY $order";
		if($group)$sql.=" GROUP BY $group";
		if($page > 0 && $limit > 0){
			$sql.=" LIMIT ".($limit * ($page-1)).",$limit";
		}else if($limit > 0){
			$sql.=" LIMIT $limit";
		}
		return $sql;
	}
	
	
	
	/**
	*	获取一行
	*/
	public function getone($table, $where, $fields='*', $order='', $group=''){
		
		$sql 	= $this->createSql(array(
			'table' => $table,
			'where' => $where,
			'fields' => $fields,
			'order' => $order,
			'group' => $group,
			'limit' => 1
		));
		$result = $this->query($sql);
		$rows	= false;
		if($result)while($row=$this->fetch_array($result)){
			$rows	= $row;
			break;
		}
		return $rows;
	}
	
	/**
	*	获取所有
	*/
	public function getarr($arr, $call=null)
	{
		$sql = $this->createSql($arr);
		return $this->getalls($sql, $call);
	}
	
	/**
	*	获取所有
	*/
	public function getall($table, $where, $fields='*', $order='', $limit = 0, $page=0, $group='',$call=null){
		
		$sql 	= $this->createSql(array(
			'table' => $table,
			'where' => $where,
			'fields' => $fields,
			'order' => $order,
			'group' => $group,
			'limit' => $limit,
			'page'  => $page
		));
		return $this->getalls($sql,$call);
	}
	
	/**
	*	获取所有sql的
	*/
	public function getalls($sql, $call=null)
	{
		$result = $this->query($sql);
		$rows	= array();
		if($result)while($row=$this->fetch_array($result)){
			if($call !=null)$row = $call($row);
			$rows[]	= $row;
		}
		return $rows;
	}
	
	/**
	*	设置错误信息
	*/
	public function setError($str, $sql){
		if(!$str)return;
		$this->isError   = true;
		$this->msgerror .= ''.$str.';';
		$this->msgerrorall .= ''.$sql.chr(10).$str.chr(10).chr(10).'';
	}
	
	/**
	*	获取错误信息
	*/
	public function error(){
		return $this->msgerror;
	}
	
	/**
	*	转换数据库可插入的对象
	*/	
	public function toaddval($str)
	{
		$adstr="'$str'";
		if($str===null){
			$adstr='null';
		}else{
			if(substr($str,0,4)=='(&;)')$adstr=substr($str,4);
		}
		return $adstr;
	}
	
	/**
	*	内容处理
	*/
	private function chuliCont($array){
		$cont	= '';
		if(is_array($array)){
			foreach($array as $k=>$v)$cont.=",`$k`=".$this->toaddval($v)."";
			$cont = substr($cont,1);
		}else{
			$cont = $array;
		}
		return $cont;
	}
	
	/**
	*	条件处理
	*/
	public function chuliWhere($str)
	{
		if(is_numeric($str))$str = '`id`='.$str.'';
		return $str;
	}
	
	/**
	*	添加
	*/
	public function insert($table, $cont)
	{
		if(!$cont)return false;
		if(is_array($cont)){
			$fields = '';$values = '';
			foreach($cont as $k=>$v){$fields.=',`'.$k.'`';$values.=",".$this->toaddval($v)."";}
			$sql = "INSERT INTO ".$this->chuliTable($table)." (".substr($fields, 1).") VALUES (".substr($values, 1).")";
		}else{
			$sql = "INSERT INTO ".$this->chuliTable($table)." SET ".$cont."";
		}
		$bool= $this->query($sql);
		if(!$bool)return false;
		return $this->insert_id();
	}
	
	/**
	*	原生的添加
	*/
	public function inserts($table, $names, $values, $sel=false)
	{
		$sql="INSERT INTO ".$this->chuliTable($table)." ($names) ";
		if(!$sel){
			$sql.="VALUES($values)";
		}else{
			$sql.=$values;
		}
		$bool= $this->query($sql);
		if(!$bool)return false;
		return $this->insert_id();
	}
	
	/**
	*	更新
	*/
	public function update($table,$cont, $where)
	{
		$cont = $this->chuliCont($cont);
		$sql = "UPDATE ".$this->chuliTable($table)." SET ".$cont." WHERE ".$this->chuliWhere($where)."";
		return $this->query($sql);
	}
	
	/**
	*	删除
	*/
	public function delete($table, $where)
	{
		$sql = 'DELETE FROM '.$this->chuliTable($table).' WHERE '.$this->chuliWhere($where).'';
		return $this->query($sql);
	}
	
	/**
	*	获取所有表
	*/
	public function getAlltable($base='')
	{
		if(!$base)$base = $this->dbbase;
		$sql 	= "SELECT `TABLE_NAME` FROM information_schema.`TABLES` WHERE `TABLE_SCHEMA`='$base'";
		return $this->getalls($sql, function($row){
			return $row['TABLE_NAME'];
		});
	}
	
	/**
	*	获取所有字段
	*/
	public function getAllFields($table, $base='')
	{
		if(!$base)$base = $this->dbbase;
		$sql 	= "SHOW COLUMNS FROM {$base}.`{$table}`;";
		return $this->getalls($sql, function($row){
			$len  = 0;$dbtype = strtolower($row['Type']);
			$arrs = explode('(',$dbtype);
			$type = $arrs[0]; 
			if(isset($arrs[1]))$len = (int)str_replace(')','', $arrs[1]);
			return array(
				'name' 	 => '',
				'fields' => $row['Field'],
				'dev'	 => $row['Default'],
				'dbtype' => $dbtype,
				'type'   => $type,
				'len'    => $len,
			);
		});
	}
	
	/**
	*	获取总数
	*/
	public function getCount($table, $where)
	{
		$count = 0;
		$rs = $this->getone($table, $where, 'count(1) as `total`');
		if($rs)$count = (int)$rs['total'];
		return $count;
	}
}