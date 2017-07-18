<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [
	'as' => 'home',
	'uses' => 'LiveController@home',
]);

// App config
Route::get('live/config', [
	'as' => 'live.config',
	'uses' => 'LiveController@config',
	'middleware' => 'cors'
]);
// Interval based pusher
Route::get('live/pusher/interval', [
	'as' => 'live.pusher.interval',
	'uses' => 'LiveController@intervalPusher',
]);

// Helpers
Route::get('helper/misconfigured', [
	'as' => 'helper.misconfigured',
	'uses' => 'HelperController@misconfigured',
]);
Route::get('helper/notlive', [
	'as' => 'helper.notlive',
	'uses' => 'HelperController@notlive',
]);

Route::group(['middleware' => ['backend']], function () {
	// Authentication
	Route::group(['prefix' => 'auth'], function () {
		Route::get('login', [
			'as' => 'auth.enter',
			'uses' => 'Auth\LoginController@showLoginForm'
		]);
		Route::post('login', [
			'as' => 'auth.login',
			'uses' => 'Auth\LoginController@login'
		]);

		Route::post('logout', [
			'as' => 'auth.logout',
			'uses' => 'Auth\LoginController@logout'
		]);

		Route::get('password/reset', [
			'as' => 'auth.reset.request',
			'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
		]);
		Route::post('email', [
			'as' => 'auth.reset.email',
			'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
		]);

		Route::get('reset/{token}', [
			'as' => 'auth.reset.form',
			'uses' => 'Auth\ResetPasswordController@showResetForm'
		]);
		Route::post('reset', [
			'as' => 'auth.reset',
			'uses' => 'Auth\ResetPasswordController@reset'
		]);
	}); // Auth

	// Admin
	Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {

		Route::get('/', [
			'as' => 'index',
			'uses' => 'Admin\\HomeController@index'
		]);

		Route::post('quick/stream', [
			'as' => 'quick.stream.add',
			'uses' => 'Admin\\HomeController@addStream'
		]);
		Route::post('quick/stream/{stream}/live', [
			'as' => 'quick.stream.live',
			'uses' => 'Admin\\HomeController@streamLive'
		]);
		Route::delete('quick/stream/{stream}', [
			'as' => 'quick.stream.destroy',
			'uses' => 'Admin\\HomeController@streamDestroy'
		]);

		/**
		 * Streams
		 */
		Route::resource('stream', 'Admin\\StreamController', [
			'except' => ['show']
		]);

		/**
		 * Shows
		 */
		Route::resource('show', 'Admin\\ShowController', [
			'except' => ['show']
		]);

		/**
		 * Show's Channels
		 */
		Route::group(['prefix' => 'show/{show}/channel'], function () {
			Route::get('{channel}', [
				'as' => 'show.channel.edit',
				'uses' => 'Admin\\ShowChannelController@edit'
			]);
			Route::match(['PUT', 'PATCH'], '{channel}', [
				'as' => 'show.channel.update',
				'uses' => 'Admin\\ShowChannelController@update'
			]);
			Route::delete('{channel}', [
				'as' => 'show.channel.destroy',
				'uses' => 'Admin\\ShowChannelController@destroy'
			]);
		});
		Route::get('show/channel', [
			'as' => 'show.channel.redirect',
			'uses' => 'Admin\\ShowChannelController@redirect'
		]);

		/**
		 * Channels
		 */
		Route::resource('channel', 'Admin\\ChannelController', [
			'except' => ['show']
		]);
		Route::get('channel/settings/{incoming_service}', [
			'as' => 'channel.service.settings',
			'uses' => 'Admin\\ChannelController@channelServiceSettings',
		]);

		/**
		 * Incoming data services
		 */
		Route::get('service/incoming', [
			'as' => 'service.incoming.index',
			'uses' => 'Admin\\IncomingServiceController@index'
		]);
		Route::get('service/incoming/{service}/edit', [
			'as' => 'service.incoming.edit',
			'uses' => 'Admin\\IncomingServiceController@edit'
		]);
		Route::match(['put', 'patch'], 'service/incoming/{service}', [
			'as' => 'service.incoming.update',
			'uses' => 'Admin\\IncomingServiceController@update'
		]);

		/**
		 * Users
		 */
		Route::resource('user', 'Admin\\UserController', [
			'except' => ['show']
		]);
	}); // Admin
});

/**
 * Helper routes
 */
Route::get('login', ['uses' => 'RedirectController@redirect', 'to' => 'auth.enter']);
