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
		}
	}


	/**
	 * Register and configure IDE Helper
	 */
	protected function registerIDEHelper() {
		$this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');

		$configureIdeHelper = function($wanted, Application $app) {
			/** @var Repository $config */
			$config = $app->make('config');

			$config->set('laravel-ide-helper::extra.Artisan', ['Illuminate\Contracts\Console\Kernel']);

			$config->set('laravel-ide-helper::model_locations', ['app/Models']);
			$config->set('laravel-ide-helper::interfaces', [
				'\Illuminate\Auth\UserInterface' => config('auth.model', 'User'),
				'\Illuminate\Contracts\Auth\Authenticatable' => config('auth.model', 'User')
			]);

			return $wanted;
		};

		$this->app->extend('command.ide-helper.generate', $configureIdeHelper);
		$this->app->extend('command.ide-helper.models', $configureIdeHelper);
	}

}
