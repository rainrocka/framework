<?php
/**
*	模块信息
*/
namespace Rainrock\Framework\kernel\core;



class Module{
	
	/**
	*	获取模块
	*/
	public static function get($num,$group='')
	{
		return Aock::getModule($num, $group);
	}
	
}