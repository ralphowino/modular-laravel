<?php namespace {{namespace}}\{{module_name_plural_studly}};

use Ralphowino\Modular\Base\BaseServiceProvider;

class {{module_name_plural_studly}}ServiceProvider extends BaseServiceProvider
{
	public function boot()
	{
		parent::boot('{{module_name_plural_lower}}');
	}

	public function register()
	{
		parent::register('{{module_name_plural_lower}}');
	}
}