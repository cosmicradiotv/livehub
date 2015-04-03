@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Shows</h2>

			<a href="{{ route('admin.show.create') }}" class="button right">Add show</a>

			<table class="small-12">
				<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				@foreach($shows as $show)
					<tr>
						<td>{{ $show->id }}</td>
						<td>{{ $show->name }}</td>
						<td><a href="{{ route('admin.show.edit', ['show' => $show]) }}">Edit</a></td>
					</tr>
				@endforeach
				</tbody>
			</table>

		</div>
	</div>

@endsection()