<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name">
			<h1><a href="{{ route('admin.index') }}">LiveHub</a></h1>
		</li>
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>

	<section class="top-bar-section">
		<ul class="left">
			<?php
			$navbar = [
				['text' => 'Home', 'url' => route('admin.index'), 'active' => 'admin.index'],
				['text' => 'Streams', 'url' => route('admin.stream.index'), 'active' => 'admin.stream.*'],
				['text' => 'Channels', 'url' => route('admin.channel.index'), 'active' => 'admin.channel.*'],
				['text' => 'Services', 'active' => 'admin.service.*', 'children' => [
					['text' => 'Incoming', 'url' => route('admin.service.incoming.index'), 'active' => 'admin.service.incoming.*'],
				]],
				['text' => 'Users', 'url' => route('admin.user.index'), 'active' => 'admin.user.*'],
			];
			?>
			@include('partials.nav', ['items' => $navbar])
		</ul>

		<!-- Right Nav Section -->
		<ul class="right">
			<li><a href="#" onclick="return false">{{ Auth::user()->username }}</a></li>
			<li class="has-form">
				<div class="row collapse">
					<div class="small-12 columns">
						{!! Form::open(['route' => ['auth.logout']]) !!}
							{!! Form::submit('Logout', ['class' => 'button']) !!}

						{!! Form::close() !!}
					</div>
				</div>
			</li>
		</ul>
	</section>
</nav>