<?php
if (!isset($javascript)) {
	$javascript = [];
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>

	<link rel="stylesheet" href="{{ mix('/assets/admin.css') }}"/>
</head>
<body class="no-js" data-config="{{ json_encode($javascript) }}">
	<div id="app">
		@include('partials.admin.navbar')

		@if (session('status'))
			<div class="callout" data-closable>
				{{ session('status') }}
				<button class="close-button" aria-label="Dismiss alert" type="button" data-close>
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		@endif

		@if(count($errors))
			<div class="callout alert">
				<strong>Whoaaaaa</strong> Something's not quite right
				<ul>
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@yield('content')
	</div>

	<script src="{{ mix('/assets/admin.js') }}"></script>
</body>
</html>