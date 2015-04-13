<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * Overwrite any vendor / package configuration.
	 *
	 * This service provider is intended to provide a convenient location for you
	 * to overwrite any "vendor" or package configuration that you may want to
	 * modify before the application handles the incoming request / command.
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
	 * Register IDE Helper
	 */
	protected function registerIDEHelper() {
		$this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
	}

}
