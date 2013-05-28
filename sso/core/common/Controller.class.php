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

        //no caching
        $this->tpl->cache_lifetime = 0;
        $this->tpl->caching = false;

        $this->tpl->debugging = sc\Config::$smartyDebugging;
    }

    /**
     * flush
     *
     * Flush out template page
     *
     */
    protected function flush($tpl='')
    {
        if(empty($tpl))
        {
            $stack = debug_backtrace();
            $tpl = $stack[1]['function'];
            unset($stack);
        }

        $fullpath = get_class($this);
        $class = array_pop(explode('\\', $fullpath));
        $tpl = $class.'/'.$tpl.'.tpl.html';
        $this->tpl->display(TPL.$tpl); 
    }

    protected function set($var, $val)
    {
        $this->tpl->assign($var, $val);
    }
}
//end of class Controller declaration
?>
