<?php
namespace Rainrock\Framework\kernel;

use Rainrock\Framework\kernel\base\Router;
use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\Rock;
use Rainrock\Framework\kernel\core\Aock;

class Start{
	
	/**
	* 开始运行
	* @param $path 系统根路径
	*/
	public static function run($path)
	{
		Base::runStart($path);
		$barr = self::runs();
		if(is_array($barr))$barr = json_encode($barr);
		Base::runEnd();
		if(!Rock::isempt($barr))echo $barr;
	}
	
	/**
	* 运行
	*/
	private static function runs()
	{
		$router 		= Router::get();
		$dir 			= $router['dir'];
		$controlller 	= $router['controlller'];
		$action 		= $router['action'];
		$obj			= Aock::getController($dir, $controlller);
		
		if(method_exists($obj, $action)){
			$obj->initController();
			$barr = $obj->checkLogin();
			if($barr){
				if(is_array($barr) && !$barr['success'])return $barr;
				if(is_string($barr))return $barr;
			}
			$barr = $obj->$action();
		}else{
			$barr = '('.$controlller.')action('.$action.') not found';
		}
		
		return $barr;
	}
}