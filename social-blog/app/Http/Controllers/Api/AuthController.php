<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\AuthService;

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
    public function logout(){
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

    
}
