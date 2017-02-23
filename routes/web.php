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

Route::get('/nhan-vien', 'UserController@getUsers');
Route::get('/sua-nhan-vien/{id}', 'UserController@getUser');
Route::post('/sua-nhan-vien/{id}', 'UserController@updateUser');

Route::get('/404', 'NotFoundController@index');
Route::get('/403', 'NotPermissionController@index');
