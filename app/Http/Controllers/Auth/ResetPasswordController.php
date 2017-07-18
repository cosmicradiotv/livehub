<?php

namespace t2t2\LiveHub\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use t2t2\LiveHub\Http\Controllers\Controller;

class ResetPasswordController extends Controller {
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
	 * Where to redirect users after resetting their password.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 */
	public function __construct() {
		$this->middleware('guest');
	}

	/**
	 * Redirect URL on login
	 *
	 * @return string
	 */
	public function redirectTo() {
		return route('admin.index');
	}

}
