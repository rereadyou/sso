<?php

	use sso\functions as sf;

	require 'functions.php';

	$authorized = false;
	$authorized = sf\getGPC('authorized', 'g');


	if(!$authorized)
	{
		//未登录则跳转至登录界面
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		$url = sf\encrypt_url($url);
		//header('Location: ./login.php?url='.urlencode(trim($url, '?')));
		header('Location: ./login.php');

	}

	echo 'you hava login in domain1';
?>

<input type="button" value="logout" onclick='logout();' />










<script type="text/javascript" src="jquery-1.9.1.js"></script>
<script type="text/javascript" src="jquery.cookie.js"></script>
<!-- <script type="text/javascript" src="sso_navigator.js"></script> -->
