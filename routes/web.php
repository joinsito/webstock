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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signupweb', function () {
    return view('signupweb');
});


Route::get('api/welcome', 'HomeController@welcome');
Route::get('/api/siteinfo/{siteId}', 'WebController@siteinfo');
Route::get('/site/{siteId}/{siteUrl}', 'WebController@site');


Route::post('api/signupwebform', 'WebController@signupwebform');


Auth::routes();

Route::get('/home', function () {
    return view('welcome');
});

Auth::routes();
