<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// test
Route::any('testp', 'TestController@testp');

Route::group(['namespace' => 'Mes', 'prefix' => 'mes'], function(){

    // 客户端发起信息
    Route::any('sendMes', 'ClientController@sendMes');

    //获取消息
    Route::any('getMes', 'MesController@getMes');

    // socket客户端发起信息
    Route::any('socket_sendMes', 'ClientController@sendSocketMes');

    Route::any('socket_sendUserMes', 'ClientController@sendUserMes');

    // 模拟客户端请求
    Route::any('socket_sendGoMes', 'ClientController@sendGoMes');

});

Route::group(['namespace' => 'Redis', 'prefix' => 'redis'], function(){

    // 下单
    Route::any('add_goods', 'OrderController@addGoods');
    Route::any('kill_order', 'OrderController@makeOrder');

});