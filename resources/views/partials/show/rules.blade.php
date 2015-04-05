<?php
$days = function($arrayOfDays) {
	$map = [
		'Sun',
		'Mon',
		'Tue',
		'Wed',
		'Thu',
		'Fri',
		'Sat'
	];
	return implode(', ', array_map(function($key) use($map) {
		return $map[$key];
	}, $arrayOfDays));
}
?>

@if(count($rules) > 0)
	<ul class="no-margin">
		@foreach($rules as $rule)
			<li>
				@if($rule->type == 'title')
					Title regex matches: {{ $rule->rule }}
				@elseif($rule->type == 'startBetween')
					Start time between {{ $rule->start }} and {{ $rule->end }} on {{ $days($rule->days) }}
				@endif()
			</li>
		@endforeach
	</ul>
@else
	<em>No rules</em>
@endif