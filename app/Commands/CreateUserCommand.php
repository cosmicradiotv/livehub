<?php namespace t2t2\LiveHub\Commands;

class CreateUserCommand extends Command {

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
	 * Create a new command instance.
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

}
