<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Support\ServiceProvider;

class LiveHubServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->alias('t2t2\LiveHub\Services\Incoming\DumbService', 'livehub.services.incoming.DumbService');
		$this->app->alias('t2t2\LiveHub\Services\Incoming\YoutubeService', 'livehub.services.incoming.YoutubeService');
		$this->app->alias('t2t2\LiveHub\Services\Incoming\TwitchService', 'livehub.services.incoming.TwitchService');


		$this->app->tag([
			'livehub.services.incoming.DumbService',
			'livehub.services.incoming.YoutubeService',
			'livehub.services.incoming.TwitchService',
		], 'livehub.services.incoming');
	}

}
