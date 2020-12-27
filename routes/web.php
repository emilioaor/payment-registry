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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('translation', 'Controller@translations');

Route::post('payment/exists', 'PaymentController@exists');
Route::resource('payment', 'PaymentController');

Route::post('user/exists', 'UserController@exists')->middleware('admin');
Route::get('user/config', 'UserController@config')->name('user.config')->middleware('admin');
Route::put('user/config', 'UserController@updateConfig')->name('user.updateConfig')->middleware('admin');
Route::resource('user', 'UserController')->middleware('admin');
