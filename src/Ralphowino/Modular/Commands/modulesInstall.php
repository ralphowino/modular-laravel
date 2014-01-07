<?php namespace Ralphowino\Modular\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\App;
use webignition\JsonPrettyPrinter\JsonPrettyPrinter;

class modulesInstall extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'modules:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install or Reinstall modules capability in your application';


	protected $reinstall = FALSE;

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

		if($this->config->isInstalled())
		{
			$this->error('Module already installed!');
			$reinstall = strtoupper($this->ask('Do you want to reinstall module [Y/N]'));
			if($reinstall == 'N') 	return FALSE;
			$this->reinstall = TRUE;			
		}
		
		//setup configuration for the modules application
		$this->config->set(array(			
			'appname' => $this->argument('appname'),			
			'folder' => $this->option('folder')
		));
		
		$this->setNamespace();
		//create modules folder
		$this->createDirectory($this->config->get('modules_path'));

		//create modules service provider 				
		$this->copyFolder(__DIR__.'/../Templates/modules-base', $this->config->get('modules_path'), $this->getVariables());

		//Add folder to composer json
		$this->updateComposer();		

		$this->config->set(array('installed'=>true));

		$this->addProvider();

		$this->info('Module system installed and ready to use'); 
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('appname', InputArgument::REQUIRED, 'The name of the application'),
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
			array('namespace', null, InputOption::VALUE_OPTIONAL, 'Namespace to use for all modules in your application', NULL),
			array('folder', null, InputOption::VALUE_OPTIONAL, 'Folder to store modules for your application', 'app/modules'),
		);
	}

	public function updateComposer($previous = null)
	{
		$composer_file = base_path().'/composer.json';
		if(is_readable($composer_file))
		{
			$composer_content = file_get_contents($composer_file);			
			$composer_content = json_decode($composer_content,true);
			if(!in_array($this->config->get('folder'), $composer_content['autoload']['classmap']))
			{
				$composer_content['autoload']['classmap'][]=$this->config->get('folder');
				$composer_content = json_encode($composer_content);
				$prettifier = new JsonPrettyPrinter();
				$composer_content = $prettifier->format($composer_content);
				$composer_content = str_replace('\/', '/', $composer_content);
				file_put_contents($composer_file, $composer_content);
				$this->info('Modules folder added into composer.json');
			}
			else
			{
				$this->info('Modules folder already in composer.json');
			}
		}
		$output = array();
		exec('composer dump-autoload', $output);
		foreach ($output as $line) {
			echo $line.PHP_EOL;
		}
	}

	public function addProvider()
	{
		$file = app_path().'/config/app.php';
		
		$config = require $file;
		$class = $this->config->get('namespace').'\ModulesServiceProvider';
		if(!in_array($class, $config['providers']))
		{
			$config['providers'][] = $class;
			$content = file_get_contents($file);
			$providers = substr($content, strpos($content, "'providers"));
			$providers = substr($providers, 0,strpos($providers, "),")+1);
			$replacement = "'providers' => array(".PHP_EOL;
			foreach ($config['providers'] as $provider) {
				$replacement .= "\t\t'$provider',".PHP_EOL;
			}
				$replacement .= "\t)";
			$content = str_replace($providers,$replacement , $content);
			file_put_contents($file, $content);
		}		
	}	

	public function setNamespace()
	{
		$namespace = $this->option('namespace');
		if($this->reinstall && is_null($namespace))
		{
			$namespace = $this->config->get('namespace');
		}
		$this->config->set(array('namespace' => $namespace));
	}

}
