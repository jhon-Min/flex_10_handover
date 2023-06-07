<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect("/dashboard");
});

Auth::routes();

Route::group(['middleware' => ['auth', 'admin'], 'namespace' => 'App\Http\Controllers\Admin'], function () {
    Route::get('dashboard', 'HomeController@index')->name('dashboard');
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', 'ProfileController@show')->name('my-profile');
        Route::post('update', 'ProfileController@update')->name('my-profile.update');
        Route::post('update/password', 'ProfileController@passwordUpdate')->name('my-profile.change.password');
    });

    //users
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->name('users');
        Route::post('datatable', 'UserController@getUserDatatable')->name('user.data');
        Route::get('update/{id}/{status}', 'UserController@update')->name('user.update');
        Route::post('soft-delete', 'UserController@softDelete')->name('user.soft-delete');
    });

    //orders
    Route::group(['prefix' => 'order'], function () {
        Route::get('/', 'OrderController@index')->name('orders');
        Route::post('datatable', 'OrderController@getOrderDatatable')->name('orders-data');
        Route::post('update', 'OrderController@update')->name('order.update');
        Route::get('{id}/delete', 'OrderController@destroy')->name('order.delete');
    });

    //pending orders
    Route::group(['prefix' => 'abandoned-cart'], function () {
        Route::get('/', 'AbandonedCartController@index')->name('abandoned-cart');
        Route::post('datatable', 'AbandonedCartController@getAbandonedCartDatatable')->name('abandoned-cart-data');
        Route::get('show', 'AbandonedCartController@show')->name('cart.get');
        Route::get('{id}/delete', 'AbandonedCartController@destroy')->name('cart.delete');
        // Route::post('update', 'OrderController@update')->name('order.update');
    });

    //analytics dashboard
    Route::group(['prefix' => 'analytics'], function () {
        Route::get('/', 'SearchHistoryController@index')->name('analytics.dashboard');
    });

    // market Intel
    Route::group(['prefix' => 'market-intel'], function () {
        Route::get('/', 'MarketIntelController@index')->name('market-intels');
        Route::post('datatable', 'MarketIntelController@getMarketIntelDatatable')->name('market-intel.data');
        Route::get('add', 'MarketIntelController@create')->name('market-intel.add');
        Route::post('save', 'MarketIntelController@store')->name('market-intel.save');
        Route::get('{id}/edit', 'MarketIntelController@edit')->name('market-intel.edit');
        Route::post('update', 'MarketIntelController@update')->name('market-intel.update');
        Route::get('{id}/delete', 'MarketIntelController@destroy')->name('market-intel.delete');
    });

    //banner management
    Route::group(['prefix' => 'banner-management'], function () {
        Route::get('/', 'BannerManagementController@index')->name('banners');
        Route::post('datatable', 'BannerManagementController@getBannerDatatable')->name('banner.data');
        Route::get('add', 'BannerManagementController@create')->name('banner.add');
        Route::post('save', 'BannerManagementController@store')->name('banner.save');
        Route::get('{id}/delete', 'BannerManagementController@destroy')->name('banner.delete');
    });

    //product Note
    Route::group(['prefix' => 'note'], function () {
        Route::get('/', 'NoteController@index')->name('notes');
        Route::post('datatable', 'NoteController@getNoteDatatable')->name('note.data');
    });

    // product stock
    Route::group(['prefix' => 'product'], function () {
        Route::post('upload-stock', 'ProductController@uploadProductCsv');
        Route::get('check-import-status', 'ProductController@checkImportStatus');
        Route::post('upload-price', 'ProductController@importProductPriceCsv');
        Route::get('check-price-import-status', 'ProductController@checkPriceImportStatus');
        Route::get('/download/{file}', function ($file = '') {
            return response()->download(public_path('uploads/products-error-stock/' . $file));
        });
    });
});
