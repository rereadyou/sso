<?php
namespace sso\core\common;

use sso\core\common\functions as sf;
use sso\core\config as sc;
use sso\core\model as md;

/**
 * SSO
 *
 * Class SSO declaration
 *
 * @version:	0.1 
 * @author:	    zhangbo		date: 2013/3/26
 */
class SSO
{
    /**
     * @static
     * @access  public
     * @var     $instance
     */
	public static $instance = NULL;
	public static $SID 		= NULL;
	public $refered 		= NULL;

	public $sessionLifeTime = 0;
	public $sessTicket 		= NULL;
	public $cookTicket 		= NULL;
	public $getTicket 		= NULL;
    public $mc = NULL;

	public $status 			= NULL;	
    public $db = NULL;

    /**
     * __construct
     *
     * Initialize attributes
     *
     * @access  private
     */
	private function __construct()
	{
		//default session will be expired after Broswer closed.
		$this->sessionLiftTime = 0;
        $this->db = DB::getInstance();
	}

	/**
	 * __clone
	 *
	 * disable SSO object clone
	 */
	private function __clone() {}
	
	/**
     * sso
     * 
	 * singleton sso initilizer
	 *
     * @static
     * @access  public
     * @return  object  sso instance
	 */
	public static function sso()
	{
		if(self::$instance == NULL)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

    /**
     * enable memcached
     *
     * Enable memcached session storage mechanism
     *
     * @access  public
     * @return  boolean
     */
    public function enable_memcached()
    {
        sc\Config::enable_memcached_session_handler();
        $this->mc = new \Memcached();
        foreach(sc\Config::$memcServers as $server=>$port)
        {
            $this->mc->addServer($server, $port) or Log::error('memcached add server error: '+server+':'+$port+'.');

        }
        return true;
    }

    /**
     * verify_service
     *
     * Check if service is registed in SSO
     *
     * @access  public
     * @param   string  $service
     * @return  boolean
     */
    public function verify_service($service)
    {
        $apps = md\App::find_all();
        return in_array($service, $apps->name->oa);
    }

	/**
	 * chk_user
	 *
	 * chk user name and password
     *
     * @static
     * @access  public
	 * @param   object  $user
     * @param   string  $servertime
	 * @param   string  $nonce
     * @return  boolean
	 */
	public function chk_user(&$user, $servertime, $nonce)
	{
        $gPsw = sf\decrypted_rsa_ciphertext($user->password);
        //should use email as unique key for an user
        $dbuser = md\User::find_by_email($user->email);

        if($servertime."\t".$nonce."\n".$dbuser[0]->password == $gPsw)
        {
            $user->id($dbuser[0]->id)
                 ->name($dbuser[0]->name)
                 ->password($dbuser[0]->password);

            sf\write_session('uid', $user->id);
			sf\write_session('email', $user->email);
            sf\write_session('uname', $user->name);
			return true;
        }
		return false;
	}

    /**
     * store_session
     *
     * Store current session in database
     *
     * @access  public
     * @param   object  &$user
     * @return  boolean
     */
    public function store_session(&$history)
    {
        $sess = new md\Login_Session();
        $msess = $this->mc->get(sc\Config::$sessNamePrefix.$history->sessid);

        $sess->uid($history->uid)
             ->sessid($history->sessid)
             ->sessinfo($msess)
             ->save();

        return true;
    }

    /**
     * store_login_info
     *
     * 将用户登录信息存储到数据库中
     *
     * @access  public
     * @param   object  &$user
     * @return  boolean
     */
    public function store_login_info(&$history)
    {
        $info = new md\Login_Info();

        $info->uid($history->uid)
             ->sessid($history->sessid)
             ->history(serialize($history))
             ->save();
        return true;
    }

	/**
	 * sell_ticket function
	 *
	 * sell client a ticket when client done login action
	 * set client cookie if find client no ticket
     *
     * @access  public
     * @param   object  $history
     * @return  mixed   Return session id or false when fail
	 */
	public function sell_ticket(&$history)
	{
		$sid = session_id();
	
		if(!$this->chk_ticket($history))
		{
			$sessLife = $this->sessionLifeTime;
			if($sessLife == 0)
            {
				$sessLife = sc\Config::$sessGcMaxlifetime;
            }
			$sessLife += time();
			
			$ticket = $this->mk_ticket();

            //cookie path must be '/', for this file is in public/
            //and was required by Router;
			setcookie(sc\Config::$ssoticketName, $ticket, $sessLife, '/');
            
			sf\write_session(sc\Config::$ssoticketName, $ticket);
			//remember session ticket last access time
            $ticket_atime = time();
			sf\write_session('ticket_atime', $ticket_atime);
			$this->cookTicket = $this->sessTicket = $ticket;
			
            $history->sessid($sid)
                    ->ticket($ticket);

            $this->mc->set($history->uid, serialize($history));

            $this->store_session($history);
            $this->store_login_info($history);

			return $sid;
		}
		return false;
	}

	/**
	 * mk_ticket
	 * 
	 * 创建ticketo
     *
     * @access public
	 * @return string
	 */
	public function mk_ticket()
	{
		$ticket = time(); //种子
		
		$salt = rand(1, 10000);
		
		$ticket .= $salt;
		
		$ticket .= sf\mk_nonce(6);
		
		$ticket = md5($ticket);
		
		return substr($ticket, 0, 16);
	}
	
	/**
	 * chk_ticket
	 *
	 * 在ticket不过期前提下，
	 * 查看用户的sso cookie ticket是否和SSO中的session ticket一致
     *
     * @access  public
     * @param   object  $history   The history model object
     * @return  boolean
	 */
	public function chk_ticket(&$history)
	{
		if($sessid = $history->sessid)
		{
            if(!$this->mc->get(sc\Config::$sessNamePrefix.$sessid))
            {
                $this->recover_dbsess_to_memc($history);
            }
            if(!$this->mc->get($history->uid))
            {
                $this->recover_dbinfo_to_memc($history);
            }
			session_id($sessid);
			session_start();
		}

        $val = sf\chk_session(sc\Config::$ssoticketName);
        $atime = sf\chk_session('ticket_atime'); 
		$this->sessTicket = $val;
		$ticket = $this->_get_cook_ticket();
		$timeSpan = time() - $atime;

		//when ticket exists and not expired
		if($ticket && $timeSpan <= sc\Config::$keepDummyClientAliveTime)
		{
			if($val == $ticket)
			{
                //update ticket access time afeter chk
                $ticket_atime = time();
                $history->ticket($ticket)
                        ->ticket_atime($ticket_atime);

				sf\write_session('ticket_atime', $ticket_atime);
				return true;
			}
		}
		return false;
	}

    /**
     * recover_dbsess_to_memc
     * 
     * 尝试从数据库中回复当前指定的session到memcached 
     *
     * @access  private
     * @param   string   $sid  Specified session id
     * @return  boolean
     */
    private function recover_dbsess_to_memc(&$history)
    {
        if(!is_object($history) || empty($history->sessid))
            return false;

        $dbsess = md\Login_Session::find_by_sessid($history->sessid);
        if(count($dbsess->oa))
        {
            $dbsess = $dbsess[0]; 
            $mcsid = sc\Config::$sessNamePrefix.$dbsess->sessid;
            $this->mc->set($mcsid, $dbsess->sessinfo);
            return true;
        }
        return false;
    }

    /**
     * recover_dbinfo_to_memc
     *
     * Try to recover serialized user to memcached from db
     *
     * @access  private
     * @param   string  $sid    user session id
     * @return  boolean
     */
    private function recover_dbinfo_to_memc(&$history)
    {
        if(!is_object($history))
            return false;
        if(empty($history->sessid))
            return false;

        $dbinfo = md\Login_Info::find_by_sessid($history->sessid);
        if(count($dbinfo->oa))
        {
            $info = $dbinfo[0];
            $this->mc->set($info->uid, $info->history);
            return true;
        }
        return false;
    }
	
    /**
     * sess_decode
     *
     * 解码session,对session进行反序列化处理。由于session_decode内置函数
     * 反序列化session和原来的值有出入，因而实现次函数。
     *
     * @access  public
     * @param   string  $sess   serizlized session string 
     * @return  mixed   boolean or array
     */
    public function sess_decode($sess)
    {
        if(!$sess)
        {
            return array();
        }
        $old == $_SESSION;
        $_SESSION = array();

        if(!session_decode($sess))
        {
            $_SESSION = $old;
            return false;
        }

        $sessArr = $_SESSION;
        $_SESSIOn = $old;
    
        return $sessArr;
    }
	
	/**
	 * get_mem_session_in_array
	 * 
	 * Get session in memcache by json format
	 * 
     * @access  public
	 * @param   string  $sessStr
	 * @return  mixed   boolean|string
	 */
	public function get_mem_session_in_array($sessStr)
	{
		if(!$sessStr)
			return false;
		$jsonArray = array();
		$parts = explode(';', $sessStr);
		//kill last empty member (;)
		array_pop($parts);
		foreach($parts as $p)
		{
			$ps = explode(':', $p);
			//remove '|s' and '|i' parts, remove braced " of string
			$key = substr(array_shift($ps), 0, -2);
			
			$val = trim(array_pop($ps), '"');
			$val = is_numeric($val) ? intval($val) : $val;
			$jsonArray[$key] = $val;
		}
		return $jsonArray;
	}
	
    /**
     * get_mem_session
     *
     * Get session variable from memcached.
     *
     * @access  public
     * @param   string  $sessStr    session string.
     * @param   string  $var        variable
     * @return  mixed   boolean or string
     */
	public function get_mem_session($sessStr, $var)
	{
		if($var=='')
           return false;
       
		$parts = explode(';', $sessStr);
		array_pop($parts);
		foreach($parts as $p)
		{
			$ps = explode(':', $p);
			//remove '|s' and '|i' parts, remove braced " of string
			if(substr(array_shift($ps), 0, -2) == $var)
				return trim(array_pop($ps), '"');
		}
		return false;
	}

	/**
	 * get_ticket
	 *
	 * Get user ticket from cookie
	 *
     * @access  public
     * @return  string  Return ticket from cookie
	 */
	public function _get_cook_ticket()
	{
		$this->cookTicket = sf\getGPC(sc\Config::$ssoticketName, 'c');
		return $this->cookTicket;
	}

	/**
	 * get_from_url
	 *
	 * Get $_SERVER['HTTP_REFERER'] and if it's from SSO
	 * then just return the URL without query string parts
	 *
     * @access  public
     * @return  string  referer
	 */
	public function get_from_url()
	{
		$referer = '';
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$referer = $_SERVER['HTTP_REFERER'];
			//$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']
			//处理 $referer 中的 from=sso后的querystring 部分，防止这个部分重复
			$referer = preg_replace('/&from=sso.*$/', '', $referer);
			$_parts = parse_url($referer);
			if(!isset($_parts['query']) && trim($referer, '?'))
			{
				$referer .= '?';
			}
		}
		return $referer;
	}	

