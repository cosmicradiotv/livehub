<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->customValidator();
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		/** @var Repository $config */
		$config = $this->app->make('config');


		// Dev
		if ($this->app->environment('local')) {
			$this->registerIDEHelper();
		} elseif ($this->app->environment('testing')) {
			$config->set('database.default', 'sqlite');
			$config->set('database.connections.sqlite.database', ':memory:');
		}
	}

	/**
	 * Implement custom validator with extra rules
	 */
	public function customValidator()
	{
		\Validator::extend('date_parseable', 'Custom\ValidatorRules@parseableDate');
		\Validator::extend('valid_regex', 'Custom\ValidatorRules@validRegex');
		\Validator::extend('time', 'Custom\ValidatorRules@time');
	}

	/**
	 * Register IDE Helper
	 */
	protected function registerIDEHelper()
	{
		$this->app->register('Barryvdh\\LaravelIdeHelper\\IdeHelperServiceProvider');
	}
}
