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
    public function flush($tpl='')
    {
        $tpl = $this->get_tpl($tpl);
        if($tpl)
            $this->tpl->display(TPL.$tpl); 

        return $this;
    }

    /**
     * get_tpl
     *
     * Get template name with full path
     *
     * @access  protected
     * @param   string  $tpl    Tpl name
     * @return  string  $tpl full name
     */
    protected function get_tpl($tpl='')
    {
        if(empty($tpl))
        {
            $stack = debug_backtrace();
            //have to chk the stack layer
            $tpl = $stack[2]['function'];
            unset($stack);
        }

        $fullpath = get_class($this);
        $class = array_pop(explode('\\', $fullpath));
        $tpldir = strtolower($class);
        echo $tpl = $tpldir.'/'.$tpl.'.tpl.html';
        return $tpl;
    }

    /**
     * set
     *
     * Set tpl variable
     *
     * @access  protected
     * @param   string  $var    variable name
     * @param   mixed   $val    variable value
     * @return  $this
     */
    protected function set($var, $val)
    {
        $this->tpl->assign($var, $val);
        return $this;
    }

    /**
     * get_flush
     *
     * Get tpl contents 
     *
     * @access  protected
     * @param   string  $tpl    Template name
     * @return  string  Tpl contents
     */
    protected function get_flush($tpl='')
    {
        $tpl = $this->get_tpl($tpl);
        return $this->tpl->fetch($tpl); 
    }

    /**
     * fetch
     *
     * Fetch tpl by controller and method
     *
     * @access  protected
     * @param   object  $controller 
     * @param   function    $method
     * @return  
     */
    protected function fetch_flush($view)
    {
        $view = explode('/', $view);
        if(count($view) != 2)
            return false;

        list($controller, $method) = $view;
        $obj = 'sso\core\common\\'.ucwords($controller);
        $obj = new $obj();
        $obj->$method();
        return $this;
    }

    /**
     * out
     *
     * Just echo an string
     *
     * @access  protected
     * @param   string  $msg
     * @return  $this
     */
    protected function out($msg)
    {
        echo $msg;
        return $this;
    }

    public function js($js)
    {
        $js = array_shift($js);
        $js = BASE_PATH.PUB.'js/'.$js;
        echo file_get_contents($js);
    }

    public function css($css)
    {
        $css = array_shift($css);
        $css = BASE_PATH.PUB.'css/'.$css;
        echo file_get_contents($css);
    }

    public function jumpto($uri)
    {
        header('Location: http://sso.allyes.me/'.$uri);
        exit;
    }
}
//end of class Controller declaration
?>
