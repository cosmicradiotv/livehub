<?php namespace t2t2\LiveHub\Http\Controllers\Auth;

use t2t2\LiveHub\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

	/**
	 * @var Guard
	 */
	protected $auth;

	/**
	 * @var PasswordBroker
	 */
	protected $passwords;

	/**
	 * Create a new password controller instance.
	 *
	 * @param \Illuminate\Contracts\Auth\Guard          $auth
	 * @param \Illuminate\Contracts\Auth\PasswordBroker $passwords
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords) {
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
	}

	/**
	 * Redirect on password reset to
	 *
	 * @return string
	 */
	public function redirectPath() {
		return route('admin.index');
	}

}
