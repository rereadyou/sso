<?php
/**
 * core functions 
 *
 * version:	0.1
 * author: zhanbo		date: 2013/3/26
 */
namespace sso\functions;
	
	/**
	 * help function _getGPC
	 *
	 * get variable named $var in $_GET, $_POST or $_COOKIE
	 *
	 * @param string $var
	 * @param string $type='GP'
	 * @author rereadyou
	 */
	function getGPC($var, $type='GP')
	{
		$type = strtoupper($type);
		switch($type)
		{
			case 'G': $rtn = $_GET;	break;
			case 'P': $rtn = $_POST; break;
			case 'C': $rtn = $_COOKIE; break;
			default:
				$rtn = $_POST;
				if(isset($_GET[$var]))
					$rtn = $_GET;
				break;
		}				
		return isset($rtn[$var]) ? $rtn[$var] : false;
	}

	

	function get_error($id)
	{
		$loginErr = array(
				1=>'Timeout',
				2=>'Unknow reason',
				3=>'Wrong username and password combination!',
				4=>'',
			);


		if(!is_numeric($id))
			return NULL;

		return $loginErr[$id];
	}


	function write_session($var, $value)
	{
		session_write_close();
		session_start();
		$_SESSION[$var] = $value;
		session_write_close();
	}

	/**
	 * chk_session
	 *
	 * check if given session value is right or
	 * return session variable if no session value given
	 */
	function chk_session($var, $val='')
	{
		session_write_close();
		session_start();
		if($val != '')
		{
			return $_SESSION[$var] == $val;
		}

		if(isset($_SESSION[$var]))
		{
			return $_SESSION[$var];
		}
		session_write_close();
		return false;
	}

	function get_session_by_id($sid)
	{
		session_id($sid);
	}

	function chk_equal($lval, $rval)
	{
		return $lval == $rval;
	}

	/**
	 * build_url
	 *
	 * build an url from parts(parse_url)
	 */
	function build_url($parts)
	{
		$url = '';
		if(isset($parts['scheme']))
			$url .= $parts['scheme'];
		$url .= '://';
		if(isset($parts['host']))
			$url .= $parts['host'];
		if(isset($parts['port']))
			$url .= $parts['port'];
		if(isset($parts['path']))
			$url .= $parts['path'];
		if(isset($parts['query']))
		{
			$url .= '?';
			$url .= $parts['query'];
		}
		if(isset($parts['fragment']))
			$url .= $parts['fragment'];

		return $url;
	}

	/**
	 * encrypt_url
	 *
	 * return encrypted URL for passing by get method
	 */
	function encrypt_url($url)
	{
		return base64_encode(encrypt($url));
	}

	/**
	 * decrypt_url
	 *
	 * Return decrypted URL when get from client
	 */
	function decrypt_url($url)
	{
		return decrypt(base64_decode($url));
	}

	function encrypt($src)
	{
		//in url get variable '?' should be escaped
		$src = str_replace('?', '*#*', $src);
		return $src;
	}

	function decrypt($src)
	{
		$src = str_replace('*#*', '?', $src);
		return $src;
	}

	function kill_session($sid='')
	{
		/* 1 */
		session_start();
		if(empty($sid))
			$sid = session_id();
		session_id($sid);
		
		/* 2 */
		unset($_SESSION['ticket']);
		if(isset($_COOKIE['ticket']))
			setcookie('ticket', '', time()-42000, '/');
		/* 3 */
		session_destroy();
	}

	
	/**
	 * get_url_host
	 *
	 * Get url host
	 */
	function get_url_host($url)
	{
		if($url == '')
			return false;
		$form = parse_url($url);
		if(isset($form['host']))
			return $form['host'];
		return false;
	}

?>