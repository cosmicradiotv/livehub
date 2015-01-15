@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="large-12 column">
			<form method="POST" action="{{ route('auth.logout') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<button type="submit" class="button">Logout</button>
			</form>
		</div>
	</div>

@endsection