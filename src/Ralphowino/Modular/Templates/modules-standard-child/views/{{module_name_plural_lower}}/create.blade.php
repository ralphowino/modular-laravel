<h1>Manage {{module_name_plural_studly}}</h1>
<em>Create a new {{module_name_lower}}</em>
<a href="{{route('{{parent_uri}}.index',$parent)}}">Back to list</a><br>

{{Form::open(array('route'=>'array({{parent_uri}}.store',$parent) 'method'=>'POST'))}}
{{Form::submit('Create')}}
{{Form::close()}}
