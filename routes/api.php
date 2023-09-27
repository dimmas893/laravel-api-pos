<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactiomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);



Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'getData']);
    Route::post('/', [ProductController::class, 'post']);
    Route::get('/{id}', [ProductController::class, 'detail']);
    Route::post('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'getData']);
    Route::post('/', [CategoryController::class, 'post']);
    Route::get('/{id}', [CategoryController::class, 'detail']);
    Route::post('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'delete']);
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'getData']);
    Route::post('/', [CartController::class, 'post']);
    Route::delete('/{id}', [CartController::class, 'delete']);
});
Route::prefix('transaction')->group(function () {
    Route::get('/', [TransactiomController::class, 'riwayatTransaksi']);
    Route::get('/{id}', [TransactiomController::class, 'riwayatTransaksiDetail']);
    Route::post('/', [TransactiomController::class, 'transaction']);
});

Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'data']);
});
