@extends('layouts.guest')

@section('content')

	<div class="row">
		<div class="medium-6 columns small-centered">
			<div class="panel">
				<h2>Login</h2>

				{!! Form::open(['route' => ['auth.login']]) !!}

					<div class="row">
						<div class="small-12 columns">
							<label>
								E-mail Address or Username
								{!! Form::text('username') !!}
							</label>
						</div>
					</div>

					<div class="row">
						<div class="small-12 columns">
							<label>
								Password
								{!! Form::password('password') !!}
							</label>
						</div>
					</div>

					<div class="row">
						<div class="small-6 columns">
							<label>
								{!! Form::checkbox('remember') !!}
								Remember Me
							</label>
							<a href="{{ route('auth.email.form') }}">Forgot Password</a>
						</div>
						<div class="small-6 columns">
							{!! Form::submit('Login', ['class' => 'button right']) !!}
						</div>
					</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

@endsection