<?php
namespace sso\core\common;

class Filter
{
    public $res = true;
    public $err = NULL;

    public $rules = array(
        'email' => '/\w+@\w{3,}\.\w{2,}/',
        'password' => '/\w{6,}/',
        'name' => '/\w{4,}/',
    );

    public function __set($var, $val)
    {
        $rules = array_keys($this->rules);

        if(!in_array($var, $rules))
            return false;
        else
            $this->$var = $val;
        return $this;
    }

    public function __call($func, $arg)
    {
        $rules = array_keys($this->rules);
        if(!in_array($func, $rules))
            return false;
        if(count($arg) != 1)
            return false;
        
        $pattern = $this->rules[$func];
        $res = preg_match($pattern, array_pop($arg));

        if(!$res && !$this->err)
        {
            $this->err = $func.' should be more resonable!';
        }

        $this->$func = $res;
        $this->res &= $res;
        
        return $this;
    }
}
//end of class filter declaration
?>
