<?php
namespace sso\core\common;

use sso\core\common as cm;
use sso\core\model as md;

class Authentication extends cm\Controller
{
    public function login()
    {
        if(count($_POST))
        {
        extract($_POST, EXTR_PREFIX_ALL, 'post');
        $users = md\User::find_by_email($post_email); 

        if(count($users) == 1)
        {
            $user = $users[0];
            if($user->password === $post_password)
            {
                echo 'login done';
            }
            else
            {
                echo 'login failed';
            }
        }
        }
        $this->flush();
    }
}
//end of authentication class declaration
?>
