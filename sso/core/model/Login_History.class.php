<?php

namespace sso\core\model;

use sso\core\common as cm;
use sso\core\common\functions as sf;

class Login_History extends cm\Model
{
    public $id = NULL;
    public $uid = NULL;
    public $appid = NULL;
    public $browser = NULL;
    public $sessid = NULL;
    public $ip = NULL;
    public $ticket = NULL;
    public $trytimes = NULl;
    public $referrer = NULL;
    public $servertime = NULL;
    public $logtime = NULL;


	public function __construct()
	{
        $this->ip = sf\get_client_ip();
        $this->browser = sf\get_client_browser();
        $this->logtime = time();
	}
}



?>
