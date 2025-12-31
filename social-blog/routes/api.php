<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class); // __invoke nên dùng trực tiếp
Route::post('/login', AuthController::class);
Route::post('/refresh', [AuthController::class, 'refresh']); // KHÔNG CẦN MIDDLEWARE nhưng phải gửi kèm token
Route::get('/verify-email', [UserController::class, 'verifyEmail']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/update', [UserController::class, 'update']);
    Route::post('/update/avatar', [UserController::class, 'updateAvatar']);
    Route::get('/verify-email/resend', [UserController::class, 'resendVerifyEmail']);
    Route::post('/change-pass', [UserController::class, 'changePass']);

    Route::prefix('post')->group(function(){
        Route::post('/create', [PostController::class, 'CreatePost']);
        Route::get('/get-post/{id}', [PostController::class, 'GetPost']);
    });
    
});