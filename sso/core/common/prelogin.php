<?php

/**
 * prelogin page
 * 
 * Generate server time and nonce for client
 */

	use sso\core\common as ss;

	use sso\core\common\functions as sf;
	
	require 'sso.php';

	require 'functions.php';
	
	$sso = ss\Allyes_SSO::sso();
	
	$retcode = 0;
	$nonce = $sso->generate_nonce();
	$serverTime = time();

	$memcache = new Memcache();
	$memcache->connect('localhost', 11211) or die('mem not cached!');
	
	if($memcache->set($nonce, $serverTime))
	{
		$arg = json_encode(array(
								'retcode' => $retcode,
								'nonce'	=> $nonce, 
								'servertime' => $serverTime
						));
		if($callback = sf\getGPC('callback', 'g'))
		{
			$arg = $callback.'('.$arg.')';
		}
		echo $arg;
		
		//echo sha1('O5OX4D');
	}
	
	//echo $memcache->get($nonce);
	exit;
?>