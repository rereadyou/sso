<?php 
	// need php version 5.3.0 or heigher
	if(!defined('PHP_VERSION_ID') || (PHP_VERSION_ID < 50300))
	{
		die("System needs PHP version 5.3.0 or heigher!");
	}


	use sso\core as C;
	use sso\core\common as CC;
	use sso\core\config as sc;
	
	include 'core/common/Singleton.php';

	include 'core/config/Config.php';
	
	
	
	include 'core/common/Log.php';

	include 'core/common/Router.php';
	
	//sc\Config::instance();
	
	CC\Router::instance()->run();


?>