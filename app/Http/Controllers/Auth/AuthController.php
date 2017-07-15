<?php namespace t2t2\LiveHub\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use t2t2\LiveHub\Http\Controllers\Controller;

class AuthController extends Controller {

	/**
	 * The Guard implementation.
	 *
	 * @var \Illuminate\Contracts\Auth\Guard
	 */
	protected $auth;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param \Illuminate\Contracts\Auth\Guard $auth
	 */
	public function __construct(Guard $auth) {
		$this->auth = $auth;

		$this->middleware('guest', ['except' => ['leave', 'logout']]);
		$this->middleware('auth', ['only' => ['leave', 'logout']]);
	}

	/**
	 * Get the login form
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function enter() {
		return view('auth.login', [
			'title' => 'Login',
		]);
	}

	/**
	 * Login the user
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login(Request $request) {
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required',
		]);

		$credentials = $request->only('username', 'password');

		// Allow e-mail or username
		if (strpos($credentials['username'], '@') !== false) {
			$credentials['email'] = $credentials['username'];
			unset($credentials['username']);
		}

		// Login
		if ($this->auth->attempt($credentials, $request->has('remember'))) {
			return redirect()->intended(route('admin.index'));
		}

		// User not found
		return redirect()->route('auth.enter')->withInput($request->only('username', 'remember'))
		                 ->withErrors(['email' => 'Invalid user']);
	}

	/**
	 * Logout form
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function leave() {
		return view('auth.logout', [
			'title' => 'Logout',
		]);
	}

	/**
	 * Log the user out
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logout() {
		$this->auth->logout();

		return redirect()->route('home');
	}

}
