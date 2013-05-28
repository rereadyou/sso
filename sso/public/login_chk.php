<?php

	use sso\core\common as cm;
	use sso\core\common\functions as sf;
	use sso\core\config as sc;
    use sso\core\model as md;	

	$sso = cm\SSO::sso();
    $sso->enable_memcached();
    $mc = $sso->mc;
	
	if(sf\getGPC('action', 'g') == 'login_chk')
	{
		$service = sf\getGPC('service', 'g');		
        $sid = sf\getGPC(sc\Config::$cookSessName, 'c');
        $ticket = sf\getGPC(sc\Config::$ssoticketName, 'c');

		$jResArr = array();
		$jResArr['retcode'] = 11;

        $historys = md\Login_History::find_by_sessid($sid);
        $history = $historys[0];

        if(!$sso->verify_service($service))
        {
            $jResArr['retcode'] = 2;
        } 
        else if($sso->chk_ticket($history))
		{
			$ticket = $sso->cookTicket;
            $msid = sc\Config::$sessNamePrefix.$sid;

			if($mcSess = $mc->get($msid))
			{
                $sessArr = $sso->get_mem_session_in_array($mcSess);
				$jResArr['retcode'] = 5;
				$jResArr['ticket'] = $sessArr['allyes_ticket'];
				$jResArr['ticket_atime'] = $sessArr['ticket_atime'];
				$jResArr['uname'] = base64_encode($sessArr['uname']);
			}
            if($jResArr['retcode'] != 5)
            {
            }
		}	

		$jResArr['reason'] = sf\get_retcode($jResArr['retcode']);
		$res = json_encode($jResArr);
		if($callback = sf\getGPC('callback', 'g'))
		{
			$res = $callback.'('.$res.')';
		}
		
		echo $res;
		exit;
	}

	exit;
?>
