@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Channels</h2>

			<a href="{{ route('admin.channel.create') }}" class="button right">Add Channel</a>

			<table class="small-12">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($channels as $channel)
						<tr>
							<td>{{ $channel->id }}</td>
							<td>{{ $channel->name }}</td>
							<td><a href="{{ route('admin.channel.edit', ['channel' => $channel]) }}">Edit</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>
	</div>

@endsection