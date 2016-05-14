<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use t2t2\LiveHub\Models\User;

class UpdateUserRequest extends Request
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @param Guard $auth
	 *
	 * @return bool
	 */
	public function authorize(Guard $auth)
    {
		return $auth->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
    {
		/** @var User $user */
		$user = $this->route('user');

		return [
			'username' => ['required', 'alpha_dash', 'max:255', "unique:users,username,{$user->id}"],
			'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
		];
	}
}
