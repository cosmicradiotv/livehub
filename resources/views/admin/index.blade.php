@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h1>Admin Panel</h1>
		</div>
	</div>

	<div class="row">
		<div class="small-12 large-6 columns">
			<h2>Channels</h2>

			<table class="small-12">
				<thead>
					<tr>
						<th class="medium-8">Channel</th>
						<th class="medium-4">Last Checked</th>
					</tr>
				</thead>
				<tbody>
					@forelse($channels as $channel)
						<tr>
							<td>{{ $channel->name }}</td>
							<td>{{ $channel->last_checked->diffForHumans() }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="2">None</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="small-12 large-6 columns">
			<h2>Live Streams</h2>

			<table class="small-12">
				<thead>
					<tr>
						<th>Title</th>
						<th class="small-2">State</th>
						<th class="small-4"></th>
					</tr>
				</thead>
				<tbody>
					@forelse($streams as $stream)
						<tr>
							<td>{{ $stream->title }}</td>
							<td>{{ $stream->state }}</td>
							<td>
								{!! Form::open(['route' => ['admin.quick.stream.destroy', 'stream' => $stream], 'method' => 'DELETE', 'class' => 'right']) !!}
									{!! Form::submit('Done', ['class' => 'button tiny danger no-margin']) !!}
								{!! Form::close() !!}
								@if($stream->state == 'next')
									{!! Form::open(['route' => ['admin.quick.stream.live', 'stream' => $stream], 'class' => 'right']) !!}
										{!! Form::submit('Live', ['class' => 'button tiny info no-margin']) !!}
									{!! Form::close() !!}
								@endif
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="3">No streams active</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			@if(count($dummyChannels))
				{!! Form::open(['route' => ['admin.quick.stream.add']]) !!}
					{!! Form::hidden('state', 'live') !!}
					{!! Form::hidden('start_time', 'now') !!}
					<h3>Quick-add</h3>
					<label>
						Title
						{!! Form::text('title') !!}
					</label>
					<label>
						Channel
						{!! Form::select('channel_id', $dummyChannels->lists('name', 'id')) !!}
					</label>
					<label>
						Show
						{!! Form::select('show_id', $shows->lists('name', 'id')) !!}
					</label>
					<label>
						Video embed URL
						{!! Form::text('video_url') !!}
					</label>
					<label>
						Chat embed URL
						{!! Form::text('chat_url') !!}
					</label>
					{!! Form::submit('Create now', ['class' => 'button']) !!}
				{!! Form::close() !!}
			@else
				<p>Configure some channels for the DummyService to quickly add streams.</p>
			@endif
		</div>
	</div>

@endsection