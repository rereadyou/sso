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
        $this->fetch_flush('admin/head')
             ->fetch_flush('admin/listactiveuser')
             ->fetch_flush('admin/listuser')
             ->fetch_flush('admin/listuserhistory')
             ->fetch_flush('admin/listinfo')
             ->fetch_flush('admin/footer');
    }

    public function head()
    {
        $this->flush();
    }

    public function footer()
    {
        $this->flush();
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
             ->set('aoflush', 'Current APPs:')
             ->set('aono', $no)
             ->set('aoclass', 'app')
             ->flush('listao');
    }

    public function listuser()
    {
        $users = md\User::find_all();

        //echo '<pre>';
        //print_r($users->attrs);
        //echo '</pre>';
        $this->set('ao', $users)
             ->set('aono', count($users->oa))
             ->set('aoflush', 'Registed users:')
             ->set('aoclass', 'user')
             ->flush('listao');
    }

    public function listactiveuser()
    {
        $sessions = md\Login_Session::find_all();
        $this->set('ao', $sessions)
             ->set('aoflush', 'Current on line users:')
             ->set('aono', count($sessions->oa))
             ->set('aoclass', 'activeuser')
             ->flush('listao');
    }

    public function listuserhistory()
    {
        //$history = md\Login_History::find_by_uid($uid); 
        $history = md\Login_History::find_all(); 

        $this->set('ao', $history)
             ->set('aoflush', 'User login history:')
             ->set('aono', count($history->oa))
             ->set('aoclass', 'history')
             ->flush('listao');
    }

    public function listinfo()
    { $infos = md\Login_Info::find_all();

        $this->set('ao', $infos)
             ->set('aoflush', 'Current logined user info:')
             ->set('aono', count($infos->oa))
             ->set('aoclass', 'info')
             ->flush('listao');
    }

    public function deluser($id)
    {
        if(empty($id))
            return false;

        print_r(md\User::find_all()));
        $users = md\User::find($id);
        print_r($users);
        //$user = $users->oa[0];
        //$user->delete();
    }

}
//end of Controller Admin class declaration
?>
