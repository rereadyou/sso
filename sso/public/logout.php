<?php
/**
 * SSO Logout control
 *
 */

	use sso\core\common as cm;
	use sso\core\common\functions as sf;
	use sso\core\config as sc;
	
	$sso = cm\SSO::sso();
    $sso->enable_memcached();
    $mc = $sso->mc;

    $retcode = 18;
	if(sf\getGPC('action', 'g') == 'logout')
	{
		$retcode = 6; //logout failed
		if($ticket = sf\getGPC(sc\Config::$ssoticketName, 'c'))
		{
			$retcode = 8; 
			if($sso->withdraw_ticket())	
            {
				$retcode = 7;		
            }
		}
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
    exit;

?>
