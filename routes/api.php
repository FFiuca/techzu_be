<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;

Route::prefix('/user')->group(function(){
    Route::apiResource('/event', EventController::class)->only([
        'store',
        'update',
        'destroy',
    ]);
    Route::prefix('/event')->name('event.')->group(function(){
        Route::post('/store-batch', [EventController::class, 'storeBatch']);
    });
});

Route::prefix('/guest')->group(function(){
    Route::apiResource('/event', EventController::class)->only([
        'index',
        'show',
    ]);
});
