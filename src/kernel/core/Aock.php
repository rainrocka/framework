<?php
namespace Rainrock\Framework\kernel\core;

class Aock{
	
	
	/**
	*	获取需要的路径
	*/
	private static function getPath($dir, $controlller)
	{
		$path = '\\'.PACKAGE.'\Controller\\';
		if($dir)$path.=''.$dir.'\\';
		$path	.=''.$controlller.'\\'.ucfirst($controlller).'Controller';
		if(!class_exists($path))$path = '';
		return $path;
	}
	
	/**
	*	cli的模式的控制器
	*/
	private static function getcliPath($dir, $controlller)
	{
		$path = '\Rainrock\Framework\kernel\Controller\\';
		if($dir)$path.=''.$dir.'\\';
		$path	.=''.ucfirst($controlller).'Controller';
		if(!class_exists($path))$path = '';
		return $path;
	}
	
	/**
	*	获取控制器
	*/
	public static function getController($dir, $controlller)
	{
		$path = self::getPath($dir, $controlller);
		if(!$path)$path = self::getcliPath($dir, $controlller);
		if(!$path)$path = '\Rainrock\Framework\kernel\core\Controller';
		return new $path();
	}
	
	/**
	*	获取模块
	*/
	public static function getModule($num,$group='')
	{
		if(!$group)$group = 'system';
		$path = '\\'.PACKAGE.'\Module\\'.$group.'\\'.$num.'\ModuleInfo_'.$num.'';
		if(!class_exists($path))return false;
		return new $path();
	}
	
	/**
	*	获取模型
	*/
	public static function getModel($table)
	{
		$path = '\\'.PACKAGE.'\Model\Model'.$table.'';
		if(!class_exists($path))$path = '';
		if(!$path)$path = '\Rainrock\Framework\kernel\core\Model';
		return new $path();
	}
}