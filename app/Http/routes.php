<?php

use Illuminate\Routing\Router;

/**
 * @var $router Router
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$router->get('/', [
	'as' => 'home',
	'uses' => 'LiveController@home',
]);

// App config
$router->get('live/config', [
	'as' => 'live.config',
	'uses' => 'LiveController@config',
	'middleware' => 'cors'
]);
// Interval based pusher
$router->get('live/pusher/interval', [
	'as' => 'live.pusher.interval',
	'uses' => 'LiveController@intervalPusher',
]);

// Helpers
$router->get('helper/misconfigured', [
	'as' => 'helper.misconfigured',
	'uses' => 'HelperController@misconfigured',
]);
$router->get('helper/notlive', [
	'as' => 'helper.notlive',
	'uses' => 'HelperController@notlive',
]);

$router->group(['middleware' => ['backend']], function (Router $router) {
	// Authentication
	$router->group(['prefix' => 'auth'], function (Router $router) {
		$router->get('login', [
			'as' => 'auth.enter',
			'uses' => 'Auth\\AuthController@enter'
		]);
		$router->post('login', [
			'as' => 'auth.login',
			'uses' => 'Auth\\AuthController@login'
		]);

		$router->get('logout', [
			'as' => 'auth.leave',
			'uses' => 'Auth\\AuthController@leave'
		]);
		$router->post('logout', [
			'as' => 'auth.logout',
			'uses' => 'Auth\\AuthController@logout'
		]);

		$router->get('email', [
			'as' => 'auth.email.form',
			'uses' => 'Auth\\PasswordController@getEmail'
		]);
		$router->post('email', [
			'as' => 'auth.email',
			'uses' => 'Auth\\PasswordController@postEmail'
		]);

		$router->get('reset/{token}', [
			'as' => 'auth.reset.form',
			'uses' => 'Auth\\PasswordController@getReset'
		]);
		$router->post('reset', [
			'as' => 'auth.reset',
			'uses' => 'Auth\\PasswordController@postReset'
		]);
	}); // Auth

	// Admin
	$router->group(['prefix' => 'admin', 'middleware' => ['auth']], function (Router $router) {

		$router->get('/', [
			'as' => 'admin.index',
			'uses' => 'Admin\\HomeController@index'
		]);

		$router->post('quick/stream', [
			'as' => 'admin.quick.stream.add',
			'uses' => 'Admin\\HomeController@addStream'
		]);
		$router->post('quick/stream/{stream}/live', [
			'as' => 'admin.quick.stream.live',
			'uses' => 'Admin\\HomeController@streamLive'
		]);
		$router->delete('quick/stream/{stream}', [
			'as' => 'admin.quick.stream.destroy',
			'uses' => 'Admin\\HomeController@streamDestroy'
		]);

		/**
		 * Streams
		 */
		$router->resource('stream', 'Admin\\StreamController', [
			'except' => ['show']
		]);

		/**
		 * Shows
		 */
		$router->resource('show', 'Admin\\ShowController', [
			'except' => ['show']
		]);

		/**
		 * Show's Channels
		 */
		$router->group(['prefix' => 'show/{show}/channel'], function (Router $router) {
			$router->get('{channel}', [
				'as' => 'admin.show.channel.edit',
				'uses' => 'Admin\\ShowChannelController@edit'
			]);
			$router->match(['PUT', 'PATCH'], '{channel}', [
				'as' => 'admin.show.channel.update',
				'uses' => 'Admin\\ShowChannelController@update'
			]);
			$router->delete('{channel}', [
				'as' => 'admin.show.channel.destroy',
				'uses' => 'Admin\\ShowChannelController@destroy'
			]);
		});
		$router->get('show/channel', [
			'as' => 'admin.show.channel.redirect',
			'uses' => 'Admin\\ShowChannelController@redirect'
		]);

		/**
		 * Channels
		 */
		$router->resource('channel', 'Admin\\ChannelController', [
			'except' => ['show']
		]);
		$router->get('channel/settings/{incoming_service}', [
			'as' => 'admin.channel.service.settings',
			'uses' => 'Admin\\ChannelController@channelServiceSettings',
		]);

		/**
		 * Incoming data services
		 */
		$router->get('service/incoming', [
			'as' => 'admin.service.incoming.index',
			'uses' => 'Admin\\IncomingServiceController@index'
		]);
		$router->get('service/incoming/{service}/edit', [
			'as' => 'admin.service.incoming.edit',
			'uses' => 'Admin\\IncomingServiceController@edit'
		]);
		$router->match(['put', 'patch'], 'service/incoming/{service}', [
			'as' => 'admin.service.incoming.update',
			'uses' => 'Admin\\IncomingServiceController@update'
		]);

		/**
		 * Users
		 */
		$router->resource('user', 'Admin\\UserController', [
			'except' => ['show']
		]);
	}); // Admin
});

/**
 * Helper routes
 */
$router->get('login', ['uses' => 'RedirectController@redirect', 'to' => 'auth.enter']);
