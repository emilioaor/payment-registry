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
Route::put('payment/{payment}/change-status/{status}', 'PaymentController@changeStatus');
Route::get('payment/report', 'PaymentController@report')->name('payment.report');
Route::post('payment/report', 'PaymentController@reportProcess');
Route::resource('payment', 'PaymentController');

Route::post('user/exists', 'UserController@exists')->middleware('admin');
Route::get('user/config', 'UserController@config')->name('user.config')->middleware('auth');
Route::put('user/config', 'UserController@updateConfig')->name('user.updateConfig')->middleware('auth');
Route::resource('user', 'UserController')->middleware('admin');
