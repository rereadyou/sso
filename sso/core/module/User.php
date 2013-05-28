<?php 

namespace sso\core\module;

use sso\core\config as sc;


class User
{
	public $mc = null;
	
	public $uid = '';
	public $uemail = '';
	
	
	public function __construct($uid)
	{
		
		$this->uid = $uid;
		
		$this->mc = new \Memcache();
		$this->mc->connect(sc\Config::$memServerIP, sc\Config::$memPort) or die("memcache server connect failed!");
	}
	
	public function __destruct()
	{
		$this->mc->close();
	}
	/**
	 * get_user_login_status
	 *
	 * 用户登录状态查询
	 * 用户登录状态需要由用户登录ticket直接进行查询
	 * 首先根据用户id查询出用户登录ticket，再根据ticket查询用户登录信息
	 * 
	 * @param unknown_type $uid
	 * @return boolean
	 */
	public function get_user_login_status()
	{
		if(!$this->uid)
			return false;
		$this->uticket = $this->mc->get($this->uid);

		if(!$this->uticket)
			return false;

		return $this->mc->get($this->uticket);
	}
}


?>