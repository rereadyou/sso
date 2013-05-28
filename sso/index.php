<?php 
	// need php version 5.3.0 or heigher
	if(!defined('PHP_VERSION_ID') || (PHP_VERSION_ID < 50300))
	{
		die("System needs PHP version 5.3.0 or heigher!");
	}

    require 'sso.php';

	use sso\core\common as CC;
	
	CC\Router::instance()->run($rules);

?>
