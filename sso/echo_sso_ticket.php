<?php

	use sso\config as sc;
	use sso\sso as ss;
	use sso\functions as sf;

	require 'sso.php';
	require 'functions.php';
	require 'sso_config.php';

	$sso = ss\Allyes_SSO::sso();
	//if(sc\SSOConfig::$memcacheSessionEnabled)
		sc\SSOConfig::enable_memcache_session_handler();
	//this should be the SSO domain cookie ticket 
	//rather than app domain cookie ticket
	$userTicket = sf\getGPC('ticket', 'g');

	$chkRes = $sso->chk_client_send_ticket($userTicket);

	if($chkRes)
	{
		
		echo 'logined';
		exit;
	}

	echo 'unlogined';


?>