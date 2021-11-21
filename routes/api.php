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
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::put('/{id}', 'CategoryController@update');
        Route::put('/delete/{id}', 'CategoryController@destroy');
        Route::post('/create', 'CategoryController@store');
    });
    Route::get('/', 'CategoryController@index');
    Route::get('/{id}', 'CategoryController@show');
});
Route::prefix('product')->group(function() {
    Route::get('/', 'ProductsController@index');
    Route::post('/create', 'ProductsController@store');
    Route::post('/keyword', 'ProductsController@getKeyword');
    Route::get('/{id}', 'ProductsController@show');
    Route::put('/{id}', 'ProductsController@update');
    Route::get('/category/{id}', 'ProductsController@showProductCate');
    Route::delete('/{id}', 'ProductsController@destroy');
});
Route::prefix('type')->group(function() {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::put('/{id}', 'TypeController@update');
        Route::put('/delete/{id}', 'TypeController@destroy');
        Route::post('/create', 'TypeController@store');
    });
    Route::get('/', 'TypeController@index');
    Route::get('/{id}', 'TypeController@show');

});
Route::prefix('topping')->group(function() {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::put('/delete/{id}', 'ToppingController@destroy');
        Route::put('/{id}', 'ToppingController@update');
        Route::post('/create', 'ToppingController@store');
    });
    Route::get('/', 'ToppingController@index');
    Route::get('/{id}', 'ToppingController@show');
});
Route::prefix('building')->group(function() {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::put('/{id}', 'BuildingController@update');
        Route::put('/delete/{id}', 'BuildingController@destroy');
        Route::post('/create', 'BuildingController@store');
    });
    Route::get('/', 'BuildingController@index');
    Route::get('/{id}', 'BuildingController@show');
    Route::get('/class/{id}', 'BuildingController@showClass');
});
Route::prefix('classroom')->group(function() {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::put('/{id}', 'ClassroomController@update');
        Route::put('/delete/{id}', 'ClassroomController@destroy');
        Route::post('/create', 'ClassroomController@store');
    });
    Route::get('/', 'ClassroomController@index');
    Route::get('/{id}', 'ClassroomController@show');
});
Route::prefix('product_topping')->group(function() {
    Route::get('/', 'ProductToppingController@index');
});
Route::post('/register', 'UserController@store');
Route::post('/login', 'AdminController@login');
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'AdminController@logout');
    Route::get('/users/{token}', 'UserController@index');
});
Route::prefix('order')->group(function() {
    Route::get('/{id}', 'OrderController@showId');
    Route::put('/{id}', 'OrderController@update');
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('/', 'OrderController@index');
        Route::post('/customer', 'OrderController@show');
    });
    Route::post('/create', 'OrderController@store');
});
Route::post('/uploads', 'UploadController@binary')->name('uploads.binary');
Route::get('/google/url', 'UserController@loginUrl')->name('login_gg');
Route::get('/callback/google', 'UserController@loginCallback');
