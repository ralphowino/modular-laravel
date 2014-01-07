<?php namespace {{namespace}}\{{module_name_plural_studly}};

use TestCase;

class {{module_name_plural_studly}}ControllerTest extends TestCase
{
	public function testIndex()
	{
		$this->action('GET', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@index');
		$this->assertResponseOk();
	}

	public function testShow()
	{		
		$this->action('GET', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@show', array('id' => 1));
		$this->assertResponseOk();
	}

	public function testCreate()
	{
		$this->action('GET', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@create');
		$this->assertResponseOk();
	}

	public function testStore()
	{
		$this->action('POST', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@store');
		$this->assertResponseOk();
	}

	public function testEdit()
	{
		$this->action('GET', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@edit', array('id' => 1));
		$this->assertResponseOk();
	}

	public function testUpdate()
	{
		$this->action('PUT', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@update', array('id' => 1));
		$this->assertResponseOk();
	}

	public function testDestroy()
	{
		$this->action('DELETE', '{{namespace}}\{{module_name_plural_studly}}\{{module_name_plural_studly}}Controller@destroy', array('id' => 1));
		$this->assertResponseOk();
	}
}