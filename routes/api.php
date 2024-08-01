<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('category', CategoryController::class);
    Route::get('category-all', [CategoryController::class, 'getAll']);
    Route::apiResource('book', BookController::class);
    Route::get('search-book', [BookController::class, 'search']);
    Route::get('book-news', [BookController::class, 'bookNews']);
    Route::get('book-all', [BookController::class, 'getAll']);
    Route::get('book-zero', [BookController::class, 'bookZero']);
    Route::apiResource('role', RoleController::class);
    Route::post('borrow', [BorrowController::class, 'store'])->middleware('auth:api');
    Route::get('borrow', [BorrowController::class, 'index'])->middleware('auth:api', 'isOwner');
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::get('get-user', [AuthController::class, 'getUser'])->middleware('auth:api');
    });
    Route::post('update-profile', [ProfileController::class, 'store'])->middleware('auth:api');
    Route::get('get-profile', [ProfileController::class, 'index'])->middleware('auth:api');
});
