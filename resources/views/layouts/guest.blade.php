<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ (isset($title) ? $title . ' | ' : '') . config('livehub.brand') }}</title>

	<link rel="stylesheet" href="{{ asset(versioned('/assets/admin.css')) }}"/>
</head>
<body>
	<div class="top-bar">
		<div class="top-bar-left">
			<ul class="dropdown menu" data-dropdown-menu>
				<li class="menu-text">LiveHub</li>
			</ul>
		</div>
		<div class="top-bar-right">
			<ul class="menu align-right">
				<li><a href="{{ route('home') }}">Back to live</a></li>
			</ul>
		</div>
	</div>

	@if (session('status'))
		<div class="callout" data-alert>
			{{ session('status') }}
			<a href="#" class="close">&times;</a>
		</div>
	@endif

	@if(count($errors))
		<div class="callout alert" data-alert>
			<strong>Whoaaaaa</strong> Something's not quite right
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@yield('content')
</body>
</html>