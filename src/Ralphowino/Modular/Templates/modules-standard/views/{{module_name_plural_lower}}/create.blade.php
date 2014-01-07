<h1>Manage {{module_name_plural_studly}}</h1>
<em>Create a new {{module_name_lower}}</em>
<a href="{{route('{{module_name_plural_lower}}.index')}}">Back to list</a><br>

{{Form::open(array('route'=>'{{module_name_plural_lower}}.store', 'method'=>'POST'))}}
{{Form::submit('Create')}}
{{Form::close()}}
