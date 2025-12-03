<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service){
        $this->service = $service;
    }

    // Login
    public function __invoke(AuthRequest $request)
    {
        return response()->json(
            $this->service->login($request->email, $request->password)
        );
    }

    // Logout
    public function logout(AuthService $service){
        $this->service->logout();

        return response()->json([
            'message'    => 'Đăng xuất thành công.',
        ]);
    }

    // Refresh token
    public function refresh()
    {
        return response()->json(
            $this->service->refresh()
        );
    }

    // Me
    public function me()
    {
        $user = $this->service->me();

        return $user
            ? response()->json($user)
            : response()->json(['error' => 'Unauthorized'], 401);
    }
}
