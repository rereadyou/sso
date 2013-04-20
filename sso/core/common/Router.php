<?php 

namespace sso\core\common;

/**
 * Router sigleton class
 * For URI dispatch 
 * All URI should be site.com/class/function/id like
 * 
 * @author rereadyou
 *
 */
	
class Router extends Singleton
{
	protected $uri;
	protected $uri_array  = array();
	
	public function __construct()
	{
		$this->uri = $_SERVER['REQUEST_URI'];
	}
		
	/**
	 * parse uri to be class/function/id
	 * 
	 */
	private function parseURI()
	{
		// /class/function/id
		$uri = trim($this->uri, "/");
		$elem = explode("/", $uri);
		
		switch(count($elem))
		{
			case 1:
				$this->dispatch_module($elem[0]);
				break;
			case 2:
				$this->dispatch_action($elem); //mod/action&query
				break;
			case 3:
				$this->dispatch_page($elem);
				break;
			default:
				Log::error("Client request #".$uri."# error: no such URI!");
				$this->dispatch_page(array_slice($elem, 0, 3));
				break;
		}
		return $this;
	}
	/**
	 * dispatch uri to module/page/id
	 */
	public function dispatch($path)
	{
		Log::access($path);
		echo $this->uri;
	}
	
	public function dispatch_module($uri)
	{
		if($uri == 'index' || $uri == 'index.php')
		{
			$this->dispatch('index');
		}
		else if($uri)
		{
			$mod = new $uri;
			$this->dispatch($mod->index());	
		}
	}
	
	public function dispatch_action($path)
	{
		list($module, $action) = each($path);
		
		$mod = new $module;
		$mod->$action();

	}
	
	public function run()
	{
		$this->parseURI();
	}
	
	public function __destruct()
	{
		//self::$instance = '';
	}
}

?>