<?php namespace {{namespace}}\{{module_parent_plural_studly}}\{{module_name_plural_studly}};

use Eloquent;

class {{module_name_studly}} extends Eloquent
{
	public function {{module_parent_plural_lower}}()
	{
		return $this->belongsTo('{{module_parent_studly}}');
	}
}