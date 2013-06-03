<?php 
namespace sso\core\common;

use sso\core\config as sc;

/**
 * Router sigleton class
 * For URI dispatch 
 * All URI should be site.com/class/function/id like
 * 
 */
	
class Router extends Singleton
{
	protected $uri;
	protected $uri_array  = array();
    protected $rules = array();
	
	/**
     * parseURI
     *
     * parse uri and dispatch router according to 
     * the router rules. 
	 * 
     * @access  private
     * @return  boolean Return true if router dispatched.
	 */
	private function parseURI()
	{
        if($innerpath = $this->match_router())
        {
            $this->dispatch($innerpath);
            return true;
        }
        $this->page404();
	}

    /**
     * match_router
     *
     * Match router rules and return array of final real path elements.
     * 
     * @access  private
     * @return  mixed   return array if rule matched or false when all rules mismatched.
     */
    private function match_router()
    {
        if(!$this->rules)
        {
            Log::error('No rule found in router!');
            return false;
        }

		$uri = ltrim($this->uri, "/");
		$uris = explode("/", $uri);
        $pathLen = count($uris);
        if(substr($uris[$pathLen-1], 0, 1) == '?') //get 参数
            array_pop($uris);
        $pathLen = count($uris);

        foreach($this->rules as $r => $v)
        {
            $keys = explode("/", $r);
            $no = count($keys);
            $matched = false;

            //when deal with concrete or general router
            if(!preg_match('/\?/', $uri) && $pathLen != $no)
            {
                continue;
            }

            //when deal with concrete or general router
            for($i = 0; $i < $no; $i++)
            {
                if(substr($keys[$i], 0, 1) == ':')
                    $maps[substr($keys[$i], 1)] = $uris[$i];
                else if($keys[$i] != $uris[$i])
                    break;
                else
                    $maps[$keys[$i]] = $uris[$i];

                if($i + 1 == $no)
                {
                    $matched = $v;
                    break;
                }
            }

            if($matched)
            {
                return $this->form_real_path($matched, $maps);
            }
        }
        return $matched;
    }

    /**
     * form_real_path
     *
     * Make an array of real path parts.
     *
     * @access  private
     * @param   string  router rule value
     * @param   array   array of rule elements values  
     * @return  array
     */
    private function form_real_path($map, $warehouse)
    {
        $path = explode("/", $map);
        $dos = count($path);
        $innerpath = array();

        for($i = 0; $i < $dos; $i++)
        {
            $val = $path[$i];
            if($i == 1 && !$warehouse[$val])
            {
                $warehouse[$val] = 'index';
            }
            if(isset($warehouse[$val]))
                $innerpath[] = $warehouse[$val]; 
        }
        return $innerpath;
    }

    /**
     * dispatch
     *
     * Dispatch uri in terms of uri array.
     * So far /model/action/parametes kind uri
     * required.
     *
     * @access  private
     * @param   array   $mass
     * @return  NULL
     */
    private function dispatch($maas)
    {
		Log::access(implode('/', $maas));
        if(!is_array($maas))
            return false;
        $maa = count($maas); 
        
        if($maa == 1)
        {
            $file = array_shift($maas);
            $file .= '.php';
            $file = PUB.$file;
            require $file;
        }
        else
        {
            $mod = ucfirst(array_shift($maas));
            $mod = "sso\core\common\\".$mod;
            $obj = new $mod();
            $action = array_shift($maas);

            if($obj && in_array($action, get_class_methods($obj)))
            {
                $obj->$action($maas);
            }
            else
            {
                $this->page404();
            }
        }
    }

    private function page404()
    {
        Log::access('404: '.$this->uri);
        die("Page 404, what you seek is not real.");
    }

	public function run($rules)
	{
        //$this->uri = $_SERVER['PATH_INFO'];
        $this->uri = $_SERVER['REQUEST_URI'];

        //default home page
        if($this->uri == '/')
        {
            $this->uri = sc\Config::$ssohomepage; 
        }
        $this->rules = $rules;
		$this->parseURI();
	}
	
}

?>
