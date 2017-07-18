@extends('layouts.guest')

@section('content')

	<div class="row">
		<div class="medium-6 columns small-centered">
			<div class="panel">
				<h2>Reset Password</h2>

				<form method="POST" action="{{ route('auth.reset.email') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="row">
						<div class="small-12 columns">
							<label>
								E-Mail Address
								<input type="text" name="email" value="{{ old('email') }}" />
							</label>
						</div>
					</div>

					<div class="row">
						<div class="small-12 columns">
							<button class="button right" type="submit">Send Password Reset Link</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>

@endsection