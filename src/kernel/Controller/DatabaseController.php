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
*	数据库操作
*/
class DatabaseController extends Controller{
	
	public function index()
	{
		$str   = 'php index.php database/create [create database '.Base::getConfig('db_base').']';
		CLog::back($str);
		$str   = 'php index.php database/update num=all [installupdate database]';
		CLog::back($str);
	}
	
	/**
	*	更新或者创建数据库
	*/
	public function update()
	{
		$num	= Request::param('num');
		$group	= Request::param('group');
		if(!$num && !$group)return 'plase input params(num/group)';
			
		CLog::show('--- updatedb start ---');
		$count 		= 0;
		$db	  		= DB::createDb();
		$alltable 	= $db->getAlltable();
		if($num == 'all' || $group){
			$files		= File::getFolder(''.ROOT_PATM.'/'.PACKPATH.'/Module', function($f,$p){
				$slu = ''.$p.'/'.$f.'';
				$alls = File::getFolder($slu);
				return array(
					$f => $alls
				);
			});
			foreach($files as $groups)foreach($groups as $gs=>$als){
				if(!$group || ($group && Rock::contain(','.$group.',', ','.$gs.','))){
					foreach($als as $nu1){
						$info = Aock::getModule($nu1, $gs);
						if($info){
							$info->updateTable($db, $alltable);
							$count++;
						}
					}
				}
			}
		}
		if($num && $num != 'all'){
			$numa = explode(',', $num);
			foreach($numa as $nu1){
				$info = Aock::getModule($nu1);
				if($info){
					$info->updateTable($db, $alltable);
					$count++;
				}
			}
		}
		CLog::show('--- updatedb end tablecount('.$count.') ---');
	}
	
	/**
	*	创建数据库
	*/
	public function create()
	{
		
	}
}