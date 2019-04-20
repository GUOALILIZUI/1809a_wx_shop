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

Route::get('/home', 'HomeController@index')->name('home');


//加入购物车
Route::any('cartIndex','Cart\CartController@cartIndex');
Route::any('cartAdd{goods_id?}','Cart\CartController@cartAdd');


//生成订单
Route::any('order','Order\OrderController@order');
Route::any('orderList','Order\OrderController@orderList');


//微信支付
Route::get('pay','WeiXin\WeiXinPayController@pay');
Route::post('payBack','WeiXin\WeiXinPayController@payBack');
