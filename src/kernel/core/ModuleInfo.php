<?php
/**
*	模块信息
*/
namespace Rainrock\Framework\kernel\core;


use Rainrock\Framework\kernel\base\Rock;
use Rainrock\Framework\kernel\base\CLog;
use Rainrock\Framework\kernel\db\DB;

class ModuleInfo{
	
	public $num = '',$table = '',$name = '',$icons = '',$tablename='';
	
	private $fieldsArray = array();
	private $indexArray = array();
	
	protected function initModule(){}
	protected function initFirst(){}
	protected $nDb,$model;
	
	public function __construct()
	{
		$this->initModule();
	}
	
	
	public function setNum($num)
	{
		$this->num = $num;
		$this->setTable($num);
		return $this;
	}
	
	public function setTable($table)
	{
		$this->table 		= $table;
		return $this;
	}
	
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	public function setIcons($icons)
	{
		$this->icons = $icons;
		return $this;
	}
	
	/**
	*	添加索引
	* @param $type key/only/jian
	*/
	public function addIndex($type, $fields, $name='')
	{
		if(!$name)$name = $fields;
		$this->indexArray[] = array(
			'type' 	 => $type,
			'fields' => $fields,
			'name' 	 => $name,
		);
	}
	
	/**
	*	加字段
	*/
	public function addFields($obj)
	{
		$this->fieldsArray[] = $obj->getFields();
	}
	
	/**
	*	获取字段
	*/
	public function getFields()
	{
		return $this->fieldsArray;
	}
	
	/**
	*	获取模块信息
	*/
	public function getInfo()
	{
		return array(
			'num' 	=> $this->num,
			'table' => $this->table,
			'name' 	=> $this->name,
			'icons' => $this->icons,
		);
	}
	
	
	/**
	*	创建表
	*/
	public function createTable($db)
	{
		$type = $db->getDbtype();
		if($type!= 'sqlite'){
			$idkey 	= '`id` int(11) NOT NULL AUTO_INCREMENT';
			$jian   = '';
			foreach($this->indexArray as $k=>$rs)if($rs['type']=='jian')$jian.=',`'.$rs['fields'].'`';
			$okey	= ',PRIMARY KEY (`id`'.$jian.')';
			foreach($this->indexArray as $k=>$rs){
				if($rs['type']=='key')$okey.=',KEY '.$rs['name'].' ('.$rs['fields'].')';
				if($rs['type']=='only')$okey.=',UNIQUE KEY '.$rs['name'].' ('.$rs['fields'].')';
			}
		}else{
			$idkey	= '`id` INTEGER PRIMARY KEY AUTOINCREMENT';
			$okey 	= '';
		}
		foreach($this->fieldsArray as $k=>$rs){
			if($rs['isfields']==0 || $rs['fields']=='id')continue;
			$str = DB::fieldsString($rs, $type);
			$idkey .= ",\n".$str."";
		}
		$sql = "CREATE TABLE IF NOT EXISTS `{$this->tablename}` (".$idkey."".$okey."\n)";
		if($type!= 'sqlite')$sql.= "ENGINE=InnoDB DEFAULT CHARSET=".$db->getDbencode()." COMMENT='{$this->name}'";
		$db->query($sql.';');
		if($type == 'sqlite')foreach($this->indexArray as $k=>$rs){
			$sql = '';
			if($rs['type']=='key')$sql = "CREATE INDEX ".$rs['name']." on `{$this->tablename}` (".$rs['fields'].")";
			if($rs['type']=='only' || $rs['type']=='jian')$sql = "CREATE UNIQUE INDEX ".$rs['name']." on `{$this->tablename}` (".$rs['fields'].")";
			if($sql)$db->query($sql);
		}
		CLog::show('-createdb('.$this->tablename.')success-');
	}
	
	/**
	*	更新表
	*/
	public function updateTable($db, $alltable)
	{
		$this->nDb 			= $db;
		$type 				= $db->getDbtype();
		$this->tablename    = $db->getDbperfix($this->table);
		if(!in_array($this->tablename, $alltable)){
			$this->createTable($db);
		}else if($type != 'sqlite'){
			$allfields 	= $db->getAllFields($this->tablename);
			$farr		= array();
			foreach($allfields as $k=>$rs)$farr[$rs['fields']] = $rs;
			$usql		= '';
			foreach($this->fieldsArray as $k=>$rs){
				$fid  = $rs['fields'];
				if($rs['isfields']==0 || $fid=='id')continue;
				$nstr = DB::fieldsString($rs, $type);
				if(!isset($farr[$fid])){
					$usql  .= ',ADD COLUMN '.$nstr.'';
				}else{
					$ors 	= $farr[$fid];
					$ostr 	= DB::fieldsString($ors, $type);
					$ux 	= stripos($nstr, $ostr);
					if($ors['type']==$rs['type'] && $ors['len'] > 0 && $ors['len'] > $rs['len'])$ux = 1;
					if(is_bool($ux) && !$ux)$usql .= ',MODIFY COLUMN '.$nstr.'';
				}
			}
			if($usql){
				$usql = substr($usql, 1);
				$db->query('ALTER TABLE `'.$this->tablename.'` '.$usql.';');
				CLog::show($usql);
				CLog::show('-updatedb('.$this->tablename.')success-');
			}
		}
		$this->model	= Model::get($this->table, $db);
		$this->initFirst();
		if($db->isError)CLog::error($db->error());
	}
}