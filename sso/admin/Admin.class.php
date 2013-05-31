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

        $this->set('ao', $apps)
             ->flush('listao')
             ->fetch_flush('admin/listactiveuser')
             ->fetch_flush('admin/listuser');
    }

    public function listuser()
    {
        $users = md\User::find_all();

        //echo '<pre>';
        //print_r($users->attrs);
        //echo '</pre>';
        $this->set('ao', $users)
             ->flush('listao');
    }

    public function listactiveuser()
    {
        $sessions = md\Login_Session::find_all();
        $this->set('ao', $sessions)
             ->flush('listao');
    }

    

}
//end of Controller Admin class declaration
?>
