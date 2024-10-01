<?php
namespace Rainrock\Framework\kernel\base;


use Rainrock\Framework\kernel\db\DB;
class Base{
	
	private static $configInfo = array();
	
	/**
	*	开始运行
	*/
	public static function runStart($path)
	{
		$path = str_replace('\\','/', $path);
		define('ROOT_PATH',  $path); //根目录
		define('ROOT_PATM',  substr($path, 0, strrpos($path, '/')));
		$_path = ''.$path.'/config/config.php';
		$config= array(
			'debug'  	=> false,
			'title'	 	=> 'rockrain',
			'updir'  	=> 'upload',
			'qom'	 	=> 'rock_',
			'url'	 	=> '',
			'package'	=> 'app',	//主包名
			'packpath'	=> 'application',	//项目的目录
			'db_type'	=> 'mysqli',
			
			'db_host'	=> '127.0.0.1',
			'db_user'	=> 'root',
			'db_pass'	=> '666666',
			'db_base'	=> '',
			'db_perfix' => '', 
			'db_encode' => 'utf8mb4',
		);
		if(file_exists($_path)){
			$_tempconf	= require($_path);
			foreach($_tempconf as $_tkey=>$_tvs)$config[$_tkey] = $_tvs;
		}
		self::$configInfo = $config;
		define('DEBUG',  $config['debug']);
		define('UPDIR',  $config['updir']);
		define('QOM',  $config['qom']);
		define('TITLE',  $config['title']);
		define('PACKPATH',  $config['packpath']);
		define('PACKAGE',  $config['package']);
		error_reporting(DEBUG ? E_ALL : 0);
	}
	
	/**
	*	获取配置参数
	*/
	public static function getConfig($key, $dev='')
	{
		$val = $dev;
		if(isset(self::$configInfo[$key]))$val = self::$configInfo[$key];
		return $val;
	}
	
	/**
	*	运行结束
	*/
	public static function runEnd()
	{
		DB::closeDb();
	}
}