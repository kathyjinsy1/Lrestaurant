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

Route::apiResource('/restaurants', 'RestaurantController');
Route::group(['prefix'=>'restaurants'],function() {
	Route::apiResource('/{restaurant}/menus','MenuController');
	Route::group(['prefix'=>'{restaurant}/menus/{menu}'],function() {
		Route::apiResource('/items', 'ItemController');
		Route::group(['prefix'=>'items/{item}'],function() {
			Route::apiResource('/options', 'OptionController');
			Route::group(['prefix'=>'options/{option}'],function() {
				Route::apiResource('/multicheckoptions', 'MultiCheckOptionController');
			
			});
		});
	});
	Route::apiResource('/{restaurant}/contact', 'ContactController');
});
Route::apiResource('/accounts', 'CustomerController');
Route::group(['prefix' => '/accounts/{account}'], function() {
	Route::apiResource('/orders','OrderController');
	Route::group(['prefix'=>'orders/{order}'],function() {
			    Route::get('/items', 'OrderController@indexItems');
			    Route::get('/items/{item}', 'OrderController@showItem');
				Route::post('/{restaurant}/{item}/{option}/{multicheckoption}', 'OrderController@storeItem');
			    Route::put('/items/{item}', 'OrderController@updateItem');
			    Route::delete('/items/{item}', 'OrderController@destroyItem');
			});
});

