<?php namespace t2t2\LiveHub\Jobs;

use Illuminate\Contracts\Hashing\Hasher;
use t2t2\LiveHub\Models\User;

class CreateUserCommand extends Job {

	/**
	 * @var string E-mail
	 */
	public $email;

	/**
	 * @var string Password
	 */
	public $password;

	/**
	 * @var string Username
	 */
	public $username;

	/**
	 * Create the job instance.
	 *
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 */
	public function __construct($username, $email, $password) {
		$this->username = $username;
		$this->email = $email;
		$this->password = $password;
	}

	/**
	 * Handle the command.
	 *
	 * @param \Illuminate\Contracts\Hashing\Hasher $crypt
	 * @return void
	 */
	public function handle(Hasher $crypt) {
		$user = new User([
			'username' => $this->username,
			'email' => $this->email,
			'password' => $crypt->make($this->password),
		]);

		$user->save();
	}

}
