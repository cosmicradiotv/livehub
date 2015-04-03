<?php
$javascript = [
	'module' => ['channel', 'edit'],
	'service-settings-url' => route('admin.channel.service.settings'),
]
?>

@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Channel</h2>
			<h3>Edit channel</h3>

			{!! Form::model($show, ['route' => ['admin.show.update', 'show' => $show], 'method' => 'PUT']) !!}

				<div class="row">
					<div class="large-12 columns">
						<label>
							Name
							{!! Form::text('name') !!}
						</label>
					</div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<label>
							URL Friendly Slug
							{!! Form::text('slug') !!}
						</label>
					</div>
				</div>

				{!! Form::submit('Update', ['class' => 'button']) !!}

			{!! Form::close() !!}

			{!! Form::open(['route' => ['admin.show.destroy', 'show' => $show], 'method' => 'DELETE']) !!}
				{!! Form::submit('Delete', ['class' => 'button alert']) !!}
			{!! Form::close() !!}

			<h3>Channels</h3>

			<table class="small-12">
				<thead>
					<tr>
						<th class="small-5">Stream</th>
						<th class="small-6">Rules</th>
						<th class="small-1"></th>
					</tr>
				</thead>
				<tbody>
					@if(count($show->channels) + count($show->defaultFor) > 0)
						@foreach($show->defaultFor as $channel)
							<tr>
								<td>{{$channel->name}}</td>
								<td><strong>Default Show</strong></td>
								<td><a href="{{ route('admin.channel.edit', ['channel' => $channel]) }}">Edit</a></td>
							</tr>
						@endforeach
						@foreach($show->channels as $channel)
						@endforeach
					@else
						<tr>
							<td colspan="3">None</td>
						</tr>
					@endif
				</tbody>
			</table>

		</div>
	</div>
@endsection