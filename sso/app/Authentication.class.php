<?php
namespace sso\core\common;

use sso\core\common as cm;
use sso\core\common\functions as sf;
use sso\core\config as sc;
use sso\core\model as md;

class Authentication extends cm\Controller
{
    public $sso = NULL;
    public $states = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->sso = cm\SSO::sso();
        $this->sso->enable_memcached();
    }

    public function prelogin($type='json')
    {
        if($nonce = $this->sso->publish_nonce())
        {
            $pubkey = array( 'retcode' => 0,
                            'reason' => sf\get_retcode(0),
                            'rsan' => sc\Config::$ssoRSAPublicKeyN,
                            'rsae' => sc\Config::$ssoRSAPublicKeyE,
                        );
            $args = array_merge($pubkey, $nonce);
            $arg = json_encode($args);

            if($callback = sf\getGPC('callback', 'g'))
            {
                $arg = $callback.'('.$arg.')';
            }
            echo $arg;
        }
        else
        {
            echo json_encode(array(
                                'retcode' => 20,
                                'reason' => sf\get_retcode(20),
                            ));
        }
        $this->states = 'prelogin';
        return $this;
    }

    public function login_chk($type='json')
    {
        if(sf\getGPC('action', 'g') == 'login_chk')
        {
            $service = sf\getGPC('service', 'g');		
            $sid = sf\getGPC(sc\Config::$cookSessName, 'c');
            $ticket = sf\getGPC(sc\Config::$ssoticketName, 'c');

            $jResArr = array();
            $jResArr['retcode'] = 11;

            $historys = md\Login_History::find_by_sessid($sid);
            $history = $historys[0];

            if(!$this->sso->verify_service($service))
            {
                $jResArr['retcode'] = 2;
            } 
            else if($this->sso->chk_ticket($history))
            {
                $ticket = $this->sso->cookTicket;
                $msid = sc\Config::$sessNamePrefix.$sid;

                if($mcSess = $this->sso->mc->get($msid))
                {
                    $sessArr = $this->sso->get_mem_session_in_array($mcSess);
                    $jResArr['retcode'] = 5;
                    $jResArr['ticket'] = $sessArr['allyes_ticket'];
                    $jResArr['ticket_atime'] = $sessArr['ticket_atime'];
                    $jResArr['uname'] = base64_encode($sessArr['uname']);
                }
            }	

            $jResArr['reason'] = sf\get_retcode($jResArr['retcode']);
            $res = json_encode($jResArr);
            if($callback = sf\getGPC('callback', 'g'))
            {
                $res = $callback.'('.$res.')';
            }
            
            echo $res;
            return $res;
        }
    }

    public function logout($type='json')
    {
        $retcode = 18;
        if(sf\getGPC('action', 'g') == 'logout')
        {
            $retcode = 6; //logout failed
            if($ticket = sf\getGPC(sc\Config::$ssoticketName, 'c'))
            {
                $retcode = 8; 
                if($this->sso->withdraw_ticket())	
                {
                    $retcode = 7;		
                }
            }
        }
            
        $res = json_encode(array(
                'retcode' => $retcode,
                'reason' => sf\get_retcode($retcode),
        ));
            
        if($callback = sf\getGPC('callback', 'g'))
        {
            $res = $callback.'('.$res.')';
        }

        echo $res;
    }

    public function login($type='json')
    {
        if($nonce = sf\getGPC('nonce', 'g'))
        {
            list($service, $servertime) = 
                sf\getGPCList('service', 'servertime', 'g');
            list($email, $upsw) = 
                sf\getGPCList('email', 'upsw', 'g');

            $user = new md\User();
            $history = new md\Login_History();
            
            $user->email(base64_decode($email))
                 ->password($upsw);

            $history->servertime($servertime);

            if(isset($_SERVER['HTTP_REFERER']))
            {
                $history->referrer($_SERVER['HTTP_REFERER']);
            }

            //以nonce为键取servertime
            $retcode = 4;
            if($this->sso->verify_nonce($nonce, $servertime))
            {
                $retcode = 0;					

                if(!$this->sso->verify_service($service))
                {
                    $retcode = 2;
                }
                else if($this->sso->chk_ticket($history)) //查票，防重复登录
                {
                    $retcode = 10;
                }
                else if($this->sso->chk_user($user, $servertime, $nonce))
                {
                    $retcode = 9; //sell ticket failed
                    $app = md\App::find_by_name($service);

                    $history->uid($user->id)
                            ->appid($app[0]->id);

                    if($sid = $this->sso->sell_ticket($history))
                    {
                        $retcode = 1; //login done
                        $history->save();
                    }
                }
                else 
                {
                    //wrong name and psw combination
                    $retcode = 3;
                }			
            }

            $retArray = array(
                    'retcode' => $retcode,
                    'reason' => sf\get_retcode($retcode),
                    'email' => $email,
                    'ticket' => $this->sso->sessTicket,
            );
        }	
        else
        {
            $retArray = array(
                    'retcode' => 19,
                    'reason' => sf\get_retcode(19),
            );
        }

        $res = json_encode($retArray);
        
        if($callback = sf\getGPC('callback', 'g'))
        {
            $res = $callback.'('.$res.')';
        }
        echo $res;
        return $this;
    }

    public function islogined()
    {
        $sid = sf\getGPC(sc\Config::$cookSessName, 'c');
        $msid = sc\Config::$sessNamePrefix.$sid;
        $ticket = sf\getGPC(sc\Config::$ssoticketName, 'c');
         
        if($sid && $this->sso->mc->get($msid))
        {
            return true;
        } 
        return false;
    }

}
//end of authentication class declaration
?>
