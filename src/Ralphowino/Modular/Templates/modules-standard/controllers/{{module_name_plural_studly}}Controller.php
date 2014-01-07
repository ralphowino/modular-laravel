<?php namespace {{namespace}}\{{module_name_plural_studly}};

use BaseController;
use View;

class {{module_name_plural_studly}}Controller extends BaseController
{
	protected ${{module_name_plural_lower}};

	public function __construct({{module_name_studly}} ${{module_name_plural_lower}})
	{
		$this->{{module_name_plural_lower}} = ${{module_name_plural_lower}};
	}

	public function index()
	{
		${{module_name_plural_lower}} = $this->{{module_name_plural_lower}}->get(); 
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.index')->with('{{module_name_plural_lower}}', ${{module_name_plural_lower}});
	}

	public function show($id)
	{
		${{module_name_lower}} = $this->{{module_name_plural_lower}}->find($id); 
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.show')->with('id',$id)->with('{{module_name_lower}}', ${{module_name_lower}});;
	}

	public function create()
	{
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.create');
	}

	public function store()
	{
		return "{{module_name_plural_studly}} store";
	}

	public function edit($id)
	{
		return View::make('{{ancestor}}::{{module_name_plural_lower}}.update')->with('id',$id);
	}

	public function update($id)
	{
		return "{{module_name_plural_studly}} update  id: $id";
	}

	public function destroy($id)
	{
		return "{{module_name_plural_studly}} destroy  id: $id";
	}
}