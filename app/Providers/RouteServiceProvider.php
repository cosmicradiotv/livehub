<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use t2t2\LiveHub\Models\Channel;
use t2t2\LiveHub\Models\IncomingService;
use t2t2\LiveHub\Models\Show;
use t2t2\LiveHub\Models\Stream;
use t2t2\LiveHub\Models\User;

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
	 * @return void
	 */
	public function boot() {
		// Register route bindings
		Route::model('channel', Channel::class);
		Route::model('incoming_service', IncomingService::class);
		Route::model('show', Show::class);
		Route::model('stream', Stream::class);
		Route::model('user', User::class);

		Route::pattern('channel', '[0-9]+');
		Route::pattern('incoming_service', '[0-9]+');
		Route::pattern('show', '[0-9]+');
		Route::pattern('stream', '[0-9]+');
		Route::pattern('user', '[0-9]+');

		Route::pattern('service', '[a-zA-Z]+');

		parent::boot();
	}

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map() {
		$this->mapWebRoutes();

		//
	}

	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @return void
	 */
	protected function mapWebRoutes() {
		Route::group([
			'namespace' => $this->namespace, 'middleware' => 'web',
		], function ($router) {
			require base_path('routes/web.php');
		});
	}

}
