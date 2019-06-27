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

// Route::group(['prefix' => 'api','middleware' => 'api','namespace' => 'API'],function(){
// 		Route::get('provinces_list','CommonController@getBasicProvince');
// 		Route::get('cities_list','CommonController@getCitiesList');
// });
Route::group(['prefix' => 'swagger'], function () {
    Route::get('json', 'API\SwaggerController@getJSON');
});

Route::any('/notify_wechcat_pay','API\ErrandController@notifyPay');
