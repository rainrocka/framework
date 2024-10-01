<?php
/**
*	数据Model
*/
namespace Rainrock\Framework\kernel\core;


use Rainrock\Framework\kernel\db\DB;

class Model{
	
	public static function get($table, $db=null)
	{
		$obj = Aock::getModel($table);
		if($db === null)$db = DB::get();
		$obj->setDb($db);
		$obj->setTable($table);
		$obj->initModel();
		return $obj;
	}
	
	protected $nDb;
	protected $infoarr = array();
	private $table,$tablename;
	
	/**
	*	可重写的
	*/
	public function initModel(){}
	
	/**
	*	设置对应数据库
	*/
	public function setDb($db)
	{
		$this->nDb = $db;
	}
	
	/**
	*	设置对应表
	*/
	public function setTable($table)
	{
		$this->table 	 = $table;
		$this->tablename = $this->nDb->getDbperfix($table);
	}
	
	/**
	*	获取总数
	*/
	public function getCount($where)
	{
		return $this->nDb->getCount($this->tablename, $where);
	}
	
	public function getone($where, $fields='*', $order='', $group='')
	{
		return $this->nDb->getone($this->tablename, $where, $fields, $order, $group);
	}
	
	public function getarr($arr, $call=null)
	{
		$arr['table'] = $this->tablename;
		return $this->nDb->getarr($arr, $call);
	}
	
	public function getall($where, $fields='*', $order='', $limit = 0, $page=0, $group='',$call=null)
	{
		return $this->nDb->getall($this->tablename, $where, $fields, $order, $limit, $page, $group, $call);
	}
	
	public function insert($cont)
	{
		return $this->nDb->insert($this->tablename, $cont); 
	}
	
	public function update($cont, $where)
	{
		return $this->nDb->update($this->tablename, $cont, $where); 
	}
	
	public function delete($where)
	{
		return $this->nDb->update($this->tablename, $where); 
	}
	
	public function getinfo($id)
	{
		if(isset($this->infoarr[$id]))return $this->infoarr[$id];
		$rs = $this->getone($id);
		$this->infoarr[$id] = $rs;
		return $rs;
	}
}