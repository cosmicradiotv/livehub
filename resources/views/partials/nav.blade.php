@foreach($items as $item)
	@if($item == 'divider')
		<li class="divider"></li>
	@else
		@if(isset($item['url']))
			<li{!! (isset($item['active']) && str_is($item['active'], Route::currentRouteName())) ? ' class="active"' : '' !!}>
				<a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
			</li>
		@elseif(isset($item['children']))
			<li class="has-dropdown{!! (isset($item['active']) && str_is($item['active'], Route::currentRouteName())) ? ' active' : '' !!}">
				<a href="#">{{ $item['text'] }}</a>
				<ul class="dropdown">
					@include('partials.nav', ['items' => $item['children']])
				</ul>
			</li>
		@else
			<li class="heading">{{ $item['text'] }}</li>
		@endif
	@endif
@endforeach