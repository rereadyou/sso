<?php
/**
 * SSO 登录控制页面
 *
 * 用于用户登录控制
 */

	use sso\core\common as cm;
	use sso\core\common\functions as sf;
	use sso\core\config as sc;
	

	require 'core\config\Config.php';
	require 'core\common\functions.php';
	require 'core\common\sso.php';
//	require 'MC.php';
	
	//是否开启memcache存储session
	//memcache 方式会影响存取session
	//nonce 的使用致使必须开启memcache
	sc\Config::enable_memcache_session_handler();

	//初始化SSO对象， sso 对象用于整个登录流程的控制
	$sso = cm\Allyes_SSO::sso();
	
	//设置cookie 60s 过期, 如不进行设置则使用 ssoconfig 中的sessGcmaxlife(5m);
	//$sso->set_session_life(60);

//	$mc = sm\MC::instance();

	$mc = new Memcache();
	$mc->connect('10.200.33.33', 11211) or die('mem not cached!');
	
///////////////////////////////////////////////////////////////////////////////
	//登出处理
	if(sf\getGPC('action', 'g') == 'logout')
	{
		$retcode = 6; //logout failed
		//echo sf\getGPC('ticket', 'c');
		if($ticket = sf\getGPC('ticket', 'c'))
		{
// 			$mc = new Memcache();
// 			$mc->connect('localhost', 11211) or die('mem not cached!');
			
			$retcode = 7; //logout complete
			//logout withdraw ticket failed
			if(!$sso->withdraw_ticket())	
				$retcode = 8;		
// 			$mc->close();		
		}
		
		$res = json_encode(array(
				'retcode' => $retcode,
				'reason' => sf\get_retcode($retcode),
		));
			
		if($callback = sf\getGPC('callback', 'g'))
		{
			$res = $callback.'('.$res.')';
		}
		echo $res;
		//header('Location: http://www.domain1.com/login.html?');
		exit;
	}

	
///////////////////////////////////////////////////////////////////////////////
	//判断当前用户是否已经登录
	if(sf\getGPC('action', 'g') == 'login_chk')
	{
		$service = sf\getGPC('service', 'g');		
		//cookie ticket 过期不进行判断，SSO以session ticket为判断登录依据
		//检查用户ticket通过时
		
		$jResArr = array();
		$retcode = 11; //unlogined;
		$jResArr['retcode'] = 11;

		if($sso->chk_ticket())
		{
			$ticket = $sso->cookTicket;
// 			$mc = new Memcache();
// 			$mc->connect('localhost', 11211) or die('mem not cached!');
			//echo 'ss', $sid = session_id();
			$sid = sf\getGPC(sc\Config::$cookSessName, 'c');
			if($mcSess = $mc->get($sid))
			{
				$jResArr['retcode'] = 5;
				$jResArr['ticket'] = $sso->get_mem_session($mcSess, 'ticket');
				$jResArr['ticket_atime'] = $sso->get_mem_session($mcSess, 'ticket_atime');
				$jResArr['uname'] = $sso->get_mem_session($mcSess, 'uname');
			}
// 			$mc->close();
		}	

		$jResArr['reason'] = sf\get_retcode($jResArr['retcode']);
		$res = json_encode($jResArr);
		//$res .= $ticket;
		if($callback = sf\getGPC('callback', 'g'))
		{
			$res = $callback.'('.$res.')';
		}
		
		echo $res;
		//header('Location: http://www.domain1.com/login.html?');
		//$mc->close();
		exit;
	}

	

///////////////////////////////////////////////////////////////////////////////	
//登录
	if($nonce = sf\getGPC('nonce', 'g'))
	{
		/////////////////////////////////
		$uname = sf\getGPC('uname', 'g');
		$upsw = sf\getGPC('upsw', 'g');
		$service = sf\getGPC('service', 'g');
		$servertime = sf\getGPC('servertime', 'g');
		
// 		$mc = new Memcache();
// 		$mc->connect('localhost', 11211) or die('mem not cached!');
		
		
		//以nonce为键取servertime
		//nonce 只能使用一次，使用后就要删除
		$retcode = 4;
		if($mc->get($nonce) == $servertime && $mc->delete($nonce))
		{
			// nonce 和 servertime 保证了访问是一次性的			
			$retcode = 0;					
			//echo $sso->sell_ticket() ? 'login' : 'no';
			if(!in_array($service, sc\Config::$services))
			{
				$retcode = 2;
			}
			else if($sso->chk_user($uname, $upsw, $servertime, $nonce))
			{
				$retcode = 9; //售票失败
				if($sid = $sso->sell_ticket())
				{
					$retcode = 11; //unlogined
					//用户登录成功后需要将用户登录标示（email或id)与用户tikcet进行存储，以备登录状态查询
					if($mc->replace($uname, $sid))
					{
						$retcode = 1; //login done
					}
					//echo $ticket;
				}
				//查票，防止重复登录
				if($sso->chk_ticket()) 
				{
					$retcode = 10;
				}
				//echo $mc->get($sso->sessTicket);
								
				//echo 'login is ok!';
			}
			else 
			{
				//wrong name and psw combination
				$retcode = 3;
			}			
		}
// 		$mc->close();

		$retArray = array(
				'retcode' => $retcode,
				'reason' => sf\get_retcode($retcode),
				'uname' => $uname,
				'ticket' => $sso->sessTicket,
		);
			
		$res = json_encode($retArray);
		
		if($callback = sf\getGPC('callback', 'g'))
		{
			$res = $callback.'('.$res.')';
		}
		echo $res;
	}	
	
	exit;
///////////////////////////////////////////////////////////////////////////////

?>
