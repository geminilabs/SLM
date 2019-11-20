<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get( '/', function() {} );

$router->group( ['prefix' => 'v1'], function() use( $router ) {
	// public routes
	$router->group( ['middleware' => 'throttle:20,1'], function() use( $router ) {
		$router->post( 'licenses/verify', 'LicenseController@verify' );
	});
	// protected routes
	$router->group( ['middleware' => ['auth:api', 'throttle:60,1']], function() use( $router ) {
		// software
		$router->get( 'software', 'SoftwareController@index' );
		$router->post( 'software', 'SoftwareController@store' );
		$router->post( 'software/archive', 'SoftwareController@archive' );
		$router->post( 'software/restore', 'SoftwareController@restore' );
		$router->delete( 'software/{product_id}', 'SoftwareController@destroy' );
		// licenses
		$router->get( 'licenses', 'LicenseController@index' );
		$router->post( 'licenses', 'LicenseController@store' );
		$router->post( 'licenses/deactivate', 'LicenseController@deactivate' );
		$router->post( 'licenses/renew', 'LicenseController@renew' );
		$router->post( 'licenses/restore', 'LicenseController@restore' );
		$router->post( 'licenses/revoke', 'LicenseController@revoke' );
		$router->delete( 'licenses/{license_key}', 'LicenseController@destroy' );
		// domains
		$router->get( 'domains', 'DomainController@index' );
		$router->post( 'domains', 'DomainController@store' );
		$router->delete( 'domains/{domain}', 'DomainController@destroy' );
		// users
		$router->get( 'users', 'UserController@index' );
		$router->post( 'users', 'UserController@store' );
	});
});
