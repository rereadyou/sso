<?php
/**
 * core functions 
 *
 * version:	0.1
 * author: zhanbo		date: 2013/3/26
 */
namespace sso\core\common\functions;

use sso\core\config as sc;

	/**
	 * help function _getGPC
	 *
	 * get variable named $var in $_GET, $_POST or $_COOKIE
	 *
	 * @param string $var
	 * @param string $type='GP'
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
		return isset($rtn[$var]) ? $rtn[$var] : NULL;
	}

    /**
     * getGPCList
     *
     * Get gpc arguments in array.
     *
     * @return  array
     */   
    function getGPCList()
    {
        $vars = func_get_args();
        $type = array_pop($vars);

        foreach($vars as $var)
        {
            $rets[] = getGPC($var, $type);    
        }
        return $rets;
    }

    function get_client_browser()
    {
        global $_SERVER;

        return $_SERVER['HTTP_USER_AGENT'];
    }

    //deprecated
    function get_obj_attrs($obj)
    {
        if(!is_object($obj))
            return false;
        ob_start();
        print_r($obj);
        $attrs = ob_get_contents();
        ob_clean();
        var_dump($attrs);
        
    }
    
    function get_client_ip()
    {
        global $_SERVER;
        if(!$ip = getenv('HTTP_CLIENT_IP'))
            if(!$ip = getenv('HTTP_X_FORWARD_FOR'))
                if(!$ip = getenv('REMOTE_ADDR'))
                    $ip = $_SERVER('REMOTE_ADDR');
        return $ip;
    }

    function get_client_os()
    {

    }

	function get_retcode($id)
	{
		$loginErr = array(
				0 => 'Dummy',
				1 => 'OK',
				2 => 'Unknow service',
				3 => 'Wrong username and password combination!',
				4 => 'Nonce don\'t exist',
				5 => 'Logined',
				6 => 'Logout without ticket',
				7 => 'Logout complete',
				8 => 'SSO withdraw session ticket failed',
				9 => 'SSO sell ticket failed',
				10 => 'Already Logined',
				11 => 'Unlogined',
				12 => 'Log user login status failed',
				13 => 'Query failed',
				14 => 'User unlogined',
				15 => 'Illegal user id',
				16 => 'No user id supplied',
				17 => 'Logined, but cannot get user info',
                18 => 'Illegal action',
                19 => 'Illegal request, no nonce supplied',
                20 => 'Generate nonce failed',
				
			);

		if(!is_numeric($id))
			return NULL;
		return base64_encode($loginErr[$id]);
	}

	function write_session($var, $value)
	{
		session_write_close();
		session_start();
		$_SESSION[$var] = $value;
		session_write_close();
        return true;
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
		unset($_SESSION[sc\Config::$ssoticketName]);
		if(isset($_COOKIE[sc\Config::$ssoticketName]))
			setcookie(sc\Config::$ssoticketName, '', time()-42000, '/');
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

    /**
     * get_decrypted_string
     *
     * Decrypte ciphertext with openssl
     */
    function decrypted_rsa_ciphertext($ciphertext)
    {
        $prikey = sc\Config::$ssoRSAPrivateKey;
        //change to hex 
        $ciphertext = pack('H*', $ciphertext);

        openssl_private_decrypt($ciphertext, $src, $prikey);
        return $src;
    }

    function encrypt_string($src)
    {
        $prikey = sc\Config::$ssoRSAPrivateKey;

        openssl_private_encrypt($src, $ciphertext, $prikey);
        return $ciphertext;
    }

    /**
     * mk_nonce
     *
     * 生成随机字符串，默认6位的长度，可以传入长度参数
     *
     * @access  public
     * @param   integer $len    Length of nonce
     * @return  string  $nonce
     */
	function mk_nonce($len=6)
	{
		$keys = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $nonce = '';
        $klen = strlen($keys); 

        for($i = 0; $i < $len; $i++)
        {
            $nonce .= substr($keys, rand(0, $klen), 1);
        }
		return $nonce;
	}

    function isbid(&$o, $s, $d=':')
    {
        if(!is_object($o))
           return false; 
        list($k, $v) = explode($d, $s);
        $ors = explode('|', $v);
        foreach($ors as $c)
        {
            $t = substr($c, 0, 1);
            $r = substr($c, 1);
            $f = strstr($o->$k, $r);

            switch($t)
            {
                case '~':
                    if($f)
                        continue;
                    else
                        return $o;
                case '%':
                    if($f)
                        return $o;
                    else
                        continue;
                default:
                    if($o->$k == $c)
                        return $o;
                    else
                        continue;
                    break;
            }
        }
    }
    
//end of function
?>
