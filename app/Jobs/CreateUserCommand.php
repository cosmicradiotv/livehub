<?php namespace t2t2\LiveHub\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Hashing\Hasher;
use t2t2\LiveHub\Models\User;

class CreateUserCommand extends Job implements SelfHandling {

	/**
	 * @var
	 */
	public $email;

	/**
	 * @var
	 */
	public $password;

	/**
	 * @var
	 */
	public $username;

	/**
	 * Create the job instance.
	 *
	 * @param $username
	 * @param $email
	 * @param $password
	 */
	public function __construct($username, $email, $password) {
		$this->username = $username;
		$this->email = $email;
		$this->password = $password;
	}

	/**
	 * Handle the command.
	 *
	 * @param Hasher $crypt
	 */
	public function handle(Hasher $crypt) {
		$user = new User([
			'username' => $this->username,
			'email'    => $this->email,
			'password' => $crypt->make($this->password),
		]);

		$user->save();
	}


}
