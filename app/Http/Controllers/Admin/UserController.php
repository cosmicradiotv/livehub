<?php namespace t2t2\LiveHub\Http\Controllers\Admin;

use Illuminate\Http\Response;
use t2t2\LiveHub\Commands\CreateUserCommand;
use t2t2\LiveHub\Http\Requests;
use t2t2\LiveHub\Http\Requests\CreateUserRequest;
use t2t2\LiveHub\Http\Requests\UpdateUserRequest;
use t2t2\LiveHub\Models\User;

class UserController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$users = User::all();

		$title = 'Users | Admin';

		return view('admin.user.index', compact('users', 'title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {

		$title = 'Create | User | Admin';

		return view('admin.user.create', compact('title'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateUserRequest $request) {

		$this->dispatchFrom(CreateUserCommand::class, $request);

		return redirect()->route('admin.user.index')
		                 ->with('status', 'User has been created');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  User $user
	 *
	 * @return Response
	 */
	public function edit(User $user) {
		$title = 'Edit | Users | Admin';

		return view('admin.user.edit', compact('user', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  User $id
	 *
	 * @return Response
	 */
	public function update(User $user, UpdateUserRequest $request) {

		$user->fill($request->only(['username', 'email']));

		if($user->isDirty()) {
			$user->save();

			return redirect()->route('admin.user.index')->with('status', 'User updated');
		}

		return redirect()->route('admin.user.index')->with('status', 'No changes');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy(User $user) {
		$user->delete();

		return redirect()->route('admin.user.index')->with('status', 'User deleted');
	}

}
