@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Incoming Services</h2>

			<table class="small-12">
				<thead>
				<tr>
					<th>Class</th>
					<th>State</th>
					<th>Description</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				@foreach($services as $service)
					<tr>
						<th>{{ $service->id() }}</th>
						<td>
							@if($service->getSettings())
								<span class="label success">On</span>
							@else
								<span class="label alert">Off</span>
							@endif
						</td>
						<td>{{ $service->description() }}</td>
						<td>
							<a href="{{ route('admin.service.incoming.edit', ['service' => $service]) }}">Edit</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>

		</div>
	</div>

@endsection