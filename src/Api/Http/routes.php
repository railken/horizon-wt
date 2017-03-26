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
	Route::get('/user/profile', ['uses' => 'User\ProfileController@index']);


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
	} );
});
