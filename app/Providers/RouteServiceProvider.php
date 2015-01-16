<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 't2t2\LiveHub\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router $router
	 *
	 * @return void
	 */
	public function boot(Router $router) {
		// Register route bindings
		$router->model('channel', 't2t2\\LiveHub\\Models\\Channel');
		$router->model('user', 't2t2\\LiveHub\\Models\\User');

		$router->pattern('service', '[a-zA-Z]+');

		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map() {
		$this->loadRoutesFrom(app_path('Http/routes.php'));
	}

}
