<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Farhad\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route::get('/hello', function () {
//     return response()->json(['message' => 'Hello, World!']);
// });

// Protected routes
Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/test', [AuthController::class, 'test']);
});
