<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/temp', function () {
    return view('mail.registered');
});

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
});

Route::get('/support', function () {
    return view('contact_us');
})->name('contact_us_page');

Route::post('/contact_us', 'App\Http\Controllers\PublicController@contact_us')->name('contact_us');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
