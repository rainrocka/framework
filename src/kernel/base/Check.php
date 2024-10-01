<?php
namespace Rainrock\Framework\kernel\base;

class Check{
	
	/**
	*	是否为邮箱
	*/
	public static function isemail($str)
	{
		if(!$str)return false;
		return filter_var($str, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	*	是否为手机号
	*/
	public static function ismobile($str)
	{
		if(!$str)return false;
		if(!is_numeric($str) || strlen($str)<5)return false;
		return true;
	}
	
	/**
	*	判断是否为国内手机号
	*/
	public static function iscnmobile($str)
	{
		if(!$str)return false;
		if(!is_numeric($str) || strlen($str)!=11)return false;
		if(!preg_match("/1[3458769]{1}\d{9}$/", $str))return false;
		return true;
	}
	
	/**
	*	是否有中文
	*/
	public static function isincn($str)
	{
		return preg_match("/[\x7f-\xff]/", $str);
	}
	
	/**
	* 是否整个的英文a-z,0-9
	*/
	public static function iszgen($str)
	{
		if(!$str)return false;
		if(self::isincn($str)){
			return false;
		}
		return true;
	}
	
	//返回字符串编码
	public static function getencode($str)
	{
		$encode = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		$encode = strtolower($encode);
		return $encode;
	}
	
	/**
	*	是否为数字
	*/
	public static function isnumber($str)
	{
		if(!$str)return false;
		return is_numeric($str);
	}
	
	/**
	*	字符是否包含数字
	*/
	public static function isinnumber($str)
	{
		return preg_match("/[0-9]/", $str);
	}
	
	/**
	*	是否为日期
	*/
	public static function isdate($str)
	{
		return preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $str);
	}
	
	/**
	*	是否为日期时间
	*/
	public static function isdatetime($str)
	{
		return preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $str);
	}
	
	/**
	*	是否为月份
	*/
	public static function ismonth($str)
	{
		return preg_match("/^([0-9]{4})-([0-9]{2})$/", $str);
	}
	
	/**
	*	过滤字母,只留数字
	*/
	public static function onlynumber($str)
	{
		return preg_replace('/[a-zA-Z]/','', $str);
	}
	
	/**
	*	替换空格
	*/
	public static function replacekg($str)
	{
		$str 	= preg_replace('/\s*/', '', $str);
		$qian	= array(" ","　","\t","\n","\r");
		return str_replace($qian, '', $str); 
	}
	
	/**
	* 过滤特殊符号
	*/
	public static function removeEmojiChar($str)
	{
		$mbLen  = mb_strlen($str);
		$strArr = array();
		for ($i = 0; $i < $mbLen; $i++) {
			$mbSubstr = mb_substr($str, $i, 1, 'utf-8');
			if (strlen($mbSubstr) >= 4) {
				continue;
			}
			$strArr[] = $mbSubstr;
		}
		return implode('', $strArr);
	}
	
	/**
	* 是否有特殊符号
	*/
	public static function isteshu($str)
	{
		$bobg = preg_replace("/[a-zA-Z0-9_]/",'', $str);
		return $bobg;
	}
}