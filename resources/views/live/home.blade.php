<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name') }}</title>

	<link rel="stylesheet" href="{{ mix('/assets/live.css') }}"/>
</head>
<body>
	<div id="app" class="container">
		<div class="header">
			{{ config('app.name') }}
		</div>
		<div class="content-wrapper">
			<div class="live-container iframe-container">
				<iframe id="live-frame" src="{{ $stream ? $stream->getVideoUrl() : 'about:blank' }}" frameborder="0" allowfullscreen></iframe>
			</div>
			<div class="chat-container iframe-container">
				<iframe id="chat-frame" src="{{ $stream ? $stream->getChatUrl() : 'about:blank' }}" frameborder="0"></iframe>
			</div>
		</div>
	</div>

	<script src="{{ mix('/assets/polyfill.js') }}"></script>
	<script src="{{ mix('/assets/live.js') }}"></script>
</body>
</html>