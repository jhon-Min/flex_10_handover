<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

    Route::group(['prefix' => 'sage'], function () {
        Route::get('ybpcinfo/{code}', 'LoginController@YBPCINFO');
        Route::get('ybpalst/{code}', 'LoginController@YBPALST');
        Route::get('yprilst/{code}', 'LoginController@YPRILST');
        Route::get('ywsitmqty/{code}', 'LoginController@YWSITMQTY');
        Route::get('ybpcsearch/{code}', 'LoginController@YBPCSEARCH');
    });

    Route::post('login', 'LoginController@login');
    Route::post('signup', 'LoginController@signup');
    Route::post('user-is-active', 'LoginController@userIsActive');

    Route::group(['prefix' => 'reset/password'], function () {
        Route::post('request', 'PasswordResetController@resetRequest');
        Route::get('{token}/verify', 'PasswordResetController@verifyToken');
        Route::post('update', 'PasswordResetController@resetPassword');
    });

    Route::get('brands', 'BrandController@index');
    Route::get('categories', 'CategoryController@index');
    Route::get('positions', 'CategoryController@positions');


    Route::get('products', 'ProductController@index');
    Route::get('products/count', 'SearchController@index');
    Route::get('products/search/dropdowns', 'SearchController@searchProductsDropdowns');
    Route::get('product/{product_id}/detail', 'ProductController@show');
    Route::get('product/{sku}/byskudetail', 'ProductController@searchbysku');

    Route::get('makes', 'SearchController@makes');
    Route::get('makes/{make_id}/models', 'SearchController@models');

    Route::post('contact', 'ContactController@index');

    Route::group(['middleware' => 'jwt.auth:api'], function () {

        Route::get('logout', 'LoginController@logout');

        Route::group(['prefix' => 'user'], function () {
            Route::resource('profile', 'UserController');
            // Route::put('profile', 'UserController@update');
            Route::post('password/change', 'UserController@passwordUpdate');
        });

        Route::group(['prefix' => 'product'], function () {
            Route::resource('note', 'NoteController');
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::post('add/product', 'CartController@store');
            Route::post('add/bulk/product', 'CartController@bulkStore');
            Route::get('items', 'CartController@index');
            Route::get('{product_id}/remove', 'CartController@destroy');
            Route::post('placeorder', 'OrderController@store');
        });

        Route::get('orders/', 'OrderController@index');
        Route::group(['prefix' => 'order'], function () {
            Route::put('reference-number/{id}', 'OrderController@update');
            Route::delete('{order_id}/delete', 'OrderController@destroy');
            Route::post('bulk/delete', 'OrderController@bulkDestroy');
            Route::get('export', 'OrderController@export');
            Route::get('{order_id}/cancel', 'OrderController@cancel');

            Route::group(['prefix' => 'favourite'], function () {
                Route::get('/', 'FavouriteOrdersController@index');
                Route::post('/', 'FavouriteOrdersController@store');
                Route::delete('{order_id}', 'FavouriteOrdersController@destroy');
            });
        });

        Route::group(['prefix' => 'market-intel'], function () {
            Route::get('/', 'MarketIntelController@index');
            Route::get('{slug}', 'MarketIntelController@show');
        });

        Route::get('banner', 'BannerManagementController@index');
    });
});
