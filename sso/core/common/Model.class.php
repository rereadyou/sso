<?php

namespace sso\core\common;

use sso\core\common as cm;
use sso\core\model as md;

class Model
{
    protected $idb = NULL; //cm\DB::getInstance();
    public static $db = NULL;

    public function __construct()
    {
        $this->idb = cm\DB::getInstance();
    }

    public function __get($attr)
    {
        $concreteModel = get_class($this);
        $attrs = array_keys(get_class_vars($concreteModel));
        if(in_array($attr, $attrs))
        {
            return $this->$attr;
        }
        Log::error('Call to undefined class attribute: '.$attr.' in class '.__CLASS__);
        return NULL;
    } 

    public function __set($attr, $val)
    {
        if($this->$attr)
        {
           $this->$attr = $val; 
           return $this;
        }
        Log::error('Try set undefined class attribute: '+$attr+' = '+$val);
        return NULL;
    }

    public function __call($func, $args)
    {
        //set attribute
        $concreteModel = get_class($this);
        $attrs = array_keys(get_class_vars($concreteModel));

        if(!in_array($func, $attrs))
            return false;
        //need to set id attribution when use find
        //if($func == 'id' && count($args))
            //return false;
        else if(count($args) != 1)
            return false;

        {
            $this->$func = $args[0];
            return $this;
        }

        return $this;
    }

    public static function get_model_name()
    {
        return __CLASS__;
    }

    public static function __callStatic($func, $args)
    {
        //this depends on php5.3 and later version 
        //LSB(late static binding) function support
        //$class = static::get_model_name();
        $class = get_called_class();
        $table = array_pop(explode('\\', $class));
        $attrs = array_keys(get_class_vars($class));

        if($func === 'find')
        {
            //should allways find by id
            $field = 'id'; 
            if(!count($args))
            {
                cm\Log::error('Use find without id provided.');
                die('Missues find() query!');
            }
        }

        if($func === 'find_all')
        {
            $field = '';
        }

        if('find_by_' == substr($func, 0, 8))
        {
            $field = substr($func, 8);
        }

        $val = '';
        if(is_array($args))
            $val = array_pop($args);

        $rs = self::$db->find($table, $field, $val);

        $objs = self::mass_draw($rs);

        return new cm\Ao($objs);
    }

    private static function mass_draw($mass)
    {
        $objs = array();
        foreach($mass as $k => $v)
        {
            $objs[] = self::draw($v);
        }
        return $objs;
    }

    private static function draw($parts)
    {
        $class = get_called_class();
        $obj = new $class;

        foreach($parts as $k => $v)
        {
            if(is_int($k))
                continue;
            $obj->$k($v);
        }
        return $obj;
    }

    public function save()
    {

        $class = get_called_class();
        $table = array_pop(explode('\\', $class));
        $attrs = array_keys(get_class_vars($class));

        $dbfields = self::$db->get_field_names($table);
        $mapps = array();
        foreach($dbfields as $f)
        {
            if(!$this->$f)
                continue;
            $mapps[$f] = $this->$f; 
        }
        
        $id = self::$db->replace($table, $mapps);        
        $this->id($id);

        //$dbobj = md\$class::find_by_id($id);
        //foreach($fields as $f)
        //{
            //$this->$f($dbobj->$f());
        //}

        return $this;
    }

    public function delete()
    {
        $class = get_called_class();
        $table = array_pop(explode('\\', $class));

        return self::$db->delete($table, $this->id);
        
    }

} //end of class Module declaration

Model::$db = cm\DB::getInstance();
?>
