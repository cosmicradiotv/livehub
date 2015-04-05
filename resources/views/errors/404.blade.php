<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>404 | {{config('livehub.brand')}}</title>

	<style>
		body {
			margin: 0;
			font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
			font-weight: lighter;
			text-align: center;
			background-color: #639499;
			color: #ffffff;
			font-size: 4vw;
		}

		h1 {
			font-size: 4em;
			height: 40vh;
			line-height: 40vh;
			margin: 0;
		}

		h2 {
			font-size: 2em;
			height: 30vh;
			line-height: 30vh;
			margin: 0;
		}

		p {
			font-size: 1em;
			height: 20vh;
			line-height: 20vh;
			margin: 0;
		}
	</style>
</head>
<body>

<div id="container">
	<h1>404</h1>

	<h2>Can't find it</h2>
	@if(Auth::check())
		<p><a href="{{route('admin.index')}}">Back to admin home</a></p>
	@else
		<p>Sorry</p>
	@endif
</div>

</body>
</html>