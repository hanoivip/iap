<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['token'])->namespace('Hanoivip\Iap\Controllers')->prefix('api')->group(function () {
    Route::any('/iap/items', 'GameController@getItems');
});

Route::namespace('Hanoivip\Iap\Controllers')->prefix('api')->group(function () {
    Route::any('/iap/query', 'AdminController@query');
});