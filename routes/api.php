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
Route::prefix('product')->group(function() {
    Route::get('/', 'ProductsController@index');
    Route::post('/create', 'ProductsController@store');
    Route::get('/{id}', 'ProductsController@show');
    Route::put('/{id}', 'ProductsController@update');
    Route::delete('/{id}', 'ProductsController@destroy');
});
Route::prefix('type')->group(function() {
    Route::get('/', 'TypeController@index');
    Route::post('/create', 'TypeController@store');
    Route::get('/{id}', 'TypeController@show');
    Route::put('/{id}', 'TypeController@update');
    Route::delete('/{id}', 'TypeController@destroy');
});
Route::post('/uploads', 'UploadController@binary')->name('uploads.binary');
