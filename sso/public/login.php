<?php

	use sso\core\common as cm;
	use sso\core\common\functions as sf;
	use sso\core\config as sc;
    use sso\core\model as md;
	
	$sso = cm\SSO::sso();
    $sso->enable_memcached();
    $mc = $sso->mc;
	
	//设置cookie 60s过期, 如不进行设置则使用ssoconfig中的sessGcmaxlife(5m);
	$sso->set_session_life(100000);

	if($nonce = sf\getGPC('nonce', 'g'))
	{
        list($service, $servertime) = 
            sf\getGPCList('service', 'servertime', 'g');
        list($email, $upsw) = 
            sf\getGPCList('email', 'upsw', 'g');

        $user = new md\User();
        $history = new md\Login_History();
        
        $user->email(base64_decode($email))
             ->password($upsw);

        $history->servertime($servertime);

        if(isset($_SERVER['HTTP_REFERER']))
        {
            $history->referrer($_SERVER['HTTP_REFERER']);
        }

		//以nonce为键取servertime
		$retcode = 4;
		if($sso->verify_nonce($nonce, $servertime))
		{
			$retcode = 0;					

            if(!$sso->verify_service($service))
            {
				$retcode = 2;
            }
            else if($sso->chk_ticket($history)) //查票，防重复登录
            {
                $retcode = 10;
            }
			else if($sso->chk_user($user, $servertime, $nonce))
			{
				$retcode = 9; //sell ticket failed
                $app = md\App::find_by_name($service);

                $history->uid($user->id)
                        ->appid($app[0]->id);

				if($sid = $sso->sell_ticket($history))
				{
                    $retcode = 1; //login done
                    $history->save();
                }
			}
			else 
			{
				//wrong name and psw combination
				$retcode = 3;
			}			
        }

		$retArray = array(
				'retcode' => $retcode,
				'reason' => sf\get_retcode($retcode),
				'email' => $email,
				'ticket' => $sso->sessTicket,
		);
	}	
    else
    {
		$retArray = array(
				'retcode' => 19,
				'reason' => sf\get_retcode(19),
        );
    }

    $res = json_encode($retArray);
    
    if($callback = sf\getGPC('callback', 'g'))
    {
        $res = $callback.'('.$res.')';
    }
    echo $res;
	
	exit;
    
?>
