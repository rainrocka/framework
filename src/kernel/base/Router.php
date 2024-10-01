<?php
namespace Rainrock\Framework\kernel\base;

class Router{
	
	private static $routerInfo;
	
	/**
	*	获取路由
	*/
	public static function get()
	{
		$uri = '';
		if(PHP_SAPI=='cli'){
			$argv = $_SERVER['argv'];
			if(isset($argv[1]))$uri = '.php/'.$argv[1].'';
		}else{
			$uri	= $_SERVER['REQUEST_URI'];
		}
		
		$path			= '';
		if(!$uri)$uri 	= '.php/index';
		$arr 			= explode('.php', $uri);
		$dir 			= '';
		$controlller 	= '';
		$action 		= '';
		$lens			= count($arr);
		
		if(isset($arr[1]))$path = $arr[1];
		if($path){
			$arr 	= explode('?', $path);
			$path 	= $arr[0];
		}
		if(Rock::isempt($path) || $path=='/'){
			$path = '/'.Request::get('d').'/'.Request::get('m').'/'.Request::get('a').'';
		}
		if(Rock::isempt($path))$path = '/index/index';
		
		$arr = explode('/', strtolower($path));
		$lens			= count($arr);
		$controlller = $arr[1];
		if($lens> 2 )$action = $arr[2];
		if($lens> 3){
			$dir = $arr[1];
			$controlller = $arr[2];
			$action = $arr[3];
		}
		if(Check::isteshu($dir))$dir = '';
		if(Check::isteshu($controlller))$controlller = '';
		if(Check::isteshu($action))$action = '';
		
		if(!$controlller)$controlller = 'index';
		if(!$action)$action = 'index';
		
		$barr = array(
			'dir' 			=> $dir,
			'controlller'	=> $controlller,
			'action'	 	=> $action,
		);
		self::$routerInfo = $barr;
		return $barr;
	}
	
	/**
	*	路由信息
	*/
	public static function info()
	{
		return self::$routerInfo;
	}
}