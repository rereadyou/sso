<?php
namespace sso\core\common;

use sso\core\common as cm;
use sso\core\common\functions as sf;

/**
 * Ao
 *
 * Array of Objects(of same kind).
 * Can be used to manipulate sql result record.
 */
class Ao extends \ArrayObject
{
    public $oa = NULL;
    public $attrs = array();
    private $funcs = array();

    public function __construct($a)
    {
        if(is_object($a))
            $a[] = $a;
        if(!is_array($a))
            return false; 
        if(count($a) == 0)
            return false;
        //if(!is_object($a[0]))
            //return false;
        //if(is_object($a) && get_class($a) == __CLASS__)
         //   return $a;

        $this->oa = $a;
        $obj = $a[0];

        if(is_object($obj))
        {
            $class = get_class($obj);

            $this->attrs = array_keys(get_object_vars($obj));
            $this->funcs = get_class_methods($class);
        }

        parent::__construct($a);
    }

    /**
     * __call
     *
     * Call model funcs or attrs
     *
     * @access  public
     * @param   array   $args   This args array should always contains one char.
     * @return  object  Ao
     */
    public function __call($func, $args)
    {
        $as = implode(', ', $args);
        $an = count($args); 
        $isuniq = ($an > 0 && $args[0]=='#')?true:false;

        if($an > 1)
        {
            cm\Log::error('Ao take '.$an.' args error!');
            return false;
        }

        $v_m = array_merge($this->attrs, $this->funcs); 
        $res = array();

        foreach($this->oa as $k => $v)
        {
            if(in_array($func, $v_m))
            {
                $rs = $v->$func;
                if($an == 0)
                {
                    is_object($rs) && get_class($rs)=='Ao' 
                        ? $res = array_merge($res, $rs->oa) 
                        : $res[] = $rs;
                }
                if($an == 1)
                {
                    if(!isbid($rs, $a[0]))
                        continue;
                    if(is_object($rs))
                    {
                        $res[] = (array)$rs;
                    }
                    else if(isbid($v, $m.':'.$a[0]));
                    {
                        is_object($rs)&&get_class($rs)=='Ao' 
                            ? $va=array_merge($va, $rs->oa) 
                            : $va[]=$rs;
                    }
                }
            }
        }

        return $isuniq ? new Ao(array_unique($res)) 
                        : new Ao($res);
    }

    public function size()
    {
        return count($this->oa);
    }
    

    public function __get($attr)
    {
        if(in_array($attr, $this->attrs))
            return $this->$attr();
    }







} //end of Ao class declaration
?>
