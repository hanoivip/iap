<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['token'])->namespace('Hanoivip\Iap\Controllers')->prefix('api')->group(function () {
    Route::get('/iap/items', 'GameController@getItems');
});