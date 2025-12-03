<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class); // __invoke nên dùng trực tiếp
Route::post('/login', AuthController::class);
Route::post('/refresh', [AuthController::class, 'refresh']); // KHÔNG CẦN MIDDLEWARE nhưng phải gửi kèm token

Route::middleware('jwt.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});