<?php

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

Route::post('oauth/access_token', function() {
    return Response::json(Authorizer::issueAccessToken());
});

Route::group(['prefix' => 'api', 'middleware' => 'oauth', 'as' => 'api'], function() {
    Route::get('test', function() {
        return [
            'id' => 1,
            'name' => 'Doctor Strange',
            'runtime' => 108,
        ];
    });
});

Route::get('tester', 'API\MovieGuestController@tester');
Route::get('movie/{id}', 'API\MovieGuestController@details');
Route::get('movie/popular/{page}', 'API\MovieGuestController@listPopular');

Route::get('test2', function() {
    $kappa = "Youtube";
    echo ('Youtube' === $kappa) ? 'true' : 'false';
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
   return app()->make('WatchTime\Repositories\GenreRepository')->all();
});
