<?php
/**
 * SSO config file
 *
 * Version: 0.1	   Date: 2013/4/1
 * Author: zhangbo
 */
namespace sso\core\config;


class Config //extends \sso\core\common\Singleton
{
	public static $LogPath 				= "core/logs/";	
	public static $SSOURL 				= "www.sso.com";
	//public static $ticketName = "ticket";
	public static $ticketLifeSpan 		= 0;
	public static $cookTicketLifeSpan 	= 0;
	public static $cookSessName 		= "allyes_sso";
	
	//session lifespan
	public static $sessGcMaxlifetime 	= 180; //5*60s session life span
	
	public static $sessGcProbability 	= 100;
	public static $sessGcDivisor 		= 1;
	
	//keep dummy client alive for certain seconds
	//if client no action during this time, it will logined off on SSO
	public static $keepDummyClientAliveTime = 3600; 
	
	
	public static $memcacheSessionEnabled 	= false;
	public static $sessSaveHandler 		= 'memcache';
	public static $memServerAdd 		= 'tcp://127.0.0.1:11211';
	public static $memServerIP 			= '127.0.0.1';
	public static $memPort 				= '11211';
	
	
	//app domain and login page and home page
	public static $apps = array(
							'http://www.domain1.com'=>array('login'=>'login.php', 'home'=>'app.php'),
							'http://www.domain2.com'=>array('login'=>'login.php', 'home'=>'app.php'),
						);
	
	public static $clientDomains = array(
										"www.domain1.com",
										"www.domain2.com",
									);
	
	public static $services = array(
									"domain1",
									"domain2",
								);

	
	public function __construct()
	{
		//set default timezone
		date_default_timezone_set('Asia/Shanghai');
	}
	
	/**
	 * set_session_feature
	 * 
	 * Default session features
	 */
	public static function set_session_feature()
	{
		//broswer cookie name for allyes SSO
		ini_set("session.name", self::$cookSessName);
		
		//default cookie will be session lifespan
		ini_set("session.cookie_lifetime", self::$cookTicketLifeSpan);
		//every 60 seconds will do garbage collect//
		
		//set session expired(remove) time
		//for if session is handled not by default file strategy
		//that is session.save_path is set by user
		//user have to handle session cancle problem
		ini_set("session.gc_maxlifetime", self::$sessGcMaxlifetime);
			
		//note that session.gc_probability will be 100 by default
		//and session.gc_divisor will be 1 by default;
		//so it's 1% probability for session collection at every PHP calls
		ini_set("session.gc_probability", self::$sessGcProbability);
		ini_set("session.gc_divisor", self::$sessGcDivisor);
		
	}
	
	/**
	 * set_file_session_handler
	 * 
	 * File session feature
	 */
	public static function set_file_session_handler()
	{
		self::set_session_feature();
	}
	
	/**
	 * set_memcache_session_handler
	 * 
	 * Memcache session feature
	 */
	public static function enable_memcache_session_handler()
	{
		try 
		{
			self::set_session_feature();
			ini_set("session.save_handler", self::$sessSaveHandler);
			ini_set("session.save_path", self::$memServerAdd);
			self::$memcacheSessionEnabled = true;
			
		} 
		catch (\Exception $e) //Exception is in Global namespace
		{
			echo $e->getMessage();
			exit;
		}
	}
}



?>