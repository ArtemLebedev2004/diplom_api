<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BasketController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\typecontroller;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\ClientAuthMiddleware;
use App\Models\Product;
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

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);

Route::get('product/{id}', [ProductController::class, 'show']);

Route::put('product/{id}', [ProductController::class, 'update']);

Route::delete('product/{id}', [ProductController::class, 'destroy']);

Route::post('product', [ProductController::class, 'store']);

Route::post('search', [ProductController::class, 'search']);

Route::post('order/create', [OrderController::class, 'store']);

Route::get('types', [typecontroller::class, 'index']);
Route::get('type/{title}', [typecontroller::class, 'show']);
Route::post('time_type/{title2}', [typecontroller::class, 't']);

Route::get('orders', [OrderController::class, 'index']);



Route::middleware('auth:sanctum')->group(function () {

    Route::middleware([ClientAuthMiddleware::class])->group(function () {

        Route::get('cart', [BasketController::class, 'index']);

        Route::post('cart/{product_id}', [BasketController::class, 'store']);

        Route::delete('cart/{product_id}', [BasketController::class, 'destroy']);

        Route::post('order', [OrderController::class, 'store']);


    });

    Route::middleware([AdminAuthMiddleware::class])->group(function () {




    });

    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('inside-mware', function () {
        return response()->json('Success', 200);
    });

});
