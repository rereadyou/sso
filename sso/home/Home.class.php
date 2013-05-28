<?php 
namespace sso\core\common;

use sso\core\common as cm;
use sso\core\model as md;
use sso\core\common\funcions as sf;


class Home extends cm\Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //$user = new md\User;
        $user = md\User::find(1);
        echo '<pre>';
        print_r($user->oa);
        echo '</pre>';

        if($_POST)
        {
            
        }

        $this->set('user', $user);
        $this->flush();
    }







}//end of class home controller declaration
?>
