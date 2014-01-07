<h1>Manage {{module_name_plural_studly}}</h1>
<em>Update {{module_name_lower}} with id {{$id}}</em>
<a href="{{route('{{module_name_plural_lower}}.index')}}">Back to list</a><br>

{{Form::open(array('route'=>array('{{module_name_plural_lower}}.update',$id), 'method'=>'PUT'))}}
{{Form::submit('Update')}}
{{Form::close()}}
{{Form::open(array('route'=>array('{{module_name_plural_lower}}.destroy',$id), 'method'=>'DELETE'))}}
{{Form::submit('Delete')}}
{{Form::close()}}
