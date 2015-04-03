@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Streams</h2>

			<a href="{{ route('admin.stream.create') }}" class="button right">Add Stream</a>

			<table class="small-12">
				<thead>
					<tr>
						<th>ID</th>
						<th>Title</th>
						<th>Channel</th>
						<th>Show</th>
						<th>State</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($streams as $stream)
						<tr>
							<td>{{ $stream->id }}</td>
							<td>{{ $stream->title }}</td>
							<td>{{ $stream->channel->name }}</td>
							<td>{{ $stream->show->name }}</td>
							<td>{{ $stream->state }}</td>
							<td><a href="{{ route('admin.stream.edit', ['stream' => $stream]) }}">Edit</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>
	</div>

@endsection