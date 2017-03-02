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
    return view('nhatminh247');
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

#region quan ly dia chi nhan hang
//Route::get('/locations/provinces', 'LocationController@getAllProvinces');
//Route::get('/locations/districts', 'LocationController@getAllDistricts');
Route::post('/user/address', 'UserAddressController@addNewUserAddress');
Route::put('/user/address/delete', 'UserAddressController@deleteUserAddress');
Route::put('/user/address/default', 'UserAddressController@setDefaultUserAddress');
#endregion

#region comment
Route::post('/comment', 'CommentController@addNewComment');
Route::get('/comment', 'CommentController@getComment');
#endregion

#region he thong
Route::get('/setting', 'SystemConfigController@getList');
Route::post('/setting', 'SystemConfigController@update');

Route::get('/setting/roles', 'SystemConfigController@roles');
Route::get('/setting/role/{id}', 'SystemConfigController@roleDetail');
Route::post('/setting/role/update/{id}', 'SystemConfigController@updateRole');
Route::post('/setting/role', 'SystemConfigController@addRole');
Route::post('/setting/role/permission', 'SystemConfigController@savePermission');
Route::post('/setting/role/user', 'SystemConfigController@updateUserRole');
Route::put('/setting/role/delete', 'SystemConfigController@deleteRole');

#endregion

#region -- giao dich --
Route::get('transactions', 'UserTransactionController@getTransactions');
Route::get('transaction/adjustment', 'UserTransactionController@renderTransactionAdjustment');
Route::post('transaction/adjustment', 'UserTransactionController@createTransactionAdjustment');
#endregion

#region chuc nang nhap/xuat kho cua giang
Route::get('warehouse','ExportWarehouseController@index');
Route::post('actionWarehouse', 'ExportWarehouseController@actionWarehouse');
#endregion

Route::get('/404', 'OtherController@renderPageNotFound');
Route::get('/403', 'OtherController@renderPageNotPermission');

