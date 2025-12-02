<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, UserService $service)
    {
        $user = $service->register($request->validated());

        // Tự động đăng nhập sau khi đăng ký
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Đăng ký thành công!',
            'user'    => $user->makeVisible(['email']),
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60
        ], 201);
    }
}
