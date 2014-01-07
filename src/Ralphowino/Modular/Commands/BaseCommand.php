<?php namespace Ralphowino\Modular\Commands;

use Illuminate\Console\Command;
use Ralphowino\Modular\Base\Config;

class BaseCommand extends Command
{	
	protected $config;

	function __construct()
	{
		parent::__construct();
		$this->config = new Config;		
	}
	
	public function getVariables()
	{
		if(empty($this->variables)) $this->setVariables();		
		return $this->variables;
	}

	public function setVariables($variables = array())
	{
		if(empty($this->variables))
		{
			$this->variables = $this->config->get();
			$this->variables['Appname'] = ucfirst($this->variables['appname']);
		}
		$this->variables = array_merge($this->variables, $variables);		
	}

	public function createDirectory($path)
	{		
		if(!file_exists($path))
		{
			return mkdir($path, 0644, true);
		}
	}

	public function makeFileName($filename, $variables)
	{

		#check if filename needs parsing		
		if(is_int(strpos($filename, '{{')))
		{			
			$var = substr($filename, strpos($filename,'{{')+2);			
			$var = substr($var,0,strpos($var, '}}'));
			$filename = str_replace('{{'.$var.'}}',$variables[$var] , $filename);			
			return $this->makeFileName($filename, $variables);
		}				
		return $filename;
	}

	public function createFile($input,$output,$variables)
	{

		if(!file_exists($output))
		{	
			//parse source file and replace variables
			$content = file_get_contents($input);			
			foreach ($variables as $var => $value) 
			{
				if(is_array($value)) continue;				
				$content = str_replace('{{'.$var.'}}', $value, $content);
			}

			//Ensure folder is available and writeable
			$dir = dirname($output);			
			if(!file_exists($dir))
			{
				$this->createDirectory($dir);
			}

			//create file
			if(file_put_contents($output, $content) || file_exists($output))
				$this->info($output.' created');
			else
				$this->error($output.' not created');
		}
		else
		{
			$this->error($output.' already exists');	
		}
	}

	public function copyFolder($source,$destination,$variables)
	{
		$files = scandir($source);	

		foreach ($files as $file) 
		{			
			$input_path = $source.'/'.$file;			
			$output_path = $destination.'/'.$this->makeFileName($file,$variables);
			
			if(is_file($input_path))
			{				
				$this->createFile($input_path,$output_path,$variables);				
			}
			elseif(is_dir($input_path) && $file !='.' && $file !='..')
			{				
				$this->createDirectory($output_path);
				$this->copyFolder($input_path, $output_path, $variables);
			}

		}
	}
}