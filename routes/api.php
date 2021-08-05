<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->namespace('Hanoivip\Iap\Controllers')->prefix('api')->group(function () {
    Route::any('/iap/items', 'GameController@getItems');
    Route::any('/iap/order', 'GameController@newOrder');
    // payment should take place in Payment gate way: output will be the receipt (with payload)
    Route::any('/iap/order/callback', 'GameController@callbackOrder');
    Route::any('/iap/purchases', 'GameController@purchases');
    Route::any('/iap/query', 'AdminController@query');
});