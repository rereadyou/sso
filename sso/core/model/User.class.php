<?php 

namespace sso\core\model;

use sso\core\common as cm;
use sso\core\common\functions as sf;

class User extends cm\Model
{
	public $id = NULL;     
	public $email = NULL; 
    public $name = NULL; 
    public $password = NULL;
    public  $isadmin = '0';

    /*
    protected $psw = NULL;    //get psw
    protected $sessid = NULL; //current session id
    protected $sessInfo = NULL; 
    protected $ticket = NULL; //current ticket
    protected $ticket_atime = NULL; //current ticket time
    protected $servertime = NULL;  //servertime for nonce
	
    protected $logApp = NULL; //get app
    protected $ip = NULL;     //client ip
    protected $logTime = NULL;    //login time
    protected $tryTimes = NULL;   //try times before login
    protected $browser = NULL;    //browser type
    protected $referrer = NULL;   //previous page
    

	public function __construct()
	{
        $this->ip = sf\get_client_ip();
        $this->browser = sf\get_client_browser();
        $this->logTime = time();
	}

     */
}//end of class User declaration
?>
