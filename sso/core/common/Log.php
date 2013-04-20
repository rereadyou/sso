<?php

namespace sso\core\common;

use sso\core\config as sc;
/**
 * Log sigleton class
 * 
 */

class Log extends Singleton
{
	public static $log_path =  'core/logs/';
	public static $error_log = 'error.log';
	public static $access_log = 'access.log';
	
	
	private function __construct()
	{
		
	}

	static function error($msg)
	{
		self::write_log('error', "[".date('YmdHis')."]--".$msg);
		//return self;
	}
	
	static function access($msg)
	{
		//date_default_timezone_set('Asia/Shanghai');
		self::write_log('access', "[".date('YmdHis')."]--".$msg);
		//return self;
	}
	
	/**
	 * static function write_log
	 * 
	 * write log to corresponding files
	 * @param string $kind
	 * @param string $content
	 * 
	 * @author
	 */
	static function write_log($kind, $content)
	{
		$file = self::$log_path;
		
		if($kind == 'error')
		{
			$file .= self::$error_log;
		}
		else if ($kind == 'access')
		{
			$file .= self::$access_log;
		}
		else 
		{
			//	
		}
	
		try 
		{
			file_put_contents($file, $content."\r\n", FILE_APPEND);
		} 
		catch (Exception $e) 
		{
			echo 'errrrrrrrrrrrrrr';;
		}
	}

	
}// end of class log defination


?>