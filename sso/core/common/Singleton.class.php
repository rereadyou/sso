<?php

namespace sso\core\common;

/**
 * Singleton abstrcat class
 * 
 * @author rereadyou
 *
 */
abstract class Singleton {
	
	/**
	 * Sole Handler of singleton class
	 * 
	 * However static $instance has to be an array,
	 * because if not, all sub-class instance will 
	 * share the same $instance no matter what sub-class
	 * really is.
	 * 
	 * @var unknown_type
	 */
	private static $instance = array();
	
	/**
	 * initializer
	 * @return \insyeah\core\common\unknown_type
	 */
	private function __construct() 
	{
		//
	}
	
	/**
	 * interface
	 * 
	 * In PHP self always refers to the class where method is defined
	 * @return \insyeah\core\common\unknown_type
	 */
	final public static function instance()
	{
		$className = get_called_class();
		
		if(!isset(self::$instance[$className]))
		{
			self::$instance[$className] = new $className;			
		}
		
		return self::$instance[$className];
	}
	
	/**
	 * prevent object to be copied
	 */
	final public function __clone() {}
	
	
	
	
}//end of abstract class Singleton defination

?>