<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/**
 * Only private routes
 */
/*
Route::middleware('auth:sanctum')->group(function () {
 */
    /**
     * Auth Routes
     */
    Route::prefix('auth')->group(function () {
        Route::post('updateProfile', [AuthController::class, 'updateProfile']);
        Route::post('verify-password', [AuthController::class, 'verifyPassword']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

     /**
     * Users routes
     */
    Route::apiResource('users', UserController::class);
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::put('restore/{user}', 'restore');
        Route::delete('force-delete/{user}', 'forceDelete');
    });

    /**
     * Categories routes
     */
    Route::apiResource('categories', CategoryController::class);
    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::put('restore/{category}', 'restore');
        Route::delete('force-delete/{category}', 'forceDelete');
    });

    /**
     * Items routes
     */
    Route::get('items/getCountsByStatus', [ItemController::class, 'getCountsByStatus']);
    Route::apiResource('items', ItemController::class);
    Route::prefix('items')->controller(ItemController::class)->group(function () {
        Route::put('restore/{item}', 'restore');
        Route::delete('force-delete/{item}', 'forceDelete');

    });

    /**
     * Claims routes
     */
    Route::apiResource('claims', ClaimController::class);
    Route::prefix('claims')->controller(ClaimController::class)->group(function () {
        Route::put('restore/{claim}', 'restore');
        Route::delete('force-delete/{claim}', 'forceDelete');
    });
/* }); */

/**
 * Public Routes
 */
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

/**
 * Contacts routes
 */
Route::apiResource('contacts', ContactController::class);
Route::prefix('contacts')->controller(ContactController::class)->group(function () {
    Route::put('restore/{claim}', 'restore');
    Route::delete('force-delete/{claim}', 'forceDelete');
});



/**
 * Fallback for not registered routes
 */
Route::fallback(function () {
    return response()->json(['message' => 'Not found'], 404);
});
