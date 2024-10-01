<?php
namespace Rainrock\Framework\kernel\base;

class CDate{
	
	/**
	*	当前时间
	*/
	public static function now()
	{
		return date('Y-m-d H:i:s');
	}
	
	/**
	*	获取毫秒
	*/
	public static function haomiao()
	{
		$mt = explode(' ', microtime());
		return (int)($mt[0] * 1000);
	}
	
}