<?php namespace Ralphowino\Modular;

use Illuminate\Support\ServiceProvider;
use Ralphowino\Modular\Base\Config;

class ModularServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ralphowino/modular');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// $this->loadServices();
		$commands = array(
			'modules.install' => 'Ralphowino\Modular\Commands\ModulesInstall',
			'modules.create' => 'Ralphowino\Modular\Commands\ModulesCreate',
			);
		$this->registerModuleCommands($commands);
		$this->commands(array_keys($commands));
	}

	public function loadServices()
	{
		$config = new Config();
		if($config->isInstalled())
			$this->register($config->get('namespace').'\ModulesServiceProvider');
	}

	public function registerModuleCommands($commands)
	{
		foreach ($commands as $command => $class) 
		{
			$this->app[$command] = $this->app->share(function($app) use($class)
			{
				return new $class;
			});	
		}		
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}