@extends('layouts.guest')

@section('content')

	<div class="row align-center align-middle">
		<div class="medium-4 columns">
			<div class="panel">
				<h2>Login</h2>

				{!! Form::open(['route' => ['auth.login']]) !!}

					<div class="row">
						<div class="small-12 columns">
							<label>
								E-mail Address
								{!! Form::text('email') !!}
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
							<a href="{{ route('auth.reset.request') }}">Forgot Password</a>
						</div>
						<div class="small-6 columns">
							{!! Form::submit('Login', ['class' => 'button float-right']) !!}
						</div>
					</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

@endsection