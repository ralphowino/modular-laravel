<h1>Manage {{module_name_plural_studly}}</h1>
<em>Display {{module_name_lower}} with id {{$id}}</em>

<a href="{{route('{{parent_uri}}.index',$parent)}}">Back to list</a><br>
<a href="{{route('{{parent_uri}}.edit',array($parent,$id))}}">Edit {{module_name_lower}} with id {{$id}}</a><br>

{{Form::open(array('route'=>array('{{parent_uri}}.destroy',$parent,$id), 'method'=>'DELETE'))}}
{{Form::submit('Delete')}}
{{Form::close()}}


