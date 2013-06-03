<?php

defined('SEPARATOR') or define('SEPARATOR', '/');
defined('BASE_PATH') or define('BASE_PATH', (string)(__DIR__.SEPARATOR));

defined('PUB') or define('PUB', 'public'.SEPARATOR);
defined('TPL') or define('TPL', PUB.'tpl'.SEPARATOR);
defined('API') or define('API', 'api'.SEPARATOR);
defined('CORE') or define('CORE', 'core'.SEPARATOR);
defined('COMMON') or define('COMMON', CORE.'common'.SEPARATOR);
defined('CONFIG') or define('CONFIG', CORE.'config'.SEPARATOR);
defined('MODEL') or define('MODEL', CORE.'model'.SEPARATOR);
defined('DB') or define('DB', CORE.'db'.SEPARATOR);
defined('DRIVER') or define('DRIVER', DB.'drivers'.SEPARATOR);
defined('ADMIN') or define('ADMIN', 'admin'.SEPARATOR);
defined('APP') or define('APP', 'app'.SEPARATOR);
defined('HOME') or define('HOME', 'home'.SEPARATOR);
defined('SMARTY_DIR') or define('SMARTY_DIR', COMMON);
defined('SMARTYSYSPLUGINS') or define('SMARTYSYSPLUGINS', SMARTY_DIR.'sysplugins'.SEPARATOR);
defined('SMARTY_PLUGINS') or define('SMARTY_PLUGINS', SMARTY_DIR.'plugins'.SEPARATOR);

$path = get_include_path(); 
$path .= (string) (PATH_SEPARATOR.BASE_PATH.HOME);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.COMMON);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.CONFIG);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.MODEL);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.DRIVER);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.API);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.APP);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.ADMIN);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.SMARTYSYSPLUGINS);
$path .= (string) (PATH_SEPARATOR.BASE_PATH.SMARTY_PLUGINS);
set_include_path($path);

 //conflict with smarty autoload function
 function sso_load($class)
 {
  //   echo '<pre>';
   //  debug_print_backtrace();
    // echo '</pre>';
    $class = end(explode('\\', $class));
    $file = $class.'.class.php';
    if(stristr($file, 'smarty_'))
    {
        return true;
    }
    include_once $file;
 }

 require 'functions.php';

 spl_autoload_register('sso_load');

 use sso\core\common as cm;
 //use sso\api; 
 //use sso\core\common\functions as sf;
 //use sso\core\config as sc;
 //use sso\core\module as mu;

 //$login = 'http://sso.allyes.me/login';
 //$login_chk = 'http://sso.allyes.me/login_chk';
 //$prelogin = 'http://sso.allyes.me/prelogin';
 //$logout = 'http://sso.allyes.me/logout';

 //$api = 'http://sso.allyes.me/api/getuserstates/12';
 //$admin = 'http://sso.allyes.me/admin/addapp/app';

 //js file is conducted by controller js function
 //简单路由指不包含变量的路由，每一条规则对应一个具体文件，
 //参数以 path/file/?arg1=var&arg2=var2 指出。
 $rules = array(
         //below is simple rule, all file will be in public/ directlly
        //'login' => 'login',
        //'login_chk' => 'login_chk',
        //'logout' => 'logout',
        //'prelogin'  => 'prelogin', 

        ':authen/:action' => 'authen/action',

        //below is concreate rule
        ':index' => 'home/index',
        'api/getuserstates/:id' => 'api/getuserstates/id',
        'admin/:action/:id' => 'admin/action/id',
        ':controller/:action/:id' => 'controller/action/id',

        //below is general rule
        //':controller/' => 'controller/index', //directory, conflict with .htaccess rewrite rule
        ':controller' => 'controller',
        ':controller/:action' => 'controller/action/',
 );



?>
