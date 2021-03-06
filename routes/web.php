<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/login', 'HomeController@showLoginForm');
Route::post('/login', 'HomeController@doLogin');
Route::get('/signup', 'HomeController@showSignupForm');
Route::post('/signup', 'HomeController@doSignup');
Route::post('/accept', 'HomeController@accept');
Route::post('/reject', 'HomeController@reject');
Route::post('/invite', 'HomeController@inviteUsers');
Route::post('/restaurant/edit', 'HomeController@editRestaurant');
Route::post('/restaurant', 'HomeController@addRestaurant');
Route::delete('/restaurant/{restID}', 'HomeController@deleteRestaurant');
Route::get('/logout', function(){
    session(['token' => '']);
    return redirect('/');
});
Route::get('/{page}', 'HomeController@paginate');
Route::get('/', 'HomeController@showMainPage');


