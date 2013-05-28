<?php

namespace sso\core\common;

use sso\core\common as cm;
use sso\core\config as sc;

class Controller
{
    protected $tpl = NULL;

    public function __construct()
    {
        //Class Smarty is in Global namespace
        $this->tpl = new \Smarty; 

        $this->tpl->setTemplateDir(sc\Config::$smartyTemplateDir);
        $this->tpl->setCompileDir(sc\Config::$smartyCompileDir);
        $this->tpl->setConfigDir(sc\Config::$smartyConfigDir);
        $this->tpl->setCacheDir(sc\Config::$smartyCacheDir);

        $this->tpl->debugging = sc\Config::$smartyDebugging;
    }

    public function flush($tpl='')
    {
        if(empty($tpl))
        {
            $tpl = 'index';
        }
        $fullpath = get_class($this);
        $class = array_pop(explode('\\', $fullpath));
        $tpl = $class.'/'.$tpl.'.tpl.html';
        $this->tpl->display(TPL.$tpl); 
    }

    public function set($var, $val)
    {
        $this->tpl->assign($var, $val);
    }
}
//end of class Controller declaration
?>
