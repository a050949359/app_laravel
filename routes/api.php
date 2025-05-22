<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewebPayController;
use Ycs77\NewebPay\Facades\NewebPay;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('pay', [NewebPayController::class, 'getOrder']);
Route::post('/pay/notify', [NewebPayController::class, 'notify']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('jwt')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    // Route::apiResource('posts', 'App\Http\Controllers\Api\PostController');
    // Route::prefix('a_products')->group(function () {
    //     Route::get('/', [ProductController::class, 'index']);
    //     Route::post('save', [ProductController::class, 'save']);
    //     Route::get('{id}/show', [ProductController::class, 'show']);
    //     Route::patch('{id}/update', [ProductController::class, 'update']);
    //     Route::delete('{id}/delete', [ProductController::class, 'delete']);
    // });

    // Route::post('/pay', [PayController::class, 'pay']);
});

