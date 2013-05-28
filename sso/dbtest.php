<?php

use sso\core\common as cm;
use sso\core\config as sc;

require 'core/config/Config.php';
require 'core/common/Singleton.php';
require 'core/common/DB.class.php';
    
$db = cm\DB::getInstance(); 


echo '<pre>';

//var_dump($db);
$sql = 'select * from users;';

print_r($db->query($sql));


echo '</pre>';



?>
