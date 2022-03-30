<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// user routes
Route::group(['namespace' => 'App\Http\Controllers', 'prefix' => ''], function ($router) {
//    entry
    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');

    Route::group(['middleware' => ['user_api']], function ($router) {
//        auth
        Route::post('logout', 'UserController@logout');
        Route::post('refresh', 'UserController@refresh');
        Route::get('me', 'UserController@me');

////        admin crud
//        Route::get('/', 'AdminController@index');
//        Route::post('/', 'AdminController@store');
//        Route::get('/{id}', 'AdminController@show');
//        Route::put('/{id}', 'AdminController@update');
//        Route::delete('/{id}', 'AdminController@destroy');


    });
});
