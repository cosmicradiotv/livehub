<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('livehub.brand') }}</title>

	<link rel="stylesheet" href="{{ asset('assets/css/live.css') }}"/>

	<script src="{{ asset('assets/js/head.js') }}"></script>
</head>
<body>
	<div id="container">
		<div id="header">
			<nav class="top-bar" data-topbar role="navigation" data-options="mobile_show_parent_link: false">
				<ul class="title-area">
					<li class="name">
						<h1><a href="#">{{ config('livehub.brand') }}</a></h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#"><span class="live-streams-text">Now Live Streams: 0</span></a></li>
				</ul>
				<section class="top-bar-section">
					<ul class="left">
						<li class="has-dropdown">
							<a href="#">
								<span class="show-for-medium-up live-streams-text">Now Live Streams: 0</span>
								<span class="show-for-small-only">Switch Stream</span>
							</a>
							<ul class="dropdown" id="streams-list">
							</ul>
						</li>
					</ul>
				</section>
			</nav>
		</div>
		<div id="live-container">
			<iframe src="about:blank" frameborder="0"></iframe>
		</div>
		<div id="chat-container">
			<iframe src="about:blank" frameborder="0"></iframe>
		</div>
	</div>

	<script src="{{ asset('assets/js/live.js') }}"></script>
</body>
</html>