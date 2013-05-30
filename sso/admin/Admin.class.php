<?php
/**
 * Admin controller
 *
 */

namespace sso\core\common;

use sso\core\common as cm;
use sso\core\model as md;
use sso\core\common\functions as sf;
use sso\core\config as sc;


class Admin extends cm\Controller
{
    public $suser = NULL;
    public $app  = NULL;
    public $sso = NULL;
    private $islogined = false;
    private $auth = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->sso = cm\SSO::sso();
        $this->sso->enable_memcached();
        $this->auth = new cm\Authentication();
    }

    public function index()
    {
        if(!$this->auth->islogined())
        {
            $this->jumpto('admin/login');
        }
        echo 'index page';
    }

    public function login()
    {
        if($this->auth->islogined())
        {
            $this->jumpto('admin/index');
        }
        else
        {
            $this->flush();
        }

        //$this->out("SSO Administrator Login Page.");    
        //$auth = new cm\Authentication();
        //echo $states = $auth->login_chk();
        //$this->out($states);
        //$this->fetch_flush('authentication/login');
    }

    public function addapp()
    {
        $app = new md\App;


        $this->set('uname', 'rereadyou');
        return $this->flush(); 
    }

    public function listapp()
    {
        $apps = md\App::find_all();
        $no = $apps->size(); 
        $attrs = $apps->attrs;

        $this->set('apps', $apps)
             ->set('appno', $no)
             ->set('attrs', $attrs)
             ->flush();

        
        
    }
    

}
//end of Controller Admin class declaration
?>
