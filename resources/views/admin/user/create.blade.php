@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="small-12 columns">
			<h2>Users</h2>
			<h3>Add User</h3>

			{!! Form::open(['route' => ['admin.user.store']]) !!}

				<div class="row">
					<div class="large-12 columns">
						<label>
							Username
							{!! Form::text('username') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						<label>
							E-Mail Address
							{!! Form::email('email') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						<label>
							Password
							{!! Form::password('password') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						<label>
							Confirm Password
							{!! Form::password('password_confirmation') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						{!! Form::submit('Create', ['class' => 'button']) !!}
					</div>
				</div>

			{!! Form::close() !!}

		</div>
	</div>
@endsection