<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "API" middleware group. Enjoy building your API!
|
*/
if(Config::get('fintech.skeleton.enabled')) {
    Route::prefix('skeleton')->name('skeleton.')->group(function () {
        
        //DO NOT REMOVE THIS LINE//
    });
}
