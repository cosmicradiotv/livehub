@forelse($config as $input)
	<div class="row">
		<div class="small-12 columns">
			<label>
				{{ $input['label'] }}
				@if(in_array($input['type'], ['text', 'password']))
					{!! Form::input($input['type'], 'options['.$input['name'].']') !!}
				@endif
			</label>
		</div>
	</div>
@empty
	<p>This service has no settings</p>
@endforelse