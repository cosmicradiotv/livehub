@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="small-12 columns">
			<h2>Service</h2>
			<h3>Edit Service</h3>

			{!! Form::model($settings, ['route' => ['admin.service.incoming.update', 'service' => $service], 'method' => 'PUT']) !!}

			<div class="row">
				<div class="large-12 columns">
					<strong>{{ $service->name() }}</strong>
					<p>{{ $service->description() }}</p>
				</div>
			</div>

			@include('partials.service.settings', ['config' => $service->serviceConfig()])

			<div class="row">
				<div class="large-12 columns">
					{!! Form::submit($settings ? 'Update' : 'Activate', ['class' => 'button']) !!}
				</div>
			</div>

			{!! Form::close() !!}

		</div>
	</div>
@endsection