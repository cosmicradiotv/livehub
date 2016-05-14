<div class="title-bar" data-responsive-toggle="top-menu" data-hide-for="medium">
		<span data-responsive-toggle="responsive-main-menu" data-hide-for="medium">
			<button class="menu-icon" type="button" data-toggle></button>
		</span>
	<strong>LiveHub</strong>
</div>
<div id="top-menu" class="top-bar">
	<div class="top-bar-left">
		<ul class="vertical medium-horizontal menu" data-responsive-menu="drilldown medium-dropdown">
			<li class="menu-text">LiveHub</li>
			<?php
			$navbar = [
					['text' => 'Home', 'url' => route('admin.index'), 'active' => 'admin.index'],
					['text' => 'Streams', 'url' => route('admin.stream.index'), 'active' => 'admin.stream.*'],
					['text' => 'Shows', 'url' => route('admin.show.index'), 'active' => 'admin.show.*'],
					['text' => 'Channels', 'url' => route('admin.channel.index'), 'active' => 'admin.channel.*'],
					[
							'text' => 'Services',
							'active' => 'admin.service.*',
							'children' => [
									[
											'text' => 'Incoming',
											'url' => route('admin.service.incoming.index'),
											'active' => 'admin.service.incoming.*'
									],
							]
					],
					['text' => 'Users', 'url' => route('admin.user.index'), 'active' => 'admin.user.*'],
			];
			?>
			@include('partials.nav', ['items' => $navbar])
		</ul>
	</div>
	<div class="top-bar-right">
		<ul class="menu align-right">
			<li><a>{{ Auth::user()->username }}</a></li>
			<li>
				{!! Form::open(['route' => ['auth.logout']]) !!}
				{!! Form::submit('Logout', ['class' => 'button']) !!}
				{!! Form::close() !!}
			</li>
		</ul>
	</div>
</div>