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

// Route::view('/sale','domainforsale');
// Route::post('/sale', 'SalesController@mail');
// Route::get('/sales-mails', 'SalesController@getMails')->middleware('auth');



// Route::get('/{p1?}/{p2?}/{p3?}', function ($p1 = null,$p2 = null,$p3 = null) {
//     return view('domainforsale');
// });



Route::get('/', 'CoursesController@home');

Route::get('/category', 'CoursesController@showCategory');
Route::get('/search', 'CoursesController@search');
Route::get('/course/{cname?}','CoursesController@show');
Route::get('/course/download/{name}', 'CoursesController@download');
Route::post('/course/download/{name}', 'CoursesController@download');
Route::get('/coupon/{cname?}','CoursesController@coupon');


Route::middleware(['auth'])->group(function () {
    Route::get('/course/edit', 'CoursesController@editList');
    Route::get('/course/edit/{id}', 'CoursesController@edit');
    Route::put('/course/update', 'CoursesController@update');

    Route::resource('/ad', 'AdsController');

    Route::get('/lf-start', 'CoursesController@start');
    Route::get('/lf-info', 'CustomsController@info');
    Route::get('/lf-tweets', 'CustomsController@tweets');
    Route::get('/lf-sitemap', 'CustomsController@sitemap');
    Route::get('/lf-duplicates', 'CustomsController@duplicates');

    Route::get('/generatesitemap','CustomsController@generateXML');

    Route::resource('/boost', 'BoostPagesController');
});





Route::get('/test', 'CustomsController@test');
Route::get('/metatest', 'MetasController@test');
Route::get('/t','CoursesController@getlink');



Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dboard', 'HomeController@dboard');
