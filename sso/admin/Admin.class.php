<?php
/**
 * Admin controller
 *
 */

namespace sso\core\common;

use sso\core\common as cm;
use sso\core\model as md;


class Admin extends cm\Controller
{
    public $suser = NULL;
    public $app  = NULL;

    public function __construct()
    {
        parent::__construct();
    }
    
    //default page
    public function index()
    {
        $this->suser = new md\User(); 
        print_r($this->suser);
    }

    public function apps()
    {
        
    }

    public function addapp()
    {
        $app = new md\App;


        $this->set('uname', 'rereadyou');
        $this->flush(); 
    }
}
//end of Controller Admin class declaration
?>
