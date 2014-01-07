<?php namespace {{namespace}};

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider 
{
	public function boot()
	{

	}

	public function register()
	{
		$meta = file_get_contents(storage_path().'/meta/modules.json');
		$meta = json_decode($meta,true);
		if(isset($meta['service_providers']))
		{
			$providers = $meta['service_providers'];
			foreach ($providers as $provider) {
				$this->app->register($provider);
			}
		}
		
	}
}