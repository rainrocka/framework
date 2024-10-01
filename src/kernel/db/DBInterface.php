<?php
namespace Rainrock\Framework\kernel\db;

interface DBInterface{
	

	public function getone($table, $where);
	
	public function getall($table, $where);

	public function close();
	
	public function error();
	
	public function query($sql);
	
	public function insert($table,$cont);
	
	public function update($table,$cont, $where);
	
	public function delete($table, $where);
}