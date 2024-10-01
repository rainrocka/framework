<?php
/**
*	展示用的
*/
namespace Rainrock\Framework\kernel\core;


use Rainrock\Framework\kernel\base\Router;

class View{
	
	private $tplpath = '';//模版路径
	
	private $assignData = array();
	
	/**
	*	设置模版路径
	*/
	public function setPath($path)
	{
		$this->tplpath = $path;
		return $this;
	}
	
	/**
	*	设置变量
	*/
	public function assign($key, $val)
	{
		$this->assignData[$key] = $val;
		return $this;
	}
	
	/**
	*	显示模版
	*/
	public function show()
	{
		$qian = ''.ROOT_PATM.'/'.PACKPATH.'/Controller';//默认的路径
		if(!$this->tplpath){
			$router = Router::info();
			$this->tplpath = ''.$router['controlller'].'/tpl_'.$router['controlller'].'_'.$router['action'].'.php';
		}
		$_path 	= $qian.'/'.$this->tplpath;
		if(!file_exists($_path))return '(Controller/'.$this->tplpath.')view tpl not found';
		$title 	= TITLE;
		foreach($this->assignData as $_k=>$_v)$$_k = $_v;
		require_once($_path);
	}
	
}