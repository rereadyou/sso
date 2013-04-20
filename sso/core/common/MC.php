<?php 
namespace sso\common;

use sso\config as sc;

require 'Singleton.php';

class MC extends Singleton
{
	public $mc = '';
	
	public function __construct()
	{
		try {
			$this->mc = new \Memcache();
			$this->mc->connect(sc\SSOConfig::$memServerIP, sc\SSOConfig::$memPort);
		} catch (\Exception $e) {
			die('Try connect memcache failed: '.$e->getMessage());
		}
	}
	
	public function __destruct()
	{
		if($this->mc)
			$this->mc->close();
	}
	
	public function get($val)
	{
		return $this->mc->get($val);
	}
	
	public function delete($val)
	{
		return $this->mc->delete($val);
	}
	
	public function set($key, $val)
	{
		return $this->set($key, $val);
	}
	
	public function __call($name, $arguments)
	{
		//return $this->mc->__call($name, $arguments);
	}
}


?>