	/**
	 * is_app_referer
	 *
	 * Check if the ask is from App domain
	 * query string app will be tried firstly
     *
     * @access  public
     * @return  boolean
	 */
	public function is_app_referer()
	{
		if($app = sf\getGPC('service', 'p'))
		{
			return in_array($app, sc\Config::$clientDomains);
		}
		$app = '';
		if(isset($_SERVER['HTTP_REFERER']))
        {
			$app = sf\get_url_host($_SERVER['HTTP_REFERER']);
        }
		if($ref = sf\getGPC('app', 'g'))
        {
			$app = sf\decrypt_url($ref);
        }
		$app = trim($app, 'http://');

		if($app && in_array($app, sc\Config::$clientDomains))
        {
			return true;
        }

		return false;
	}
	
    /**
     * get_tid_and_sid
     *
     * Get ticket and session name from cookies
     *
     * @access  public
     * @return  array
     */
    public function get_tid_and_sid()
    {
        $tsid = array();
        $tsid[] = sf\getGPC(sc\Config::$ssoticketName, 'c');
        $tsid[] = sf\getGPC(sc\Config::$cookSessName, 'c');
        return $tsid;
    }

    /**
     * get_sess_info_by_sid
     *
     * Get user session info according to sid.
     *
     * @access  public
     * @param   integer $sid
     * @return  string  or false when failed.
     */
    public function get_sess_info_by_sid($sid)
    {
        $sessid = sc\Config::$sessNamePrefix.$sid;

        if(!$sess = $this->mc->get($sessid))
        {
            if(!$sess = $this->get_db_sess_info_by_sid($sid))
            {
                return false;
            }
        } 
        return $sess;
    }

