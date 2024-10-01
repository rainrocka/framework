<?php
namespace Rainrock\Framework\kernel\base;


/**
*	文件夹
*/
class File{
	
	/**
	*	获取路径下的文件夹
	*/
	public static function getFolder($path, $call=null)
	{
		$rows = array();
		if(is_dir($path)){
			$files 		= scandir($path);
			foreach ($files as $file) {
				if ($file != "." && $file != ".."){
					if($call != null)$file = $call($file, $path);
					$rows[] = $file;
				}
			}
		}
		return $rows;
	}
}