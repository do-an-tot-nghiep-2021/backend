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
Route::prefix('category')->group(function() {
    Route::get('/', 'CategoryController@index');
    Route::post('/create', 'CategoryController@store');
    Route::get('/{id}', 'CategoryController@show');
    Route::put('/{id}', 'CategoryController@update');
    Route::delete('/{id}', 'CategoryController@destroy');
});
Route::post('/uploads', 'UploadController@binary')->name('uploads.binary');
