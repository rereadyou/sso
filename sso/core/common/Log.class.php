<?php

namespace sso\core\common;
use sso\core\common\functions as sf;
use sso\core\config as sc;

class Log extends Singleton
{
	public static $log_path =  'core/logs/';
	public static $error_log = 'error.log';
	public static $access_log = 'access.log';
	
	private function __construct()
	{
		
	}

	public static function error($msg)
	{
        $ip = sf\get_client_ip();
		self::write_log('error', '['.date('D M d H:i:s Y').']'." [$ip] ".$msg);
	}
	
	public static function access($msg)
	{
        $ip = sf\get_client_ip();
		self::write_log('access', '['.date('D M d H:i:s Y').']'." [$ip] ".$msg);
	}
	
	/**
	 * static function write_log
	 * 
	 * write log to corresponding files
     *
     * @access  private
	 * @param   string  $kind
	 * @param   string  $content
	 * @author
	 */
	private static function write_log($kind, $content)
	{
		$file = self::$log_path;
        if(isset(sc\Config::$LogPath))
        {
            $file = sc\Config::$LogPath;
        }
		
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

        }
	
		try 
		{
			file_put_contents($file, $content."\r\n", FILE_APPEND);
		} 
		catch (Exception $e) 
		{
			echo 'error!';;
		}
	}

}// end of class log defination
?>
