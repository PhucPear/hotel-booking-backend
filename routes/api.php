<?php

use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\User\RoomController;
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

    // Kết hợp Middleware + Policy
    // Route::middleware(['auth', 'permission:bookings'])->group(function () {
    //     Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    // });


    // Route::prefix('admin')->group(function () {
    //     Route::apiResource('room-types', RoomTypeController::class);
    // });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // User
        Route::prefix('user')->group(function () {
            Route::post('/rooms', [RoomController::class, 'index']);
            
            // Route::middleware(['auth', 'auto.authorize'])->group(function () {
                
            //     // Route::apiResource('bookings', BookingController::class);

            //     // Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve']);
            //     // Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
            // });
        });

        // Admin / Staff
        Route::prefix('admin')->group(function () {
            Route::apiResource('room-types', RoomTypeController::class)->middleware('permission:admin');
        });
    });
});
