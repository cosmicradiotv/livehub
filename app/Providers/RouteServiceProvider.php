<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
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
		$router->model('incoming_service', 't2t2\\LiveHub\\Models\\IncomingService');
		$router->model('show', 't2t2\\LiveHub\\Models\\Show');
		$router->model('stream', 't2t2\\LiveHub\\Models\\Stream');
		$router->model('user', 't2t2\\LiveHub\\Models\\User');

		$router->pattern('channel', '[0-9]+');
		$router->pattern('incoming_service', '[0-9]+');
		$router->pattern('show', '[0-9]+');
		$router->pattern('stream', '[0-9]+');
		$router->pattern('user', '[0-9]+');

		$router->pattern('service', '[a-zA-Z]+');

		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router) {
		$this->mapWebRoutes($router);

		//
	}

	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function mapWebRoutes(Router $router) {
		$router->group([
			'namespace' => $this->namespace, 'middleware' => 'web',
		], function ($router) {
			require app_path('Http/routes.php');
		});
	}

}
