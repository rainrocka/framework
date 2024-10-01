<?php
namespace Rainrock\Framework\kernel\Controller;


use Rainrock\Framework\kernel\core\Controller;
use Rainrock\Framework\kernel\core\Aock;
use Rainrock\Framework\kernel\base\Request;
use Rainrock\Framework\kernel\base\CLog;
use Rainrock\Framework\kernel\base\Rock;
use Rainrock\Framework\kernel\base\Base;
use Rainrock\Framework\kernel\base\File;
use Rainrock\Framework\kernel\db\DB;

/**
*	一些服务使用
*/
class ServerController extends Controller{
	
	public function index()
	{
		$str   = 'php index.php database/create [create database '.Base::getConfig('db_base').']';
		CLog::back($str);
		
		$str  = 'php index.php database/update num=all [installupdate database]';
		CLog::back($str);
		
		$str  = 'php index.php server/taskstart [start task]';
		CLog::back($str);
		
		$str  = 'php index.php server/taskstop [stop task]';
		CLog::back($str);
	}
	
	/**
	*	启动计划任务
	*/
	public function taskstart()
	{
		//$pid = getmypid();
		CLog::show('task server runing...');
	}
	
	/**
	*	停止计划任务
	*/
	public function taskstop()
	{
		//$pid = getmypid();
		CLog::show('task server stop');
	}
	
	public function test()
	{
		//for($i=0;$i<999;$i++)CLog::show('testci_'.$i.'...');
	}
}