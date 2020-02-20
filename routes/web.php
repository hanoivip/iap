<?php
use Illuminate\Support\Facades\Route;

Route::namespace('Hanoivip\Iap\Controllers')->group(function () {
    // Get purchase table items..
    Route::get('/iap/items', 'GameController@getItems')->name('purchase');
    // Decide to buy an item
    Route::get('/iap/purchase', 'GameController@purchase');
    Route::any('/iap/purchase-item', 'GameController@doPurchase')->name('purchase.do');
    Route::any('/iap/callback', 'PlatformController@callback');
    Route::any('/iap/paypal/return', 'PaypalController@return')->name('paypal.return');
    Route::any('/iap/paypal/cancel', 'PaypalController@cancel')->name('paypal.cancel');
    // Test
    Route::get('/iap/test/callback', 'TestController@callback');
});