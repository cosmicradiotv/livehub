<?php namespace t2t2\LiveHub\Providers;

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
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register() {

	}

	/**
	 * Implement custom validator with extra rules
	 */
	public function customValidator() {
		\Validator::resolver(function ($translator, $data, $rules, $messages) {
			return new Validator($translator, $data, $rules, $messages);
		});
	}

}
