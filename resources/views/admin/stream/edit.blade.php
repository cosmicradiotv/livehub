@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Streams</h2>
			<h3>Edit stream</h3>

			{!! Form::model($stream, ['route' => ['admin.stream.update', 'stream' => $stream], 'method' => 'PUT']) !!}

				<div class="row">
					<div class="large-12 columns">
						<label>
							Title
							{!! Form::text('title') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-6 columns">
						<label>
							Channel
							{!! Form::select('channel_id', $channels->lists('name', 'id')) !!}
						</label>
					</div>
					<div class="large-6 columns">
						<label>
							Service metadata
							{!! Form::text('service_info') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-6 columns">
						<label>State</label>
						{!! Form::radio('state', 'live', null, ['id' => 'state-live']) . Form::label('state-live', 'Live') !!}
						{!! Form::radio('state', 'next', null, ['id' => 'state-next']) . Form::label('state-next', 'Upcoming') !!}
					</div>
					<div class="large-6 columns">
						<label>
							Start Time
							{!! Form::text('start_time', null, ['class' => 'no-margin']) !!}
						</label>
						<p class="help">Server timezone: {{ date_default_timezone_get() }} - <a href="http://php.net/manual/en/datetime.formats.php">Supported Formats</a></p>
					</div>
				</div>

				<div class="row">
					<div class="small-12 columns">
						<label>
							Video embed URL
							{!! Form::text('video_url', null, ['class' => 'no-margin']) !!}
						</label>
						<p class="help">Leave blank to get from channel</p>
					</div>
				</div>
				<div class="row">
					<div class="small-12 columns">
						<label>
							Chat embed URL
							{!! Form::text('chat_url', null, ['class' => 'no-margin']) !!}
						</label>
						<p class="help">Leave blank to get from channel</p>
					</div>
				</div>

				{!! Form::submit('Update', ['class' => 'button']) !!}

			{!! Form::close() !!}

			{!! Form::open(['route' => ['admin.stream.destroy', 'stream' => $stream], 'method' => 'DELETE']) !!}
				{!! Form::submit('Delete', ['class' => 'button alert']) !!}
			{!! Form::close() !!}

		</div>
	</div>

@endsection