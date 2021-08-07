<?php
use Illuminate\Support\Facades\Route;

Route::namespace('Hanoivip\Iap\Controllers')->group(function () {
    // Get purchase table items..
    Route::get('/iap/items', 'GameController@getItems')->name('purchase');
    // Decide to buy an item
    Route::get('/iap/confirm', 'GameController@askConfirm');
    //Route::any('/iap/purchase-item', 'GameController@doPurchase')->name('purchase.do');
});