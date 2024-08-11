<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\CategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('book', BookController::class);
    Route::get('borrow', [BorrowController::class,"index"]);
    Route::post('borrow', [BorrowController::class,"createOrUpdate"]);
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        
    });
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::apiResource('role', RoleController::class)->middleware('check.admin');
        Route::post('profile', [ProfileController::class, 'store']);
    });
});