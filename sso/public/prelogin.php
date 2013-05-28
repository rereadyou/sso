<?php
/**
 * prelogin 
 * 
 * Publish nonce and servertime to client
 */

	use sso\core\common as cm;
	use sso\core\common\functions as sf;
    use sso\core\config as sc;
	
	$sso = cm\SSO::sso();
    $sso->enable_memcached();
	
	if($nonce = $sso->publish_nonce())
	{
		$pubkey = array( 'retcode' => 0,
                        'reason' => sf\get_retcode(0),
                        'rsan' => sc\Config::$ssoRSAPublicKeyN,
                        'rsae' => sc\Config::$ssoRSAPublicKeyE,
                    );
        $args = array_merge($pubkey, $nonce);
		$arg = json_encode($args);

		if($callback = sf\getGPC('callback', 'g'))
		{
			$arg = $callback.'('.$arg.')';
		}
		echo $arg;
	}
    else
    {
        echo json_encode(array(
                            'retcode' => 20,
                            'reason' => sf\get_retcode(20),
                        ));
    }
	
	exit;
?>
