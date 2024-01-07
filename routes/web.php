<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Laraflow\Crud\Http\Controllers\CrudGenerateController;

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
Route::prefix('crud')->name('crud.')->group(function () {
    Route::get('generate', CrudGenerateController::class)
        ->name('generate');
    Route::post('generate', [CrudGenerateController::class, 'attempt']);
});

