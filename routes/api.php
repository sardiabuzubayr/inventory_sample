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
define('MODULE_PATH', "\App\Http\Modules");

Route::post("/item/get_all/{limit}/{offset}", MODULE_PATH.'\Item\Controllers\ItemController@get_all');
Route::get("/item/get_one/{id_item}", MODULE_PATH.'\Item\Controllers\ItemController@get_one');
Route::get("/item/search/{keywords}", MODULE_PATH.'\Item\Controllers\ItemController@search');
Route::post("/item/new_item", MODULE_PATH.'\Item\Controllers\ItemController@new_item');
Route::put("/item/edit_item/{id_item}", MODULE_PATH.'\Item\Controllers\ItemController@edit_item');
Route::post("/item/update_gambar", MODULE_PATH.'\Item\Controllers\ItemController@update_gambar');
Route::delete("/item/delete_item/{id_item}", MODULE_PATH.'\Item\Controllers\ItemController@delete_item');

Route::post("/sales/get_all/{limit}/{offset}", MODULE_PATH.'\Sales\Controllers\SalesController@get_all');
Route::post("/sales/save_transaction", MODULE_PATH.'\Sales\Controllers\SalesController@save_transaction');
Route::delete("/sales/delete_item/{kode_transaksi}", MODULE_PATH.'\Sales\Controllers\SalesController@delete_item');
Route::get("/sales/get_detail/{kode_transaksi}", MODULE_PATH.'\Sales\Controllers\SalesController@get_detail');

Route::post("/customer/get_all/{limit}/{offset}", MODULE_PATH.'\Customer\Controllers\CustomerController@get_all');
Route::get("/customer/list_customer", MODULE_PATH.'\Customer\Controllers\CustomerController@list_customer');
Route::post("/customer/new_item", MODULE_PATH.'\Customer\Controllers\CustomerController@new_item');
Route::delete("/customer/delete_item/{id_customer}", MODULE_PATH.'\Customer\Controllers\CustomerController@delete_item');
Route::get("/customer/get_one/{id_customer}", MODULE_PATH.'\Customer\Controllers\CustomerController@get_one');
Route::put("/customer/edit_item/{id_customer}", MODULE_PATH.'\Customer\Controllers\CustomerController@edit_item');
Route::post("/customer/update_gambar", MODULE_PATH.'\Customer\Controllers\CustomerController@update_gambar');