    /**
     * get_db_sess_info_by_sid
     *
     * Get database session info according to sid.
     *
     * @access  private
     * @param   integer $sid
     * @return  string  or false when failed
     */
    private function get_db_sess_info_by_sid($sid)
    {
        $sql = "SELECT sess_info FROM login_session WHERE sessid='$sid' ORDER BY id DESC LIMIT 1";
        if($sess = $this->db->get_field($sql))
        {
            return $sess;
        }
        return false;
    }

	/**
	 * withdraw_ticket
	 * 
	 * Withdraw user ticket
     *
     * @access  public
	 * @return  boolean
	 */
	public function withdraw_ticket()
	{
        list($tid, $sid) = $this->get_tid_and_sid();

        if(!$sid)
            return false;
        if(!$tid)
            return false;

        $dbinfo = md\Login_Info::find_by_sessid($sid);
        $dbsess = md\Login_Session::find_by_sessid($sid);
        $uid = $dbsess[0]->uid;

        $dbinfo[0]->delete();
        $dbsess[0]->delete();
        $this->mc->delete($uid);
        sf\kill_session();

        return true;
	}

    /**
     * get_mc_uid_by_cook_ticket
     *
     * 根据传入的id和ticket获取memcached中的user id.
     *
     * @access  public
     * @param   string  $id='uname' default is 'uname'
     * @param   string  $cticket    default is empty string
     * @return  string  $uid    Return uid in memcached session 
     */
    public function get_mc_uid_by_cook_ticket($id='uname', $cticket='')
    {
        if(!$cticket)
        {
           $cticket = sf\getGPC(sc\Config::$cookSessName, 'c'); 
        }
        $msid = sc\Config::$sessNamePrefix.$cticket;
        $mcSess = $this->mc->get($msid);
        if($mcSess)
        {
            $uid = $this->get_mem_session($mcSess, $id);
        }
        return $uid;
    }

