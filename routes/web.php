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
Route::get('/cart/add', 'AddonController@addCart');

Route::get('/cart', 'CartController@showCart');
Route::get('/cart/deposit', 'CartController@deposit');
Route::get('/cart/deposit/success', 'CartController@depositSuccess');
Route::post('/cart/quantity', 'CartController@updateQuantity');
Route::post('/cart/shop/service', 'CartController@updateService');
Route::post('/cart/item/comment', 'CartController@actionUpdate');
Route::delete('/cart/item', 'CartController@deleteItem');
Route::delete('/cart/shop', 'CartController@deleteShop');
Route::post('/cart/deposit', 'CartController@depositOrder');
#endregion

#region quan ly nhan vien
Route::get('/user', 'UserController@getUsers');
Route::get('/user/detail/{id}', 'UserController@detailUser');
Route::get('/user/edit/{id}', 'UserController@getUser');
Route::post('/user/edit/{id}', 'UserController@updateUser');
Route::post('/user/phone', 'UserController@addUserPhone');
Route::put('/user/phone', 'UserController@deleteUserPhone');
Route::get('/user/original_site', 'UserController@listUserOriginalSite');
Route::post('/user/original_site', 'UserController@addUserOriginalSite');
Route::put('/user/original_site/delete', 'UserController@removeUserOriginalSite');
#endregion

#region quan ly dia chi nhan hang
//Route::get('/locations/provinces', 'LocationController@getAllProvinces');
//Route::get('/locations/districts', 'LocationController@getAllDistricts');
Route::post('/user/address', 'UserAddressController@addNewUserAddress');
Route::put('/user/address/delete', 'UserAddressController@deleteUserAddress');
Route::put('/user/address/default', 'UserAddressController@setDefaultUserAddress');
#endregion

#region quan ly don hang
Route::get('/order', 'OrderController@getOrders');
Route::get('/order/{id}', 'OrderController@getOrder');
Route::post('/order/{id}/freight_bill', 'OrderController@insertFreightBill');
Route::put('/order/{id}/freight_bill', 'OrderController@removeFreightBill');
Route::post('/order/{id}/original_bill', 'OrderController@insertOriginalBill');
Route::put('/order/{id}/original_bill', 'OrderController@removeOriginalBill');

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

//======== Warehouse ==========
Route::get('/warehouses', 'WarehouseController@render');
Route::post('/warehouse', 'WarehouseController@insert');
Route::put('/warehouse/delete', 'WarehouseController@delete');

Route::get('/warehouses_manually', 'WarehouseController@render_manually');
Route::post('/warehouses_manually', 'WarehouseController@insert_manually');
Route::put('/warehouses_manually/delete', 'WarehouseController@delete_manually');

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

