<?php namespace t2t2\LiveHub\Providers;

use Illuminate\Support\ServiceProvider;
use t2t2\LiveHub\Services\Incoming\DumbService;
use t2t2\LiveHub\Services\Incoming\HlsService;
use t2t2\LiveHub\Services\Incoming\TwitchService;
use t2t2\LiveHub\Services\Incoming\YoutubeService;

class LiveHubServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
    {
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
    {
		$this->app->alias(DumbService::class, 'livehub.services.incoming.DumbService');
		$this->app->alias(YoutubeService::class, 'livehub.services.incoming.YoutubeService');
		$this->app->alias(TwitchService::class, 'livehub.services.incoming.TwitchService');
		$this->app->alias(HlsService::class, 'livehub.services.incoming.HlsService');


		$this->app->tag([
			'livehub.services.incoming.DumbService',
			'livehub.services.incoming.YoutubeService',
			'livehub.services.incoming.TwitchService',
			'livehub.services.incoming.HlsService',
		], 'livehub.services.incoming');
	}
}
