<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;

Route::middleware('auth:sanctum')->prefix('/user')->group(function(){
    Route::apiResource('/event', EventController::class)->only([
        'store',
        'update',
        'destroy',
    ]);
});

Route::prefix('/guest')->group(function(){
    Route::apiResource('/event', EventController::class)->only([
        'index',
        'show',
    ]);
});
