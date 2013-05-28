<?php
/**
 * sso_client
 *
 * SSOClient is for interaction with SSO
 * All ticket chk and set and login process
 * should be handled by SSOClient
 */
namespace sso\sso_client;

use sso\functions as sf;

require_once 'functions.php';

class SSOClient 
{
	//SSO 地址
	public static $ssoURL		= "http://www.sso.com/login.php";
	//SSO host
	public static $ssoHost 		= "www.sso.com";
	public static $ssoTicketEchoURL = 'http://www.sso.com/echo_sso_ticket.php';
	//client app host, with "http://"
	public static $clientHost	= "http://www.domain1.com";
	//client app login page
	public static $domainLogin	= "login.php";
	//client app home page
	public static $domainHome	= "app.php";

	//获取用户cookie提供的ticket
	public static function get_user_ticket()
	{
		$ticket = false;
		if(isset($_COOKIE['ticket']))
		{
			$ticket = $_COOKIE['ticket'];
		}
		return $ticket;
	}

	//检查用户ticket是否有效
	public static function chk_user_ticket($ssoTicket)
	{
		if($uTicket = self::get_user_ticket())
		{
			return $uTicket == $ssoTicket; 
		}

		return false;
	}

	/**
	 * get_from_url
	 *
	 * Get from url which redirected to sso client login page
	 * Return from url or false
	 */
	public static function get_from_url()
	{
		//获取域内url get参数的解码后url
		if($url = sf\getGPC('url', 'g'))
		{
			$url = sf\decrypt_url($url);
			$from = parse_url(urldecode(rtrim($url, '?'))); 
			
			//若url参数与当前文件同域则为登录/登录状态查询
			if($from['host'] == $_SERVER['HTTP_HOST'])
			{
				return sf\build_url($from);
			}
		}
		return false;
	}

	/**
	 * chk_login_error
	 *
	 * chk if SSO error happened
	 * return false if no error, else return error msg
	 */
	public static function chk_sso_error()
	{
		$fromSSO = sf\getGPC('from', 'g');
		$errNo = sf\getGPC('error', 'g');
		if($errNo)
		{
			return sf\get_error($errNo);
		}
		return false;
	}

	/**
	 * set_client_ticket
	 *
	 * Set client domain ticket when SSO ticket is received
	 */
	public static function set_client_ticket()
	{
		$fromSSO = sf\getGPC('from', 'g');
		$ticket = sf\getGPC('ssoticket', 'g');

		if($fromSSO == 'sso' && $ticket)
		{
			//从 SSO 返回并且有ticket
			//写本域ticket
			setcookie('ticket', $ticket);
			sf\write_session('ticket', $ticket);
			//返回最初请求的页面
			return true;
		}
		return false;
	}

	/**
	 * chk_sso_ticket
	 *
	 * Check user ticket in SSO domain
	 */
	public static function chk_sso_ticket()
	{
		//由用户cookie中的ticket判断用户是否已经登录SSO
		//这个cookie应该是SSO域下的cookie，而非domain1等下的cookie
		//如果用户已经登录SSO
		//只应该接受SSO的访问，因此这里需要进行判断
		if(sf\getGPC('from', 'g') == 'sso')
		{
			if(sf\getGPC('error'))
				return false;
			if(sf\getGPC('ssoticket', 'g'))
				return true;
			//若已经从SSO登出则不必再查看是否有SSO的ticket
			if(sf\getGPC('action', 'g') == 'logout')
				return false;
		}
		
		header('Location:'.self::$ssoURL.'?app='.sf\encrypt_url(self::$clientHost));	
		exit;
	}

	/**
	 * is_sso_referer
	 * 
	 * Verity if it's a SSO referer
	 * 
	 * TODO: Update the judge method, not use GET
	 */
	public static function is_sso_referer()
	{
		//if(isset($_SERVER['HTTP_REFERER']))
		//	return self::$ssoHost == sf\get_url_host($_SERVER['HTTP_REFERER']);
		if(sf\getGPC('from', 'g') == 'sso')
			return true;
		return false;
	}

	/**
	 * logout
	 * 
	 * SSO Client app logout action
	 */
	public static function logout()
	{
		//app应该为登录页面URL
		header('Location:'.self::$ssoURL.'?app='.sf\encrypt_url(self::$clientHost).'&action=logout');
		exit;
	}
}
//end of SSOClient class
	


?>