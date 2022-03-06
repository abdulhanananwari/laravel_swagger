<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
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

Route::middleware(['jwt.verify'])->group(function(){
    Route::controller(EventController::class)->group(function(){
        Route::prefix('event')->group(function () {
            Route::get('/','index');
            Route::post('/','store');
            Route::get('/{id}','show');
            Route::put('/{id}','update');
            Route::delete('/{id}','delete');
        });
    });
});


Route::controller(AuthController::class)->group(function(){
    Route::prefix('auth')->group(function () {
        Route::post('/login','login');
    });
});