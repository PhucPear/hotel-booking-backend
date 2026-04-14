<?php

use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Authentication\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {

    // Route::prefix('user')->group(function () {
    //     Route::apiResource('room-types', RoomTypeController::class);
    // });

    Route::prefix('admin')->group(function () {
        Route::apiResource('room-types', RoomTypeController::class);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // User
        Route::prefix('user')->group(function () {
            
        });

        // Admin / Staff
        Route::prefix('admin')->group(function () {
            Route::apiResource('room-types', RoomTypeController::class)->middleware('permission:admin');

        });

    });

});