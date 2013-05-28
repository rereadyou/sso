<?php
/**
 * class SSO defination
 *
 * version:	0.1 
 * author:	zhangbo		date: 2013/3/26
 */
namespace sso\core\common;

use sso\core\common\functions\getGPC;

use sso\core\common\functions as sf;
use sso\core\config as sc;


class Allyes_SSO
{
	/**
	 * $instance
	 *
	 * singleton sso instance
	 */
	public static $instance = NULL;
	public static $SID 		= NULL;
	public $refered 		= NULL;

	public $sessionLifeTime = 0;
	public $sessTicket 		= NULL;
	public $cookTicket 		= NULL;
	public $getTicket 		= NULL;

	public $status 			= NULL;	

	private function __construct()
	{
		//default session will be expired after Broswer closed.
		$this->sessionLiftTime = 0;
	}

	/**
	 * __clone
	 *
	 * disable SSO object clone
	 */
	private function __clone() {}
	
	/**
	 * singleton sso initilizer
	 *
	 */
	public static function sso()
	{
		if(self::$instance == NULL)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * public static function chk_user
	 *
	 * chk user name and password
	 * @param string $uname
	 * @param string $password
	 *
	 * author zhangbo
	 */
	public function chk_user($uname, $upsw, $servertime, $nonce)
	{
		if($upsw == $this->_get_upsw_digest($uname, $servertime, $nonce))
		{
			//session 中填写用户姓名
			sf\write_session('uname', $uname);
			return true;
		}
		return false;
	}

	public function _get_upsw_digest($uname, $servertime, $nonce)
	{
		$upsw = $uname; //get password from db;
		$upsw = sha1(sha1($upsw));
		$upsw = sha1($upsw.$servertime.$nonce);
		return $upsw;
	}
	
	/**
	 * sell_ticket function
	 *
	 * sell client a ticket when client done login action
	 * set client cookie if find client no ticket
	 */
	public function sell_ticket()
	{
		$sid = session_id();
	
		//$sid = $this->mk_ticket();
		if(!$this->chk_ticket())
		{
			//Use sso set sessionLifeTime firstly
			//if not set sso sessionLifeTime then use 
			//the SSOConfig messGcMaxlifetime
			$sessLife = $this->sessionLifeTime;
			if($sessLife == 0)
				$sessLife = sc\Config::$sessGcMaxlifetime;
			$sessLife += time();
			
			//设置SSO域下的cookie lifespan
			$ticket = $this->mk_ticket();
			setcookie('ticket', $ticket, $sessLife);
			sf\write_session('ticket', $ticket);
			
			//remember session ticket last access time
			sf\write_session('ticket_atime', time());

			$this->cookTicket = $this->sessTicket = $sid;
			
			//return session id
			return $sid;
		}
		return false;
	}

	
	/**
	 * mk_ticket
	 * 
	 * Ticket formula
	 * @return string
	 */
	public function mk_ticket()
	{
		$ticket = time(); //种子
		
		$salt = rand(1, 10000);
		
		$ticket .= $salt;
		
		$ticket .= $this->generate_nonce();
		
		$ticket = md5($ticket);
		
		
		
		return substr($ticket, 0, 16);
	}
	
	/**
	 * chk_ticket
	 *
	 * 在ticket不过期前提下，
	 * 查看用户的sso cookie ticket是否和SSO中的session ticket一致
	 */
	public function chk_ticket()
	{
		//find $_SESSION['ticket']
		if($cticket = sf\getGPC(sc\Config::$cookSessName, 'c'))
		{
			session_id($cticket);
			session_start();
		}
		
		
		
		$val = sf\chk_session('ticket');
		$atime = sf\chk_session('ticket_atime');
		
		$this->sessTicket = $val;
		//find cookie ticket
		//compare the two value
		$ticket = $this->_get_cook_ticket();
		$timeSpan = time() - $atime;
		//when ticket exists and not expired
		if($ticket && $timeSpan <= sc\Config::$keepDummyClientAliveTime)
		{
			if($val == $ticket)
			{
				//检查ticket access time 后应该更新时间值
				sf\write_session('ticket_atime', time());
				return true;
			}
		}
		return false;
	}

	/**
	 * chk_client_send_ticket
	 *
	 * 查看接收到的ticket是否和session ticket一致
	 *
	 * @return boolean
	 */
	public function chk_client_send_ticket($clientTicket='')
	{
		if($clientTicket == '')
			return false;
		$this->getTicket = $clientTicket;
		//get sso session ticket with session id = $clientTicket;
		session_id($clientTicket);
		$this->sessTicket = sf\chk_session('ticket');
		
		if(sc\Config::$memcacheSessionEnabled)
		{
		
			$memcache = new \Memcache();
			$memcache->connect('localhost', 11211) or die('mem not cached!');
			//ticket exists(for ticket is sessionid now)			
			if($sess = $memcache->get($clientTicket))
			{
				$ticket_atime = intval($this->get_mem_session($sess, 'ticket_atime'));
				//chk access time
				if(sc\Config::$keepDummyClientAliveTime < time() - $ticket_atime)
				{
					return false;
				}
				
				//preg_match('/(?<=ticket\|s:\d*:).*(?=;)/', $sess, $ticket);
				$this->sessTicket = $this->get_mem_session($sess, 'ticket');
			}
			$memcache->close();
		}
		return sf\chk_equal($clientTicket, $this->sessTicket);
	}
	
	/**
	 * get_mem_session_json
	 * 
	 * Get session in memcache by json format
	 * 
	 * @param unknown_type $sessStr
	 * @return boolean|string
	 */
	public function get_mem_session_in_array($sessStr)
	{
		if(!$sessStr)
			return false;
		$jsonArray = array();
		$parts = explode(';', $sessStr);
		//kill last empty member (;)
		array_pop($parts);
		foreach($parts as $p)
		{
			$ps = explode(':', $p);
			//remove '|s' and '|i' parts, remove braced " of string
			$key = substr(array_shift($ps), 0, -2);
			
			$val = trim(array_pop($ps), '"');
			$val = is_numeric($val) ? intval($val) : $val;
			$jsonArray[$key] = $val;
		}
		return $jsonArray;
	}
	
	public function get_mem_session($sessStr, $var)
	{
		if($var=='')
			return false;
		//(?<=\w*\|(s:)?i?\d*:)[\w|\"]*(?=;)
		//php不支持含不定宽度（+，*，{1，}等）d零宽断言
		//$pattern = '/(?<='.$var.'\|(s:)i)\d:[\w|\"]*(?=;)/';
		//if(preg_match($pattern, $sessStr, $res))
		//{
		//	return $res[0];
		//}
		$parts = explode(';', $sessStr);
		array_pop($parts);
		foreach($parts as $p)
		{
			$ps = explode(':', $p);
			//remove '|s' and '|i' parts, remove braced " of string
			if(substr(array_shift($ps), 0, -2) == $var)
				return trim(array_pop($ps), '"');
		}
		return false;
		
	}

	/**
	 * get_ticket
	 *
	 * Get user ticket from cookie
	 *
	 */
	public function _get_cook_ticket()
	{
		$this->cookTicket = sf\getGPC('ticket', 'c');
		return $this->cookTicket;
	}

	/**
	 * get_user_info
	 *
	 * Get user info by user ticket
	 * All app user info should get from this method
	 */
	public function get_user_info($ticket)
	{
		
	}

	/**
	 * get_from_url
	 *
	 * Get $_SERVER['HTTP_REFERER'] and if it's from SSO
	 * then just return the URL without query string parts
	 *
	 */
	public function get_from_url()
	{
		$referer = '';
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$referer = $_SERVER['HTTP_REFERER'];
			//$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']
			//处理 $referer 中的 from=sso后的querystring 部分，防止这个部分重复
			$referer = preg_replace('/&from=sso.*$/', '', $referer);
			$_parts = parse_url($referer);
			if(!isset($_parts['query']) && trim($referer, '?'))
			{
				$referer .= '?';
			}
		}
		return $referer;
	}	

