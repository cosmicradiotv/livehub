<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use t2t2\LiveHub\Custom\Validator;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->customValidator();
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		/** @var Repository $config */
		$config = $this->app->make('config');


		// Dev
		if ($this->app->environment('local')) {

			$config->set('mail.driver', 'log');
			$config->set('mail.from', ['address' => 'mailer@livehub.dev', 'name' => 'livehub']);

			$this->registerIDEHelper();
		} elseif ($this->app->environment('testing')) {
			$config->set('database.default', 'sqlite');
			$config->set('database.connections.sqlite.database', ':memory:');
		}
	}

	/**
	 * Implement custom validator with extra rules
	 */
	public function customValidator() {
		\Validator::resolver(function ($translator, $data, $rules, $messages) {
			return new Validator($translator, $data, $rules, $messages);
		});
	}

	/**
	 * Register IDE Helper
	 */
	protected function registerIDEHelper() {
		$this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
	}

}
