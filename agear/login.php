<?php
	use sso\functions as sf;
	use sso\sso_client as sc;
	
	require "functions.php";
	require "sso_client.php";


	/* 1 */
	//域内进入时用于登录后跳转
	//由SSO返回的用于判断用户登录状态
	//域内进入必须设置 url get方式参数
	$rtnURL = sc\SSOClient::get_from_url();

	/* 2 */
	//每一次调用都需要判断用户登录情况
	//处理登出
	if(sf\getGPC('action', 'g') == 'logout')
	{
		//从SSO返回并且已经从SSO登出
		if(sc\SSOClient::is_sso_referer())
		{
			sf\kill_session();
		}
		else
		{
			sc\SSOClient::logout();
		}		
	}

	//同域的周期性登录检查,需要通过SSO提供的页面进行
	if(sf\getGPC('action', 'g') == 'login_chk' && $t = sf\getGPC('ticket', 'g'))
	{
		//echo sc\SSOClient::$ssoTicketEchoURL.'?ticket='.$t;
		echo file_get_contents(sc\SSOClient::$ssoTicketEchoURL.'?ticket='.$t);
		exit;
	}
	
	//这里会进行跳转，因而不便用来就行周期性的登录查询
	$isLogined = false;
	//if(!sf\getGPC('error', 'g'))
	{
		$isLogined = sc\SSOClient::chk_sso_ticket();
	}
	

	//若用户已经登录SSO
	if($isLogined)
	{
		//写本域的cookie
		sc\SSOClient::set_client_ticket();
		//根据 rtnURL跳转
		if(!$rtnURL)
		{
			header('location: '.sc\SSOClient::$clientHost.'/'.sc\SSOClient::$domainHome.'?authorized=true');
		}
		else
		{
			header('location: '.$rtnURL);
			exit;
		}
		exit;
	}
	

	//若是SSO访问，则为验证返回/登录后返回	
	if(sc\SSOClient::is_sso_referer())
	{
		//检查登录错误
		$error = sc\SSOClient::chk_sso_error();		
		//出现登录错误时
		if($error)
		{
			echo $error;
			echo '</br>';
		}		
		
		
		/*
		//从 SSO 返回并且有ticket
		if(sc\SSOClient::set_client_ticket())
		{
			//若直接从app login页面进入，则跳转至app home
			if($rtnURL == sc\SSOClient::$clientHost.sc\SSOClient::$domainHome)
			{
				header('location: ./'.sc\SSOClient::$domainHome);
				exit;
			}
			//否则跳转至进入之前的页面
			header('Location: '.$rtnURL.'?authorized=true');
			exit;
		}
		*/
	}
	
	
	echo 'You hava to login firstly!</br>';

////////////////////////////////////////////////////////////////
?>
