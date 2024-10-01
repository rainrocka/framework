<?php
namespace Rainrock\Framework\kernel\base;

class Session{
	
	/**
	*	获取
	*/
	public static function get($key, $dev='')
	{
		$val = '';
		$key = QOM.$key;
		if(isset($_SESSION[$key]))$val = $_SESSION[$key];
		if(Rock::isempt($val))$val = $dev;
		return $val;
	}
	
	
	/**
	*	设置
	*/
	public static function set($key, $val)
	{
		$key = QOM.$key;
		$_SESSION[$key] = $val;
	}
	
	/**
	*	移除
	*/
	public static function reomve($key)
	{
		self::set($key, '');
	}
}