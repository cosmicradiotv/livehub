@extends('layouts.admin')

@section('content')
	<div class="row">
		<div class="small-12 columns">
			<h2>Users</h2>
			<h3>Edit User</h3>

			{!! Form::model($user, ['route' => ['admin.user.update', 'user' => $user], 'method' => 'PUT']) !!}

				<div class="row">
					<div class="large-12 columns">
						<label>
							Username
							{!! Form::text('username') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						<label>
							E-Mail Address
							{!! Form::email('email') !!}
						</label>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						{!! Form::submit('Update', ['class' => 'button']) !!}
					</div>
				</div>

			{!! Form::close() !!}

			{!! Form::open(['route' => ['admin.user.destroy', 'user' => $user], 'method' => 'DELETE']) !!}
				{!! Form::submit('Delete', ['class' => 'button alert']) !!}
			{!! Form::close() !!}

		</div>
	</div>
@endsection