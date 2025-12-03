<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service){
        $this->service = $service;
    }

    // Me
    public function me()
    {
        $user = $this->service->me();

        return $user
            ? response()->json($user)
            : response()->json(['error' => 'Unauthorized'], 401);
    }

    // Update profile
    public function update(UpdateUserRequest $request){
        $updatedUser = $this->service->updateProfile(auth('api')->user(), $request->validated());

        return response()->json([
            'message' => 'Cập nhật thành công!',
            'user' => $updatedUser,
        ]);
    }

    // Update avatar
    public function updateAvatar(UpdateAvatarRequest $request){
        $updateAvatar = $this->service->updateAvatar(auth('api')->user(), $request->validated());

        return response()->json([
            'message' => 'Cập nhật thành công.',
            'path'  => $updateAvatar,
        ]);
    }
}
