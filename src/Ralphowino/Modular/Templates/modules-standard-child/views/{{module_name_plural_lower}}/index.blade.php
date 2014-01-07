<h1>Manage {{module_name_plural_studly}}</h1>
<em>Display all {{module_name_plural_lower}}</em>

<ul>
	<li>
		<a href="{{route('{{parent_uri}}.create',$parent)}}">Create New</a>
	</li>
	<li>
		<a href="{{route('{{parent_uri}}.show',array($parent,1))}}">View {{module_name_plural_lower}} with id 1</a>
	</li>
	<li>
		<a href="{{route('{{parent_uri}}.edit',array($parent,1))}}">Edit {{module_name_plural_lower}} with id 1</a>
	</li>
</ul>


