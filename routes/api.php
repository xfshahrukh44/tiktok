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

//    public routes
    Route::get('/feed', 'VideoController@feed');
    Route::get('/category/{id}/videos/', 'VideoController@category_videos');

    Route::group(['middleware' => ['user_api']], function ($router) {
//        auth
        Route::post('logout', 'UserController@logout');
        Route::post('refresh', 'UserController@refresh');
        Route::get('me', 'UserController@me');

//        user crud
        Route::get('/user/', 'UserController@index');
        Route::post('/user/', 'UserController@store');
        Route::get('/user/{id}', 'UserController@show');
        Route::put('/user/{id}', 'UserController@update');
        Route::delete('/user/{id}', 'UserController@destroy');

//        video crud
        Route::get('/video/', 'VideoController@index');
        Route::get('/video/my_videos', 'VideoController@my_videos');
        Route::post('/video/', 'VideoController@store');
        Route::get('/video/{id}', 'VideoController@show');
        Route::put('/video/{id}', 'VideoController@update');
        Route::delete('/video/{id}', 'VideoController@destroy');

//        category crud
        Route::get('/category/', 'CategoryController@index');
        Route::post('/category/', 'CategoryController@store');
        Route::get('/category/{id}', 'CategoryController@show');
        Route::put('/category/{id}', 'CategoryController@update');
        Route::delete('/category/{id}', 'CategoryController@destroy');


    });
});
