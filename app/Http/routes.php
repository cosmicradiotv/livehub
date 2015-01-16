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
	'as'   => 'home',
	'uses' => 'LiveController@home'
]);


$router->group(['prefix' => 'auth'], function (Router $router) {

	$router->get('login', [
		'as'   => 'auth.enter',
		'uses' => 'Auth\\AuthController@enter'
	]);
	$router->post('login', [
		'as'   => 'auth.login',
		'uses' => 'Auth\\AuthController@login'
	]);

	$router->get('logout', [
		'as'   => 'auth.leave',
		'uses' => 'Auth\\AuthController@leave'
	]);
	$router->post('logout', [
		'as'   => 'auth.logout',
		'uses' => 'Auth\\AuthController@logout'
	]);

	$router->get('email', [
		'as'   => 'auth.email.form',
		'uses' => 'Auth\\PasswordController@getEmail'
	]);
	$router->post('email', [
		'as'   => 'auth.email',
		'uses' => 'Auth\\PasswordController@postEmail'
	]);

	$router->get('reset/{token}', [
		'as'   => 'auth.reset.form',
		'uses' => 'Auth\\PasswordController@getReset'
	]);
	$router->post('reset', [
		'as'   => 'auth.reset',
		'uses' => 'Auth\\PasswordController@postReset'
	]);

});

$router->group(['prefix' => 'admin', 'middleware' => ['auth']], function (Router $router) {

	$router->get('/', [
		'as'   => 'admin.index',
		'uses' => 'Admin\\HomeController@index'
	]);



	$router->resource('channel', 'Admin\\ChannelController', [
		'except' => ['show']
	]);

	// Incoming data services
	$router->get('service/incoming', [
		'as'   => 'admin.service.incoming.index',
		'uses' => 'Admin\\IncomingServiceController@index'
	]);
	$router->get('service/incoming/{service}/edit', [
		'as'   => 'admin.service.incoming.edit',
		'uses' => 'Admin\\IncomingServiceController@edit'
	]);
	$router->match(['put', 'patch'], 'service/incoming/{service}', [
		'as'   => 'admin.service.incoming.update',
		'uses' => 'Admin\\IncomingServiceController@update'
	]);


	// Users admin controller
	$router->resource('user', 'Admin\\UserController', [
		'except' => ['show']
	]);

});

/**
 * Helper routes
 */
$router->get('login', ['uses' => 'RedirectController@redirect', 'to' => 'auth.enter']);
