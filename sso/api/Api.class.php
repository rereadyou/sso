<?php
namespace sso\core\common;

use sso\core\common as cm;
use sso\core\model as md;
use sso\core\config as sc;
use sso\core\common\functions as sf;


class Api extends cm\Controller
{
    public $sso = NULL;
    public $retcode = NULL;
    public $user = NULL;
    public $info = array();

    public function __construct()
    {
        $this->sso = cm\SSO::sso();
        $this->sso->enable_memcached();
    }

    /**
     * flush_out
     *
     * Flush the api result out.
     *
     * @access  private
     * @param   array   $info
     * @param   string  $type='json'
     */
    private function flush_out($info, $type='json')
    {
        if($type == 'json')
        {
            $info['retcode'] = $this->retcode;
            $info['reason'] = sf\get_retcode($this->retcode);
            $ret = json_encode($info);

            if($callback = sf\getGPC('callback', 'g'))
            {
                $ret = $callback.'('.$ret.')';
            }
        }

        echo $ret;
        exit;
    }

    /**
     * getuserstates
     *
     * Get user login states according to uid
     *
     * @access  public
     * @param   integer $uid
     * @return  string  json kind string
     */
    public function getuserstates($args)
    {
        if(count($args) != 1)
        {
            die ("Wrong No. of Arguments, required 1!");
        }

        $uid = array_shift($args);
        $users = md\User::find_by_id($uid);

        if(!is_numeric($uid))
        {
            $this->retcode = 16; //need an integer id NO.
        }
        else if(!$users->size())
        {
            $this->retcode = 15; //no such user id
        }
        else 
        {
            $this->retcode = 11; //unlogined 
            
            if(!$history = $this->sso->mc->get($uid))
            {
                $dbinfo = md\Login_Info::find_by_uid($uid);
                if($dbinfo->size())
                {
                    $info = $dbinfo[0];
                    $history = $info->history;
                    $this->retcode = 5;
                }
            }

            if($history)
            {
                $history = unserialize($history);
                $sid = $history->sessid;

                $this->retcode = 17;
                $sessid = sc\Config::$sessNamePrefix.$sid;
                if(!$sess = $this->sso->mc->get($sessid))
                {
                    $sess = md\Login_Session::find_by_sessid($sid);
                    $sess = $sess[0]->sessinfo;
                }

                if($sess)
                {
                    $info = $this->sso->get_mem_session_in_array($sess);
                    $this->retcode = 5; //logined    
                }
            }
        }

        $ret = array();
        if(isset($info))
        {
            $ret = array_merge($ret, $info);
        }

        $this->flush_out($ret);
    }
}//end of api class declaration

?>
