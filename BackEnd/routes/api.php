<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;

Route::group(
    ['middleware' => 'api', 'namespace' => 'App\Http\Controllers'],
    function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', [JWTController::class, 'login']);
            Route::post('logout', [JWTController::class, 'logout'])->middleware('auth:api');
            Route::get('me', [JWTController::class, 'me'])->middleware('auth:api');
            Route::post('verify', [JWTController::class, 'verify'])->middleware('auth:api');
            Route::post('register', [JWTController::class, 'register']);
            Route::post('forgot-password/{userEmail}', [JWTController::class, 'forgotPassword']);
        });
    });
