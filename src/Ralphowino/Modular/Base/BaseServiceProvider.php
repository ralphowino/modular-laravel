<?php namespace Ralphowino\Modular\Base;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function boot()
    {        
        $this->config = new Config();
        $this->module_dir = base_path().'/'.$this->config->get('modules_path');
        //Run if module is passed in arguements
        if ($module = $this->getModule(func_get_args()))
        {            
            // register module as a package
            $this->package('modules' . $module, $module, $this->module_dir.'/'. $module);         
        }
    }

    public function register()
    {        
        //Run if module is passed in arguements
        if ($module = $this->getModule(func_get_args()))
        {            
            $this->autoloads($module,array('routes.php','filters.php','start.php'));
        }
    }

    public function getModule($args)
    {
        $module = (isset($args[0]) and is_string($args[0])) ? $args[0] : null;
        return $module;
    }

    public function autoloads($module, $files)
    {
        $this->config = new Config();
        $this->module_dir = base_path().'/'.$this->config->get('modules_path');
        foreach ($files as $file)
        {
            $file = $this->module_dir. '/'.$module .'/'. $file;            
            if (file_exists($file)) require $file;
        }
    }
}