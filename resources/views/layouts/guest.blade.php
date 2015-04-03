<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ (isset($title) ? $title . ' | ' : '') . config('livehub.brand') }}</title>

	<link rel="stylesheet" href="{{ asset(versioned('assets/css/admin.css')) }}"/>

	<script src="{{ asset(versioned('assets/js/head.js')) }}"></script>
</head>
<body>
	<nav class="top-bar" data-topbar role="navigation">
		<ul class="title-area">
			<li class="name">
				<h1><a href="{{ route('auth.login') }}">LiveHub</a></h1>
			</li>
			<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
		</ul>

		<section class="top-bar-section">
			<!-- Right Nav Section -->
			<ul class="right">
				<li><a href="{{ route('home') }}">Back to live</a></li>
			</ul>
		</section>
	</nav>

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

	<script src="{{ asset(versioned('assets/js/app.js')) }}"></script>
</body>
</html>