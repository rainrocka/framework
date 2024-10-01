<?php
/**
*	模块字段
*/
namespace Rainrock\Framework\kernel\core;



class ModuleFields{
	
	public static function get($fields)
	{
		$obj = new ModuleFields();
		return $obj->setFields($fields);
	}
	
	private $name = '' ,$fields = '' ,$type = '',$sm = '',$dev='',$data='';
	
	private $islu = 1, $islb = 1,$isfields = 1, $len = 0;
	
	public function setFields($fields)
	{
		$this->fields = $fields;
		return $this;
	}
	
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	public function setType($type)
	{
		$type 		= strtolower($type);
		$this->type = $type;
		if($type=='datetime' || $type=='date')$this->setDev(null);
		if($type=='int')$this->setLen(11);
		return $this;
	}
	
	public function setLen($len)
	{
		$this->len = $len;
		return $this;
	}
	
	public function setDev($dev)
	{
		$this->dev = $dev;
		return $this;
	}
	
	public function setSm($sm)
	{
		$this->sm = $sm;
		return $this;
	}
	
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}
	
	public function setIslu($islu)
	{
		$this->islu = $islu;
		return $this;
	}
	
	public function setIslb($islb)
	{
		$this->islb = $islb;
		return $this;
	}
	
	public function setIsfields($isfields)
	{
		$this->isfields = $isfields;
		return $this;
	}
	
	public function getFields()
	{
		$dbtype = $this->type;
		if($this->len > 0)$dbtype .= '('.$this->len.')';
		return array(
			'name' 	=> $this->name,
			'fields' => $this->fields,
			'type' 	=> $this->type,
			'dbtype'=> $dbtype,
			'len' 	=> $this->len,
			'sm' 	=> $this->sm,
			'dev' 	=> $this->dev,
			'data' 	=> $this->data,
			'islu' 	=> $this->islu,
			'islb' 	=> $this->islb,
			'isfields' 	=> $this->isfields,
		);
	}
}