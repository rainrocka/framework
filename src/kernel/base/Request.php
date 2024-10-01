<?php
namespace Rainrock\Framework\kernel\base;

class Request{
	
	/**
	*	特殊字符过滤
	*/
	public static function xssrepstr($str)
	{
		$xpd  = explode(',','(,), ,	,<,>,\\,*,&,%,$,^,[,],{,},@,#,",+,?,;\'');
		$xpd[]= "\n";
		return str_ireplace($xpd, '', $str);
	}
	
	/**
	*	get的获取
	*/
	public static function get($key, $dev='')
	{
		$val = '';
		if(isset($_GET[$key]))$val = self::xssrepstr($_GET[$key]);
		if(Rock::isempt($val))$val = $dev;
		return $val;
	}
	
	/**
	*	过滤字母,只留数字
	*/
	public static function onlynumber($str)
	{
		return preg_replace('/[a-zA-Z]/','', $str);
	}
	
	/**
	*	获取数字,多个
	*/
	public static function getints($key)
	{
		$val = self::get($key);
		if($val)$val = self::onlynumber($val);
		return $val;
	}
	
	/**
	*	get的获取
	*/
	public static function getint($key)
	{
		$val = self::get($key, '0');
		return (int)$val;
	}
	
	
	/**
	*	post的获取
	*/
	public static function post($key, $dev='')
	{
		$val = '';
		if(isset($_POST[$key]))$val = $_POST[$key];
		if(Rock::isempt($val))$val = $dev;
		return $val;
	}
	
	/**
	*	post的获取数字
	*/
	public static function postint($key)
	{
		$val = self::post($key, '0');
		return (int)$val;
	}
	
	/**
	*	post的获取数字多,分开
	*/
	public static function postints($key)
	{
		$val = self::post($key);
		if($val){
			$val = self::xssrepstr($val);
			$val = self::onlynumber($val);
		}
		return $val;
	}
	
	/**
	*	postdata
	*/
	public static function postdata()
	{
		$val = '';
		if(isset($GLOBALS['HTTP_RAW_POST_DATA']))$val = $GLOBALS['HTTP_RAW_POST_DATA'];
		if($val=='')$val = trim(file_get_contents('php://input'));
		return $val;
	}
	
	/*
	*	获取客户端IP
	*/
	public static function getIp()
	{
		$ip = '';
		if(isset($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else if(isset($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$ip= self::xssrepstr($ip);
		if(!$ip)$ip = 'unknow';
		return $ip;
	}
	
	/**
	*	小数点位数
	*/
	public static function number($num,$w=2)
	{
		if(!$num)$num='0';
		return number_format($num,$w,'.','');
	}
	
	/**
	*	获取命令的参数
	*/
	public static function param($key, $dev='')
	{
		if(PHP_SAPI=='cli'){
			$argv 	= $_SERVER['argv'];
			$len 	= count($argv);
			$val 	= '';
			if($len > 2)for($i=2;$i<$len;$i++){
				$str = $argv[$i];
				if($str){
					$arr = explode('=',$str);
					if($arr[0]==$key){
						if(isset($arr[1]))$val = self::xssrepstr($arr[1]);
						break;
					}
				}
			}
			if(Rock::isempt($val))$val = $dev;
			return $val;
		}else{
			return self::get($key, $dev);
		}
	}
}