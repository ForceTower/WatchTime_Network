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

Route::group(['prefix' => 'api', 'as' => 'api'], function() {
    Route::post('facebook/login', 'API\AccountController@facebookLogin');
    Route::post('user/sign_up', 'API\AccountController@createAccount');

    Route::group(['prefix' => 'watch_time', 'middleware' => 'oauth', 'as' => '.watch_time'], function() {
        Route::get('user/me', 'API\AccountController@myProfile');
        Route::get('user/{id}', 'API\AccountController@userProfile');
        Route::get('user/{id}/profile_image', 'API\AccountController@userImage');
        Route::post('user/me/cover', 'API\AccountController@coverUpdate');
        Route::post('user/me/movie_watched', 'API\UserProfileController@markAsWatched');
        Route::get('user/me/watchlist', 'API\UserProfileController@getWatchlist');
        Route::post('user/me/watchlist/add/movie', 'API\UserProfileController@addMovieToWatchlist');
        Route::post('user/me/watchlist/remove/movie', 'API\UserProfileController@removeMovieFromWatchlist');

    });
});

Route::get('user/{id}/profile_image', 'API\AccountController@userImage');
Route::get('movie/{id}', 'API\MovieGuestController@details');
Route::get('movies/popular/{page}', 'API\MovieGuestController@listPopular');
Route::get('movies/rating/{page}', 'API\MovieGuestController@listRating');
Route::get('movies/on_theaters/{page}', 'API\MovieGuestController@listOnTheaters');
Route::get('movies/upcoming/{page}', 'API\MovieGuestController@listUpcoming');
Route::get('movies/release', 'API\MovieGuestController@listRelease');

Route::get('/', function () {
    return view('welcome');
});

