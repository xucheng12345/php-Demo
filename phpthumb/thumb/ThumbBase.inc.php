<?php

abstract class ThumbBase 
{
	
	protected $imported;
	
	protected $importedFunctions;
	
	protected $errorMessage;
	
	protected $hasError;
	
	protected $fileName;
	
	protected $format;
	
	protected $remoteImage;
	
	protected $isDataStream;
	
	
	public function __construct ($fileName, $isDataStream = false)
	{
		$this->imported				= array();
		$this->importedFunctions	= array();
		$this->errorMessage			= null;
		$this->hasError				= false;
		$this->fileName				= $fileName;
		$this->remoteImage			= false;
		$this->isDataStream			= $isDataStream;
		
		$this->fileExistsAndReadable();
	}
	
	
	public function importPlugins ($registry)
	{
		foreach ($registry as $plugin => $meta)
		{
			$this->imports($plugin);
		}
	}
	
	
	protected function imports ($object)
	{
		
		$newImport 			= new $object();
		
		$importName			= get_class($newImport);
		
		$importFunctions 	= get_class_methods($newImport);
		
		
		array_push($this->imported, array($importName, $newImport));
		
		
		foreach ($importFunctions as $key => $functionName)
		{
			$this->importedFunctions[$functionName] = &$newImport;
		}
	}
	
	
	 
	 
	 
	protected function fileExistsAndReadable ()
	{
		if ($this->isDataStream === true)
		{
			return;
		}
		
		if (stristr($this->fileName, 'http://') !== false)
		{
			$this->remoteImage = true;
			return;
		}
		
		if (!file_exists($this->fileName))
		{
			$this->triggerError('Image file not found: ' . $this->fileName);
		}
		elseif (!is_readable($this->fileName))
		{
			$this->triggerError('Image file not readable: ' . $this->fileName);
		}
	}
	
	
	protected function triggerError ($errorMessage)
	{
		$this->hasError 	= true;
		$this->errorMessage	= $errorMessage;
		
		throw new Exception ($errorMessage);
	}
	
	
	public function __call ($method, $args)
	{
		if( array_key_exists($method, $this->importedFunctions))
		{
			$args[] = $this;
			return call_user_func_array(array($this->importedFunctions[$method], $method), $args);
		}
		
		throw new BadMethodCallException ('Call to undefined method/class function: ' . $method);
	}

   
    public function getImported ()
    {
        return $this->imported;
    }
    
    
    public function getImportedFunctions ()
    {
        return $this->importedFunctions;
    }
	
	
	public function getErrorMessage ()
	{
		return $this->errorMessage;
	}
	
	
	public function setErrorMessage ($errorMessage)
	{
		$this->errorMessage = $errorMessage;
	}
	
	
	public function getFileName ()
	{
		return $this->fileName;
	}
	
	
	public function setFileName ($fileName)
	{
		$this->fileName = $fileName;
	}
	
	
	public function getFormat ()
	{
		return $this->format;
	}
	
	
	public function setFormat ($format)
	{
		$this->format = $format;
	}
	
	
	public function getHasError ()
	{
		return $this->hasError;
	}
	
	
	public function setHasError ($hasError)
	{
		$this->hasError = $hasError;
	} 
	

}
