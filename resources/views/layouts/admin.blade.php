<?php
if (! isset($javascript)) {
	$javascript = [];
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ (isset($title) ? $title . ' | ' : '') . config('livehub.brand') }}</title>

	<link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}"/>

	<script src="{{ asset('assets/js/head.js') }}"></script>
</head>
<body data-config="{{ json_encode($javascript) }}">
	@include('partials.admin.navbar')

	@if (session('status'))
		<div class="alert-box" data-alert>
			{{ session('status') }}
			<a href="#" class="close">&times;</a>
		</div>
	@endif

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

	@yield('content')

	<script src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>