<?php

define('THUMBLIB_BASE_PATH', dirname(__FILE__));
define('THUMBLIB_PLUGIN_PATH', THUMBLIB_BASE_PATH . '/thumb_plugins/');
define('DEFAULT_THUMBLIB_IMPLEMENTATION', 'gd');


require_once THUMBLIB_BASE_PATH . '/PhpThumb.inc.php';

require_once THUMBLIB_BASE_PATH . '/ThumbBase.inc.php';

require_once THUMBLIB_BASE_PATH . '/GdThumb.inc.php';


class PhpThumbFactory
{
	
	public static $defaultImplemenation = DEFAULT_THUMBLIB_IMPLEMENTATION;
	
	public static $pluginPath = THUMBLIB_PLUGIN_PATH;
	
	
	public static function create ($filename = null, $options = array(), $isDataStream = false)
	{
		
		$implementationMap = array
		(
			'imagick'	=> 'ImagickThumb',
			'gd' 		=> 'GdThumb'
		);
		
		
		$pt = PhpThumb::getInstance();
		
		$pt->loadPlugins(self::$pluginPath);
		
		$toReturn = null;
		$implementation = self::$defaultImplemenation;
		
		
		if ($pt->isValidImplementation(self::$defaultImplemenation))
		{
			$imp = $implementationMap[self::$defaultImplemenation];
                        
			$toReturn = new $imp($filename, $options, $isDataStream);
		}
		
		else if ($pt->isValidImplementation('gd'))
		{
			$imp = $implementationMap['gd'];
			$implementation = 'gd';
			$toReturn = new $imp($filename, $options, $isDataStream);
		}
		
		else
		{
			throw new Exception('You must have either the GD or iMagick extension loaded to use this library');
		}
		
		$registry = $pt->getPluginRegistry($implementation);
		$toReturn->importPlugins($registry);
		return $toReturn;
	}
}