<?php

class PhpThumb
{
	
	protected static $_instance;
	
	protected $_registry;
	
	protected $_implementations;
	
	
	public static function getInstance ()
	{
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	private function __construct ()
	{
		$this->_registry		= array();
		$this->_implementations	= array('gd' => false, 'imagick' => false);
		
		$this->getImplementations();
	}
	
	
	private function getImplementations ()
	{
		foreach($this->_implementations as $extension => $loaded)
		{
			if($loaded)
			{
				continue;
			}
			
			if(extension_loaded($extension))
			{
				$this->_implementations[$extension] = true;
			}
		}
	}
	
	
	public function isValidImplementation ($implementation)
	{
		if ($implementation == 'n/a')
		{
			return true;
		}
		
		if ($implementation == 'all')
		{
			foreach ($this->_implementations as $imp => $value)
			{
				if ($value == false)
				{
					return false;
				}
			}
			
			return true;
		}
		
		if (array_key_exists($implementation, $this->_implementations))
		{
			return $this->_implementations[$implementation];
		}
		
		return false;
	}
	
	
	public function registerPlugin ($pluginName, $implementation)
	{
		if (!array_key_exists($pluginName, $this->_registry) && $this->isValidImplementation($implementation))
		{
			$this->_registry[$pluginName] = array('loaded' => false, 'implementation' => $implementation);
			return true;
		}
		
		return false;
	}
	
	
	public function loadPlugins ($pluginPath)
	{
		
		if (substr($pluginPath, strlen($pluginPath) - 1, 1) == '/')
		{
			$pluginPath = substr($pluginPath, 0, strlen($pluginPath) - 1);
		}
		
		if ($handle = opendir($pluginPath))
		{
			while (false !== ($file = readdir($handle)))
			{
				if ($file == '.' || $file == '..' || $file == '.svn')
				{
					continue;
				}
				
				include_once($pluginPath . '/' . $file);
			}
		}
	}
	
	
	public function getPluginRegistry ($implementation)
	{
		$returnArray = array();
		
		foreach ($this->_registry as $plugin => $meta)
		{
			if ($meta['implementation'] == 'n/a' || $meta['implementation'] == $implementation)
			{
				$returnArray[$plugin] = $meta;
			}
		}
		
		return $returnArray;
	}
}