	/**
	 * form_app_url
	 * 
	 * Build app url from GET app value
     *
     * @access  public
     * @return  string  
	 */
	public function form_app_url()
	{
		//get app val from GET
		$gApp = sf\getGPC('app', 'g');
		if(!$gApp)
        {
			return false;
        }
		$app = sf\decrypt_url($gApp);
		if(array_key_exists($app, sc\Config::$apps))
        {
			$arrAppSettings = sc\Config::$apps[$app];
        }
		else 
        {
			return false;
        }
		if(array_key_exists('login', $arrAppSettings))
        {
			$appLoginPage = $arrAppSettings['login'];
        }
		else
        {
			return false;
        }
		$app .= '/'.$appLoginPage.'?';
		return $app;
	}
	
	/**
	 * set_session_life
	 * 
	 * Set life span for current session(cookie)
	 * 
     * @access  public
	 * @param   integer $lifeSpan
	 * @return  boolean
	 */
	public function set_session_life($lifeSpan=0)
	{
		if(!is_numeric($lifeSpan) || $lifeSpan == 0)
        {
			return false;
        }
		//cookie lifespan
		$this->sessionLifeTime = $lifeSpan;
	}
	
    /**
     * public_nonce
     *
     * Make a nonce and set it in memcached with 
     * value of current time stamp.
     *
     * @access  public
     * @param   integer $len=6    default by 6.
     * @return  mixed   $nonce  false by action failed.
     */
    public function publish_nonce()
    {
        $nonce = sf\mk_nonce(6);
        $servertime = time();
        if($this->mc->set($nonce, $servertime))
        {
            return array('nonce' => $nonce, 
                         'servertime' => $servertime);
        }
        return false;
    }

    /**
     * verify_nonce
     *
     * Check if nonce and servertime is correct.
     * 
     * @access  public
     * @param   string  $nonce
     * @param   string  $servertime
     * @return  boolean
     */
    public function verify_nonce($nonce, $servertime)
    {
        if($this->mc->get($nonce) == $servertime && 
            $this->mc->delete($nonce))
        {
            return true;
        }
        return false;
    }

}
//end of SSO class declaration

?>
