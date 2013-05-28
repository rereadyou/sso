<?php

namespace sso\core\model;

use sso\core\config as sc;
use sso\core\common as cm;
use sso\core\common\functions as sf;

class App extends cm\Model
{
    public $id = NULL;
    public $contactor = NULL;
    public $name    = NULL;
    public $ip = NULL;
    public $domain = NULL;
    public $login_page = NULL;
    public $home_page = NULL;

    public function __construct()
    {

    }

} //end of App declaration
?>
