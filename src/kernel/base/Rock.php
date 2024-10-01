<?php
namespace Rainrock\Framework\kernel\base;

class Rock{
	
	/**
	*	定义一些常用方法
	*/
	public static function isempt($str)
	{
		$bool=false;
		if( ($str==''||$str==NULL||empty($str)) && (!is_numeric($str)) )$bool=true;
		return $bool;
	}

	/**
	*	是否有包含
	*/
	public static function contain($str,$str1)
	{
		$bool = false;
		if(!self::isempt($str1) && !self::isempt($str)){
			$ad=strpos($str,$str1);
			if($ad>0||!is_bool($ad))$bool=true;
		}
		return $bool;
	}


	/**
	* 返回错误
	*/
	public static function returnerror($msg='', $code=201, $data='')
	{
		return array(
			'data' => $data,
			'msg'  => $msg,
			'code' => $code,
			'success' => false
		);
	}

	/**
	* 返回成功
	*/
	public static function returnsuccess($data='', $msg='')
	{
		return array(
			'data' => $data,
			'msg'  => $msg,
			'code' => 200,
			'success' => true
		);
	}
	
	/**
	*	创建目录
	*/
	public static function createDir($path, $oi=1)
	{
		$zpath	= explode('/', $path);
		$len    = count($zpath);
		$mkdir	= '';
		for($i=0; $i<$len-$oi; $i++){
			if($zpath[$i]){
				$mkdir.='/'.$zpath[$i].'';
				$wzdir = ROOT_PATM.''.$mkdir;
				if(!is_dir($wzdir))mkdir($wzdir);
			}
		}
	}
	
	/**
	*	写入文件
	*/
	public static function createFile($path, $cont)
	{
		self::createDir($path);
		$path	= ''.ROOT_PATM.'/'.$path.'';
		@$file	= fopen($path,'w');
		$bo 	= false;
		if($file){
			$bo = true;
			if($cont){
				if(is_array($cont))$cont = json_encode($cont);
				$bo = fwrite($file,$cont);
			}
			fclose($file);
		}
		return $bo;
	}
	
	/**
	* 返回文件大小
	*/
	public static function formatsize($size)
	{
		$arr = array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
		if($size == 0)return '0';
		$e = floor(log($size)/log(1024));
		return number_format(($size/pow(1024,floor($e))),2,'.','').' '.$arr[$e];
	}
	
	/**
	*	当前时间
	*/
	public static function now()
	{
		return date('Y-m-d H:i:s');
	}
	
	/*
	*	获取当前访问全部url
	*/
	public static function nowurl()
	{
		if(PHP_SAPI=='cli'){
			$argv = $_SERVER['argv']; $str  = 'cli';
			foreach($argv as $v)$str.=' '.$v.'';
			return $str;
		}
		if(!isset($_SERVER['HTTP_HOST']))return '';
		$qz  = 'http';
		if($_SERVER['SERVER_PORT']==443)$qz='https';
		$url = ''.$qz.'://'.$_SERVER['HTTP_HOST'];
		if(isset($_SERVER['REQUEST_URI']))$url.= $_SERVER['REQUEST_URI'];
		return $url;
	}
	
	
	
	/**
	*	获取默认值
	*/
	public static function arrvalue($arr, $key, $dev='')
	{
		$val = '';
		if(isset($arr[$key]))$val = $arr[$key];
		if(self::isempt($val))$val = $dev;
		return $val;
	}
}