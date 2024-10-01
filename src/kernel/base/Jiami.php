<?php
namespace Rainrock\Framework\kernel\base;



class Jiami{
	
	private static $keystr = 'abcdefghijklmnopqrstuvwxyz';
	private static $jmsstr = '';
	
	
	public static function getRandkey()
	{
		return str_shuffle(self::$keystr);
	}
	
	public static function base64encode($str)
	{
		if(!$str)return '';
		$str	= base64_encode($str);
		$str	= str_replace(array('+', '/', '='), array('!', '.', ':'), $str);
		return $str;
	}
	
	public static function base64decode($str)
	{
		if(!$str)return '';
		$str	= str_replace(array('!', '.', ':'), array('+', '/', '='), $str);
		$str	= base64_decode($str);
		return $str;
	}
}