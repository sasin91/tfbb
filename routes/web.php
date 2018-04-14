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

Route::view('/', 'welcome');

Route::get('facebook/login', 'Auth\Social\Facebook\FacebookLoginController@__invoke');
Route::post('facebook/login', 'Auth\Social\Facebook\FacebookCallbackController@__invoke');

Route::middleware('auth')->group(function () {
	Route::view('/home', 'home');

	Route::get('profile', 'CurrentUserProfileController@show');
	Route::post('profile', 'CurrentUserProfileController@store');
	Route::match(['PUT', 'PATCH'], 'profile', 'CurrentUserProfileController@update');
	Route::delete('profile', 'CurrentUserProfileController@destroy');

	Route::post('workout', 'CurrentWorkoutController@store');
	Route::get('workout-of-the-month', 'WorkoutOfTheMonthController@show');
	Route::get('workouts', 'WorkoutController@index');
	Route::get('workouts/{workout}', 'WorkoutController@show');

	Route::get('boards', 'BoardController@index');
	Route::get('boards/{board}', 'BoardController@show');
	Route::get('threads/{thread}', 'ThreadController@show');

	Route::get('profiles', 'ProfileController@index');
	Route::get('profiles/{profile}', 'ProfileController@show');

	Route::get('recordings', 'RecordingController@index');
	Route::get('recordings/{recording}', 'RecordingController@show');
});
