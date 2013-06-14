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
//        $this->fetch_flush('admin/head')
        $this->fetch_flush('admin/listapp')
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

    public function listapp($id='')
    {
        if(empty($id))
        {
            $apps = md\App::find_all();
        }
        else if($id)
        {
            $apps = md\App::find_by_id($id);
        }

        $no = $apps->size(); 
        $attrs = $apps->attrs;

        $this->set('ao', $apps)
             ->set('aoflush', 'Current APPs:')
             ->set('aono', $no)
             ->set('aoclass', 'app')
             ->set('showaction', true)
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
             ->set('showaction', true)
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
             ->set('showaction', false)
             ->flush('listao');
    }

    public function listinfo()
    { 
        $infos = md\Login_Info::find_all();

        $this->set('ao', $infos)
             ->set('aoflush', 'Current logined user info:')
             ->set('aono', count($infos->oa))
             ->set('aoclass', 'info')
             ->set('showaction', false)
             ->flush('listao');
    }

    public function delapp($id)
    {
        if(count($id) != 1)
            return false;

        $id = array_pop($id);
        $apps = md\App::find($id);
        if(count($apps) == 1)
        {
            $app = $apps->oa[0];
            $app->delete();
            echo 'done!';
            return true;
        }
        else
        {
            $this->out('No such APP!');
            return false;
        }
    }

    public function deluser($id)
    {
        if(count($id) != 1)
            return false;

        $id = array_pop($id);
        $users = md\User::find($id);
        if(count($users) == 1)
        {
            $user = $users->oa[0];
            $user->delete();
            echo 'done!';
            return true;
        }
        else
        {
            $this->out('No such user!');
            return false;
        }
    }

    public function delinfo($id)
    {
        if(count($id) != 1)
            return false;

        $id = array_pop($id);
        $info = md\Login_Info::find($id);
        if(count($info) == 1)
        {
            $info = $info[0];
            $info->delete();
            echo 'done!';
            return true;
        }
        else
        {
            $this->out('No such info!');
            return false;
        }
    }

    public function delhistory($id)
    {
        if(count($id) != 1)
            return false;

        $id = array_pop($id);
        $history = md\Login_History::find($id);
        if(count($history) == 1)
        {
            $history = $history->oa[0];
            $history->delete();
            echo 'done!';
            return true;
        }
        else
        {
            $this->out('No such history!');
            return false;
        }
    }

    public function addapp()
    {
        $app = new md\App;
        $kvs = get_object_vars($app);
        array_shift($kvs); // kill id
        $attrs = array_keys($kvs);

        $err = '';
        if($_POST)
        {
            foreach($_POST as $k=>$v)
            {
                if(in_array($k, $attrs))
                {
                    $app->$k = $v;
                }
            }
            $app->save();
            if($app->id)
            {
                echo 'done!';
            }
            $err = 'Add new app failed!';
        }
            $this->set('attrs', $attrs)
                 ->set('addclass', 'app')
                 ->set('err', $err)
                 ->fetch_flush('admin/head')
                 ->flush()
                 ->fetch_flush('admin/footer');
    }

    public function adduser()
    {
        $user = new md\User(); 
        
        $attrs = get_object_vars($user);

        $this->set('attrs', $attrs)
             ->flush();
    }

    public function upapp($args)
    {
        if(count($args) !== 1)
            $err = 'Only app id required!';
        $id = array_shift($args);
        $app = md\App::find($id);

        if($app->size != 0)
            $err = 'No such app!';
        else
        {
            $app = $app[0];
            $attrs = get_object_vars($app);
            if(isset($_POST['app']))
            {
                foreach($_POST['app'] as $k=>$v)
                {
                    if($k !== 'id')
                        $app->$k($v);
                }
                $app->save();
                if($app->id)
                {
                    $this->set('hint', 'Update done!');
                }
            }
        }
        $this->set('attrs', $attrs)
             ->set('err', $err)
             ->set('modelclass', 'app')
             ->flush('up');
    }

    public function upuser($args)
    {
        if(count($args) !== 1)
            $err = 'Only user id required!';
        $id = array_shift($args);
        $user = md\User::find($id);

        if($user->size != 0)
            $err = 'No such User!';
        else
        {
            $user = $user[0];
            $attrs = get_object_vars($user);
            if(isset($_POST['user']))
            {
                foreach($_POST['user'] as $k=>$v)
                {
                    if($k !== 'id')
                        $user->$k($v);
                }
                $user->save();
                if($user->id)
                {
                    $this->set('hint', 'Update done!');
                    $this->out('done!');
                    return $this;
                }
            }
        }
        $this->set('attrs', $attrs)
             ->set('err', $err)
             ->set('modelclass', 'user')
             ->flush('up');
    }

} //end of Controller Admin class declaration
?>
