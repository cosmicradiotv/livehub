<?php namespace t2t2\LiveHub\Http\Requests;

use Illuminate\Contracts\Auth\Guard;

class CreateUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @param \Illuminate\Contracts\Auth\Guard $auth
	 * @return bool
	 */
	public function authorize(Guard $auth) {
		return $auth->check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'username' => ['required', 'alpha_dash', 'max:255', 'unique:users,username'],
			'email' => ['required', 'email', 'max:255', 'unique:users,email'],
			'password' => ['required', 'confirmed', 'min:6'],
		];
	}

}
