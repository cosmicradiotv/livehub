<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('livehub.brand') }}</title>

	<link rel="stylesheet" href="{{ asset('assets/css/live.css') }}"/>

	<script src="{{ asset('assets/js/head.js') }}"></script>
</head>
<body data-livehub data-livehub-config="{{ route('live.config') }}">
	<div id="container">
		<div id="header">
			<nav class="top-bar" data-topbar role="navigation" data-options="mobile_show_parent_link: false">
				<ul class="title-area">
					<li class="name">
						<h1 class="hide-for-small"><a href="#">{{ config('livehub.brand') }}</a></h1>
					</li>
					<li class="toggle-topbar menu-icon"><a href="#"><span class="live-streams-text">Loading streams...</span></a></li>
				</ul>
				<section class="top-bar-section">
					<ul class="left">
						<li class="has-dropdown">
							<a>
								<span class="show-for-medium-up live-streams-text">Loading streams...</span>
								<span class="show-for-small-only">Switch Stream</span>
							</a>
							<ul class="dropdown" id="streams-list">
								<li><a id="cruise-control"></a></li>
								<li class="divider"></li>
							</ul>
						</li>
					</ul>
				</section>
			</nav>
		</div>
		<div id="live-container">
			<iframe id="live-frame" src="{{ $stream ? $stream->getVideoUrl() : 'about:blank' }}" frameborder="0"></iframe>
		</div>
		<div id="chat-container">
			<iframe id="chat-frame" src="{{ $stream ? $stream->getChatUrl() : 'about:blank' }}" frameborder="0"></iframe>
		</div>
	</div>

	<script src="{{ asset('assets/js/live.js') }}"></script>
</body>
</html>