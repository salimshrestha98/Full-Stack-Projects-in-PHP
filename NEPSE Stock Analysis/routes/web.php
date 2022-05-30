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

Auth::routes();

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);

Route::middleware(['auth'])->group(function() {

    Route::get('/up','StocksController@update');

    Route::post('/', 'StocksController@index');
    Route::get('/', 'StocksController@index')->name('root');
    Route::get('/company/{sym}', 'StocksController@show');

    Route::get('/nepse/update', 'NepseController@update');
    Route::get('/nepse/calculate', 'NepseController@calculate');
    Route::post('/nepse/find', 'NepseController@find');
    Route::get('/nepse/doji','StocksController@doji');
    Route::view('/nepse', 'nepse.home');

    Route::get('/hot-scrips', 'NepseController@getHotScrips');

    Route::get('/graham', 'StocksController@graham');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('/portfolio', 'PortfolioController');
    Route::get('/volume', 'StocksController@volume');

    Route::get('gsa', 'StocksController@getSectorAvg');

});

Route::get('/testNepse', 'StocksController@testNepse');
Route::get('/gann-squares', 'NepseController@gannSquares');
