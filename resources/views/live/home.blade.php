<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('livehub.brand') }}</title>

	<link rel="stylesheet" href="{{ asset(versioned('/assets/live.css')) }}"/>
</head>
<body data-livehub data-livehub-config="{{ route('live.config') }}">
	<div id="app" class="container">
		<div class="header">
			{{ config('livehub.brand') }}
		</div>
		<div class="content-wrapper">
			<div class="live-container iframe-container">
				<iframe id="live-frame" src="{{ $stream ? $stream->getVideoUrl() : 'about:blank' }}" frameborder="0"></iframe>
			</div>
			<div class="chat-container iframe-container">
				<iframe id="chat-frame" src="{{ $stream ? $stream->getChatUrl() : 'about:blank' }}" frameborder="0"></iframe>
			</div>
		</div>
	</div>

	<script src="{{ asset(versioned('/assets/live.js')) }}"></script>
</body>
</html>