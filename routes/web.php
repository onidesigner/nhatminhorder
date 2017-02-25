<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

#region quan ly gio hang
Route::get('/gio-hang', 'CartController@showCart');
Route::get('/dat-coc', 'CartController@deposit');
Route::post('/cart/quantity', 'CartController@updateQuantity');
Route::post('/cart/shop/service', 'CartController@updateService');
Route::post('/cart/item/comment', 'CartController@actionUpdate');
Route::delete('/cart/item', 'CartController@deleteItem');
Route::delete('/cart/shop', 'CartController@deleteShop');
#endregion

#region quan ly nhan vien
Route::get('/nhan-vien', 'UserController@getUsers');
Route::get('/sua-nhan-vien/{id}', 'UserController@getUser');
Route::post('/sua-nhan-vien/{id}', 'UserController@updateUser');
#endregion

Route::get('/404', 'NotFoundController@index');
Route::get('/403', 'NotPermissionController@index');

