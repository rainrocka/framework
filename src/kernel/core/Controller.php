<?php
/**
*	控制器
*/
namespace Rainrock\Framework\kernel\core;



class Controller{
	
	
	public function initController() { }
	
	/**
	*	需要登录
	*/
	public function checkLogin()
	{
		
	}
	
	public function View()
	{
		return new View();
	}
	
	public function index()
	{
		return 'index not dev';
	}
	
	public function data()
	{
		return 'data not dev';
	}
	
	public function check()
	{
		return 'data not dev';
	}
}