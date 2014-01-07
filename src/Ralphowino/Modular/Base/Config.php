<?php namespace Ralphowino\Modular\Base;

use webignition\JsonPrettyPrinter\JsonPrettyPrinter;

class Config
{
	protected $file;

	protected $config = array();

	public function __construct()
	{
		$this->file = storage_path().'/meta/modules.json';
	}

	public function set($config, $value = null)
	{
		if(empty($this->config)) 
			$this->get();
		if(is_array($config))
		{
			foreach ($config as $key => $value)
			{
				$this->set_val($this->config, explode('.',$key),$value);
			}
		}
		else
		{
			$this->set_val($this->config, explode('.',$config),$value);
		}	
						
		$this->config['modules_path'] = base_path().'/'.$this->config['folder'];	
		if(!array_key_exists('namespace',$this->config) || is_null($this->config['namespace']))
		{
			$this->config['namespace'] = ucfirst($this->config['appname']).'\Modules';
		}
		$content = json_encode($this->config);
		$prettifier = new JsonPrettyPrinter();
		$content = $prettifier->format($content);
		$content = str_replace('\/', '/', $content);
		file_put_contents($this->file, $content);
	}

	function set_val(&$array,$path,$val) 
	{
		for($i=&$array; $key=array_shift($path); $i=&$i[$key]) 
		{
			if(!isset($i[$key])) $i[$key] = array();
		}
		$i = $val;
	}

	public function get($key = null)
	{
		if(empty($this->config)) $this->loadConfig();		

		if(is_null($key))
		{
			return $this->config;
		}
		$path = explode('.',$key);

		for($config=$this->config; $i=array_shift($path); $config=$config[$i]) 
		{
	      if(!isset($config[$i])) return false;
	    }
    	return $config;			
	}

	public function loadConfig()
	{
		if(file_exists($this->file)) 
			$this->config = json_decode(file_get_contents($this->file),true);
	}

	public function add($key, $value)
	{
		$config = $this->get($key);
		if(is_array($config))
		{
			$this->set(array_merge($config,array($key => $value)));
		}
		else
		{
			$this->set(array($key => $value));
		}
	}

	public function isInstalled()
	{
		return $this->get('installed');
	}
}
