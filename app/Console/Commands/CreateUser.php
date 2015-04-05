<?php namespace t2t2\LiveHub\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Validation\Factory as ValidationFactory;
use t2t2\LiveHub\Commands\CreateUserCommand;
use t2t2\LiveHub\Http\Requests\CreateUserRequest;

class CreateUser extends Command {

	use DispatchesCommands;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'auth:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create an user for logging in with';

	/**
	 * @var ValidationFactory
	 */
	private $validator;

	/**
	 * Create a new command instance.
	 *
	 * @param ValidationFactory $validator
	 */
	public function __construct(ValidationFactory $validator) {
		parent::__construct();

		$this->validator = $validator;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$credentials = [];
		$credentials['username'] = $this->ask('Username');
		$credentials['email'] = $this->ask('E-mail');
		$credentials['password'] = $this->secret('Password');
		$credentials['password_confirmation'] = $this->secret('Confirm Password');

		$rules = (new CreateUserRequest())->rules();
		$validator = $this->validator->make($credentials, $rules);

		if ($validator->passes()) {

			$this->dispatchFromArray(CreateUserCommand::class, $credentials);

			$this->info('User has been created');

		} else {
			$this->error('Input error:');

			foreach ($validator->errors()->all() as $message) {
				$this->error($message);
			}
		}


	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [
		];
	}

}
