<h1>Manage {{module_name_plural_studly}}</h1>
<em>Display all {{module_name_plural_lower}}</em>
<a href="{{route('{{module_name_plural_lower}}.create')}}">Create New</a>
<ul>
	<li>
		
	</li>
	<li>
		<a href="{{route('{{module_name_plural_lower}}.show',1)}}">View {{module_name_plural_lower}} with id 1</a>
	</li>
	<li>
		<a href="{{route('{{module_name_plural_lower}}.edit',1)}}">Edit {{module_name_plural_lower}} with id 1</a>
	</li>
</ul>

@foreach(${{module_name_plural_lower}} as ${{module_name_lower}})
<p>
	{{${{module_name_lower}}}}
</p>
@endforeach
