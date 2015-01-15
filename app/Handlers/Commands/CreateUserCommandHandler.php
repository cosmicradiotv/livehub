<?php namespace t2t2\LiveHub\Handlers\Commands;

use Illuminate\Contracts\Hashing\Hasher;
use t2t2\LiveHub\Commands\CreateUserCommand;
use t2t2\LiveHub\Models\User;

class CreateUserCommandHandler {

	/**
	 * @var Hasher
	 */
	private $hasher;

	/**
	 * Create the command handler.
	 *
	 * @param Hasher $crypt
	 */
	public function __construct(Hasher $crypt) {
		$this->hasher = $crypt;
	}

	/**
	 * Handle the command.
	 *
	 * @param  CreateUserCommand $command
	 *
	 * @return void
	 */
	public function handle(CreateUserCommand $command) {
		$user = new User([
			'username' => $command->username,
			'email' => $command->email,
			'password' => $this->hasher->make($command->password),
		]);

		$user->save();
	}

}
