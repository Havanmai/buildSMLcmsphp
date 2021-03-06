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

Route::get('/download', function () {
    return view('download');
});

Route::get('/getcode/{id}','App\Http\Controllers\SecretController@render');
Route::get('/survey/sml',function () {
    return view('sml-survey');
});

Route::get('/survey/shop',function () {
    return view('shop-survey');
});

Route::get('/survey/customer',function () {
    return view('customer-survey');
});

Route::any('/{any}', function () {
    return view('cms');
})->where('any', '.*');;
