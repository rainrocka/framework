<?php
namespace Rainrock\Framework\kernel\base;

class CLog{
	
	private static $timeci = 0;
	
	public static function show($str, $col='',$isqz=true)
	{
		self::$timeci++;
		if(is_array($str))$str = json_encode($str);
		if($col)$col = self::printColored($col);
		if($col){
			//$str = "\033[" .$col."$str\033[0m";
		}
		//$str  = iconv("UTF-8", "GB2312", $str);
		$pstr = $str;
		if($isqz)$pstr = ''.self::$timeci.'.['.PACKAGE.']'.CDate::now().'.'.CDate::haomiao().': '.$str.'';
		$br	  = (PHP_SAPI=='cli') ? PHP_EOL : '<br>';
		echo $pstr.$br;
	}
	
	public static function back($str, $col='')
	{
		self::show($str,$col, false);
	}
	
	public static function error($str)
	{
		self::show($str, 'red');
	}
	
	
	public static function printColored($col) {
		$colors = [
			'black' => '0;30',
			'red' => '0;31',
			'green' => '0;32',
			'yellow' => '0;33',
			'blue' => '0;34',
			'magenta' => '0;35',
			'cyan' => '0;36',
			'white' => '0;37',
		];
		if(isset($colors[$col]))return $colors[$col];
		return '';
	}
	
	/**
	*	创建日志文件
	*/
	public static function createLog($cont, $file='')
	{
		if(is_array($cont))$cont = json_encode($cont, JSON_UNESCAPED_UNICODE);
		$msg 	= '['.Rock::now().']:'.Rock::nowurl().''.chr(10).''.$cont.'';
		$path 	= ''.PACKPATH.'/'.UPDIR.'/logs/'.date('Ym').'/'.$file.''.time().'_'.rand(1000,9999).'.log';
		return Rock::createFile($path, $msg);
	}
}