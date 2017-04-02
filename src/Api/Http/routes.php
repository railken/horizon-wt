<?php

/*
|--------------------------------------------------------------------------
| Sign-IN
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your application.
|
*/

Route::post('/sign-in', ['uses' => 'SignInController@index']);


/*
|--------------------------------------------------------------------------
|
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your application.
|
*/

if (!function_exists('admin_routes')) {
	function admin_routes($prefix, $controller) {
		Route::group(['prefix' => $prefix], function() use($controller) {
			Route::get('/', ['uses' => $controller.'@index'] );
			Route::post('/', ['uses' => $controller.'@create'] );
			Route::get('/{id}', ['uses' => $controller.'@show'] );
			Route::put('/{id}', ['uses' => $controller.'@update'] );
			Route::delete('/{ids}', ['uses' => $controller.'@delete'] );
		} );
	}
}
/*
|--------------------------------------------------------------------------
| Logged-in Operation
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your application.
|
*/
Route::group(['middleware' => 'core-auth'], function () {

	Route::group(['prefix' => 'user'], function() {

		Route::get('/profile', ['uses' => 'User\ProfileController@index']);

		Route::group(['prefix' => 'library'], function() {
			Route::get('/', ['uses' => 'User\LibraryController@index']);
			Route::post('/{id}', ['uses' => 'User\LibraryController@update']);
			Route::delete('/{id}', ['uses' => 'User\LibraryController@delete']);
		});

	});

	/*
	|--------------------------------------------------------------------------
	| Admin Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register routes for your application.
	|
	*/
	Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'namespace' => 'Admin'], function() {
		admin_routes('resources', 'ResourceContainerController');
		admin_routes('series', 'SeriesController');

		Route::group(['prefix' => 'sync'], function() {
			Route::post('/', ['uses' => 'Admin\SyncController@index']);
		});

	} );

});
