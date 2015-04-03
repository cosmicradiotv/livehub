@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Shows</h2>
			<h3>Add show</h3>

			{!! Form::open(['route' => ['admin.show.store']]) !!}

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

				<p>Add streams to the show after creating the show.</p>

				{!! Form::submit('Create', ['class' => 'button']) !!}

			{!! Form::close() !!}

		</div>
	</div>

@endsection