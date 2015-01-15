@extends('layouts.admin')

@section('content')

	<div class="row">
		<div class="small-12 columns">
			<h2>Users</h2>

			<a href="{{ route('admin.user.create') }}" class="button right">Add User</a>

			<table class="small-12">
				<thead>
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>E-mail</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<th>{{ $user->id }}</th>
							<td>{{ $user->username }}</td>
							<td>{{ $user->email }}</td>
							<td>
								<a href="{{ route('admin.user.edit', ['user' => $user]) }}">Edit</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>
	</div>

@endsection