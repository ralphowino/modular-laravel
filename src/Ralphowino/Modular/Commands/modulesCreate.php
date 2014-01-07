<?php namespace Ralphowino\Modular\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class modulesCreate extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'modules:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new module';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->module_name = str_singular($this->argument('name'));
		$this->module_parent = $this->option('parent');		
		$this->module_name_plural =str_plural($this->module_name);
		$this->setVariables(array(
			'module_name'=>$this->module_name,
			'module_name_lower'=>strtolower($this->module_name),
			'module_name_studly'=>studly_case($this->module_name),
			'module_name_plural'=>$this->module_name_plural,
			'module_name_plural_lower'=>strtolower($this->module_name_plural),
			'module_name_plural_studly'=>studly_case($this->module_name_plural),
		));
		$installer = (is_null($this->module_parent)?'parent':'child').'Module';
		$this->$installer();
		$this->call('dump-autoload');
		$this->finalize();
	}

	public function parentModule()
	{
		$this->namespace = $this->config->get('namespace').'\\'.studly_case($this->module_name_plural);
		$this->path = $this->config->get('modules_path').'/'.strtolower($this->module_name_plural);
		$this->uri = strtolower($this->module_name_plural);
		$this->model = $this->namespace.'\\'.studly_case($this->module_name_plural);
		$this->ancestor = strtolower($this->module_name_plural);
		$this->setVariables(array(			
			'ancestor' => $this->ancestor,
		));	
		$this->addProvider($this->namespace.'\\'.studly_case($this->module_name_plural).'ServiceProvider');
		$this->copyFolder(__DIR__.'/../Templates/modules-standard',$this->path,$this->getVariables());
		$this->addTestDirectory('./'.$this->config->get('folder').'/'.strtolower($this->module_name_plural).'/tests/');
	}

	public function childModule()
	{
		$this->parent = $this->config->get('modules.'.$this->module_parent);
		if(!$this->parent)
		{
			$this->error("Module $this->module_parent does not exist!");
			exit;
		}
		$this->module_parent_plural =str_plural($this->module_parent);
		$this->namespace = $this->parent['namespace'].'\\'.studly_case($this->module_name_plural);
		$this->path = $this->parent['folder'];
		$this->uri = $this->parent['uri'].'/{'.strtolower($this->module_parent).'}/'.strtolower($this->module_name_plural);
		$this->model = $this->namespace.'\\'.studly_case($this->module_name);
		$this->ancestor = $this->parent['ancestor'];
		$this->setVariables(array(
			'module_parent'=>$this->module_parent,
			'module_parent_lower'=>strtolower($this->module_parent),
			'module_parent_studly'=>studly_case($this->module_parent),
			'module_parent_plural'=>$this->module_parent_plural,
			'module_parent_plural_lower'=>strtolower($this->module_parent_plural),
			'module_parent_plural_studly'=>studly_case($this->module_parent_plural),
			'namespace' => $this->namespace,
			'parent_model' => $this->model,
			'parent_uri' => $this->uri,
			'uri_arguments'	=> $this->uriArguments($this->uri),
			'ancestor' => $this->ancestor,
		));	
		$this->copyFolder(__DIR__.'/../Templates/modules-standard-child',$this->path,$this->getVariables());
		$route_file = $this->path.'/routes.php';			
		$action = $this->namespace.'\\'.studly_case($this->module_name_plural).'Controller';
		$this->addRoute($route_file,'resource',$this->uri,$action);	
	}
	public function addProvider($class)
	{
		$providers = ($this->config->get('service_providers')?:array());
		if(!in_array($class,$providers))
		{
			$providers[]= $class;		
		}		
		$this->config->set(array('service_providers' => $providers));
	}

	public function addTestDirectory($directory)
	{		
		$file = base_path().'/phpunit.xml';
		$phpunit = simplexml_load_file($file);
		$directories = (Array) $phpunit->testsuites->testsuite[0]->directory;
		if(!in_array($directory, $directories))
		{
			$phpunit->testsuites->testsuite[0]->addChild('directory',$directory);
			$dom = new \DOMDocument('1.0');
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			$dom->loadXML($phpunit->asXML());		
			$xml = $dom->saveXML(); 
			file_put_contents($file, $xml);	
		}	
	}

	public function addRoute($file, $method, $uri ,$action)
	{
		$routes = file_get_contents($file);
		$routes .= PHP_EOL."Route::$method('$uri','$action');".PHP_EOL;
		file_put_contents($file, $routes);
	}

	public function uriArguments($uri, $args = '')
	{		
		echo $uri.PHP_EOL;
		if($start = strpos($uri,'{'))
		{
			$arg = substr($uri, $start+1);
			$arg = substr($arg, 0,strpos($arg,'}'));
			$args .= (strlen($args)>0?',':'').'$'.$arg;		
			$uri = substr($uri, strpos($uri,"{".$arg."}")+strlen($arg)+2);		
			return $this->uriArguments($uri, $args);
		}
		// dd($args);
		return $args;
	}

	public function finalize()
	{
		$this->config->set('modules.'.$this->module_name, 
			array(
				'namespace'=> $this->namespace,
				'folder' => $this->path,
				'uri' => $this->uri,
				'model' => $this->model,
				'ancestor' => $this->ancestor,
				));
		$this->info('Module '.$this->module_name.' created!');
	}
	

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Name of the module'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('fields', null, InputOption::VALUE_OPTIONAL, 'Fields to be added into the module', array()),
			array('parent', null, InputOption::VALUE_OPTIONAL, 'Parent module', null),
		);
	}

}
