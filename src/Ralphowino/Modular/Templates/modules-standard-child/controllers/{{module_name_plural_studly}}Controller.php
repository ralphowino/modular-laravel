<?php namespace {{namespace}};

use BaseController;
use View;

class {{module_name_plural_studly}}Controller extends BaseController
{	

	public function index({{uri_arguments}})
	{		
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.index');
	}

	public function show({{uri_arguments}},$id)
	{		
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.show')->with('id',$id);
	}

	public function create({{uri_arguments}})
	{		
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.create');
	}

	public function store({{uri_arguments}})
	{
		return "{{module_name_plural_studly}} store";
	}

	public function edit({{uri_arguments}},$id)
	{		
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.update')->with('id',$id);
	}

	public function update({{uri_arguments}},$id)
	{
		return "{{module_name_plural_studly}} update  id: $id";
	}

	public function destroy({{uri_arguments}},$id)
	{
		return "{{module_name_plural_studly}} destroy  id: $id";
	}
}