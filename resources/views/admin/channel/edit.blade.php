<?php
$javascript = [
	'module' => ['channel', 'edit'],
	'service-settings-url' => str_replace(
			'PLACEHOLDER',
			'{incoming_service}',
			route('admin.channel.service.settings', ['incoming_service' => 'PLACEHOLDER'])
	),
]
?>

@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Channel</h2>
			<h3>Edit channel</h3>

			{!! Form::model($channel, ['route' => ['admin.channel.update', 'channel' => $channel], 'method' => 'PUT']) !!}

				<div class="row">
					<div class="large-9 columns">
						<label>
							Name
							{!! Form::text('name') !!}
						</label>
					</div>
					<div class="large-3 columns">
						<label>
							Service
							{!! Form::select('incoming_service_id', $services->pluck('class', 'id'), null, ['data-service' => true]) !!}
						</label>
					</div>
				</div>

				<div id="channel-service-settings">
					@include('partials.service.settings', ['config' => $currentServiceSettings])
				</div>

				<div class="row">
					<div class="small-12 columns">
						<label>
							Video embed URL
							{!! Form::text('video_url', null, ['class' => 'no-margin']) !!}
						</label>
						<p class="help">Leave blank to get from service</p>
					</div>
				</div>
				<div class="row">
					<div class="small-12 columns">
						<label>
							Chat embed URL
							{!! Form::text('chat_url', null, ['class' => 'no-margin']) !!}
						</label>
						<p class="help">Leave blank to get from service</p>
					</div>
				</div>
				<div class="row">
					<div class="small-12 columns">
						<label>
							Default Show
							{!! Form::select('default_show_id', ['' => '* No Show *'] + $shows->pluck('name', 'id')->all(), null, ['class' => 'no-margin']) !!}
						</label>
						<p class="help">If no default show is set and the found streams don't match any of the shows the stream is ignored.</p>
					</div>
				</div>

				{!! Form::submit('Update', ['class' => 'button']) !!}

			{!! Form::close() !!}

			{!! Form::open(['route' => ['admin.channel.destroy', 'channel' => $channel], 'method' => 'DELETE']) !!}
				{!! Form::submit('Delete', ['class' => 'button alert']) !!}
			{!! Form::close() !!}

		</div>
	</div>
@endsection