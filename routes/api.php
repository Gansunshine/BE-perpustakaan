<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RoleController;

Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::delete('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::prefix('v1')->group(function () {
    Route::get('category', [CategoryController::class, 'index']);
    Route::middleware(['auth:api', 'owner'])->post('category', [CategoryController::class, 'store']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::middleware(['auth:api', 'owner'])->put('category/{id}', [CategoryController::class, 'update']);
    Route::middleware(['auth:api', 'owner'])->delete('category/{id}', [CategoryController::class, 'destroy']);
});

Route::prefix('v1')->group(function () {
    Route::get('book', [BookController::class, 'index']);
    Route::get('dashboard', [BookController::class, 'dashboard']);
    Route::middleware(['auth:api', 'owner'])->post('book', [BookController::class, 'store']);
    Route::get('book/{id}', [BookController::class, 'show']);
    Route::middleware(['auth:api', 'owner'])->put('book/{id}', [BookController::class, 'update']);
    Route::middleware(['auth:api', 'owner'])->delete('book/{id}', [BookController::class, 'destroy']);

});

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'owner'])->get('borrow', [BorrowController::class, 'index']);
    Route::middleware(['auth:api'])->post('borrow', [BorrowController::class, 'store']);

});

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api'])->post('profile', [ProfileController::class, 'store']);

});

Route::prefix('v1')->middleware(['auth:api', 'owner'])->group(function () {
    Route::get('role', [RoleController::class, 'index']);
    Route::post('role', [RoleController::class, 'store']);
    Route::put('role/{id}', [RoleController::class, 'update']);
    Route::delete('role/{id}', [RoleController::class, 'destroy']);
});