	/**
	 * is_app_referer
	 *
	 * Check if the ask is from App domain
	 * query string app will be tried firstly
	 */
	public function is_app_referer()
	{
		/////////////////
		if($app = sf\getGPC('service', 'p'))
		{
			return in_array($app, sc\Config::$clientDomains);
		}
		/////////////////
		$app = '';
		if(isset($_SERVER['HTTP_REFERER']))
			$app = sf\get_url_host($_SERVER['HTTP_REFERER']);

		if($ref = sf\getGPC('app', 'g'))
			$app = sf\decrypt_url($ref);

		$app = trim($app, 'http://');

		if($app && in_array($app, sc\Config::$clientDomains))
			return true;

		return false;
	}
	
	/**
	 * withdraw_ticket
	 * 
	 * Withdraw the user ticket
	 * @return boolean
	 */
	public function withdraw_ticket()
	{
		if($this->chk_ticket())
		{
			sf\kill_session();
			return true;
		}
		return false;
	}
	
	/**
	 * form_app_url
	 * 
	 * Build app url from GET app value
	 */
	public function form_app_url()
	{
		//get app val from GET
		$gApp = sf\getGPC('app', 'g');
		if(!$gApp)
			return false;
		$app = sf\decrypt_url($gApp);
		if(array_key_exists($app, sc\Config::$apps))
			$arrAppSettings = sc\Config::$apps[$app];
		else 
			return false;
		if(array_key_exists('login', $arrAppSettings))
			$appLoginPage = $arrAppSettings['login'];
		else
			return false;
		$app .= '/'.$appLoginPage.'?';
		return $app;
	}
	
	/**
	 * set_session_life
	 * 
	 * Set life span for current session(cookie)
	 * 
	 * @param number $lifeSpan
	 * @return boolean
	 */
	public function set_session_life($lifeSpan=0)
	{
		if(!is_numeric($lifeSpan) || $lifeSpan == 0)
			return false;
		//cookie lifespan
		$this->sessionLifeTime = $lifeSpan;
	}
	
	public function generate_nonce()
	{
		//2 176 782 336
		$src = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		$nonce = substr($src, rand(0, 35), 1);
		$nonce .= substr($src, rand(0, 35), 1);
		$nonce .= substr($src, rand(0, 35), 1);
		$nonce .= substr($src, rand(0, 35), 1);
		$nonce .= substr($src, rand(0, 35), 1);
		$nonce .= substr($src, rand(0, 35), 1);
		
		return $nonce;
		
	}
	
	
	public function mem_user_ticket_by_email($email, $status)
	{
		
	}
}
//end of SSO class declaration

?>