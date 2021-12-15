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
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('/create', 'ProductsController@store');
        Route::put('/{id}', 'ProductsController@update');
        Route::put('/delete/{id}', 'ProductsController@destroy');
    });
    Route::get('/', 'ProductsController@index');
    Route::post('/keyword', 'ProductsController@getKeyword');
    Route::get('/{id}', 'ProductsController@show');
    Route::get('/category/{id}', 'ProductsController@showProductCate');
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
    Route::post('/', 'ToppingController@index');
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
    Route::post('/', 'ClassroomController@index');
    Route::get('/{id}', 'ClassroomController@show');
});
Route::prefix('product_topping')->group(function() {
    Route::get('/', 'ProductToppingController@index');
});
Route::post('/register', 'UserController@store');
Route::post('/login', 'AdminController@login');
Route::post('/reset-pass','AdminController@resetPass');
Route::post('/get-reset-pass','AdminController@getResetPass');
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'AdminController@logout');
    Route::get('/users/{token}', 'UserController@index');
});
Route::prefix('user')->group(function() {
    Route::put('/{id}', 'UserController@updateProfileGoogle');
    Route::put('/profile-google/{id}', 'UserController@getProfileGoogle');
    Route::group(['middleware' => 'auth.jwt'], function () {
         Route::post('/', 'UserController@getAll');
    });
});
Route::prefix('order')->group(function() {
    Route::get('/{id}', 'OrderController@showId');
    Route::put('/{id}', 'OrderController@update');
    Route::post('/customer', 'OrderController@show');
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('/', 'OrderController@index');
        Route::post('/date', 'OrderController@getDate');
    });
    Route::post('/create', 'OrderController@store');
    Route::post('/cancel', 'OrderController@cancel');
});
Route::prefix('voucher')->group(function() {
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::put('/{id}', 'VoucherController@update');
        Route::put('/delete/{id}', 'VoucherController@destroy');
        Route::post('/create', 'VoucherController@store');
    });
    Route::get('/', 'VoucherController@index');
    Route::get('/{id}', 'VoucherController@show');
    Route::post('/redeem', 'VoucherController@redeem');
    Route::post('/account-id', 'VoucherController@voucherAccountId');
});
Route::post('/uploads', 'UploadController@binary')->name('uploads.binary');
Route::post('/google/data', 'UserController@storeDataGoogle');
Route::get('/callback/google', 'UserController@loginCallback');
Route::get('/export/order', function () {
    return view('new-mail');
});
Route::post('/export/order', 'ExportExcelController@export');
