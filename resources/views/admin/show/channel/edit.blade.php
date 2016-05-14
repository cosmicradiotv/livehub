<?php
$javascript = [
	'module' => ['show-channel', 'edit'],
]
?>

@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">

			<h2>Show: {{ $show->name }}</h2>
			<a href="{{ route('admin.show.edit', ['show' => $show]) }}">Back to show</a>

			<h3>Channel: {{ $channel->name }}</h3>
			<span class="right">Server Time: {{ Carbon\Carbon::now() }}</span>

			{!! Form::open(['route' => ['admin.show.channel.update', 'show' => $show, 'channel' => $channel], 'method' => 'PUT']) !!}
			<h4>Show Rules</h4>
			<div class="row">
				<div class="large-12 columns">
					{!! Form::textarea('rules', json_encode($rules), ['data-rules' => true]) !!}
				</div>
			</div>
			{!! Form::submit('Save', ['class' => 'button']) !!}
			{!! Form::close() !!}

			{!! Form::open(['route' => ['admin.show.channel.destroy', 'show' => $show, 'channel' => $channel], 'method' => 'DELETE']) !!}
			{!! Form::submit('Delete', ['class' => 'button alert']) !!}
			{!! Form::close() !!}
		</div>
	</div>


@endsection()