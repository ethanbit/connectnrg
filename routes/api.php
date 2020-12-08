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

Route::post('salelogin', 'API\UserController@saleLogin'); 
Route::post('login', 'API\UserController@login');
Route::post('login2', 'API\UserController@login2');
Route::post('register', 'API\UserController@register');
Route::post('forgotpassword', 'API\UserController@forgotPassWord');
Route::get('categories', 'API\CategoriesController@getCategories');
Route::post('subcategories', 'API\CategoriesController@getSubCategories');
Route::post('getsubcategoryproducts', 'API\CategoriesController@getSubcategoryProducts');
Route::post('getproducts', 'API\CategoriesController@getProducts');
Route::post('getproducts2', 'API\CategoriesController@getProducts2');
Route::get('publicholiday', 'API\PublicHolidayController@publicholiday');

Route::post('testmethod', 'API\OrderController@testmethod');
Route::get('testmethod', 'API\OrderController@testmethod');

Route::post('sale_orders_dev', 'API\OrderController@getOrderList');
Route::post('sale_orders_backup', 'API\OrderController@getOrderList_backupfeb25');


Route::group(['middleware' => "auth:api"], function(){
	routeFunc();
});


Route::group(['middleware' => "auth:apiadmin"], function(){
	//Route::post('sale_orders1', 'API\OrderController@getOrderList');
	routeFunc('sale_');
});
Route::post('orders', 'API\OrderController@getOrderListAll');
//Route::post('checkout', 'API\OrderController@checkout');
function routeFunc($type=''){
	Route::get($type.'customers', 'API\UserController@customers');
	Route::post($type.'userdetail', 'API\UserController@details');
	Route::post($type.'updateuser', 'API\UserController@updateUser');
	Route::post($type.'changepassword', 'API\UserController@changePassword');

	Route::post($type.'getwishlist', 'API\WishlistController@getWishlist');
	Route::post($type.'addtowishlist', 'API\WishlistController@addToWishList');

	Route::post($type.'addshippingaddress', 'API\ShippingController@addShippingAddress');
	Route::post($type.'getshippingaddress', 'API\ShippingController@getShippingAddress');
	Route::post($type.'updateshippingaddress', 'API\ShippingController@updateShippingAddress');
	Route::post($type.'deleteshippingaddress', 'API\ShippingController@deleteShippingAddress');
	
	Route::post($type.'orders', 'API\OrderController@getOrderList');
	Route::post($type.'orderdetail', 'API\OrderController@orderDetail');
	Route::post($type.'updateorderstatus', 'API\OrderController@updateOrderStatus'); 
	Route::post($type.'checkout', 'API\OrderController@checkout');

	Route::post($type.'saveorderimage','API\OrderController@saveOrderImage');
	Route::post($type.'getorderimage','API\OrderController@getOrderImage');
	
	
}

/* ================= New Implementations ================== */

Route::post('forgotpassword', 'API\UserController@forgotPassWord');
Route::post('servertime', 'API\UserController@serverTime');
Route::post('getsubcategoryproducts', 'API\CategoriesController@getSubcategoryProducts');
//Route::post('getwishlist', 'API\WishlistController@getWishlist');
//Route::post('removefromwishlist', 'API\WishlistController@removeFromWishlist');
Route::get('deliveryblocklist', 'API\DeliveryController@deliveryBlockList');
Route::get('ordercsv', 'API\OrderController@OrderCsv');
Route::get('customercsv', 'API\OrderController@CustomersCsv'); 
Route::get('productscsv', 'API\OrderController@ProductsCsv'); 
























