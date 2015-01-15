@extends('layouts.guest')

@section('content')

	@if(count($errors))
		<div class="alert-box alert" data-alert>
			<strong>Whoaaaaa</strong> Something's not quite right
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="row">
		<div class="medium-6 columns small-centered">
			<div class="panel">
				<h2>Reset Password</h2>

				<form method="POST" action="{{ route('auth.reset') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="token" value="{{ $token }}">

					<div class="row">
						<div class="small-12 columns">
							<label>
								E-mail Address
								<input type="text" name="email" value="{{ old('email') }}" />
							</label>
						</div>
					</div>

					<div class="row">
						<div class="small-12 columns">
							<label>
								Password
								<input type="password" name="password" />
							</label>
						</div>
					</div>

					<div class="row">
						<div class="small-12 columns">
							<label>
								Password
								<input type="password" name="password_confirmation" />
							</label>
						</div>
					</div>

					<div class="row">
						<div class="small-12 columns">
							<button class="button right" type="submit">Reset Password</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>

@endsection