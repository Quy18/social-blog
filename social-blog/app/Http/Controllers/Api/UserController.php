<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\EmailVerification;
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

    // VerifyEmail
    public function verifyEmail(Request $request){
        $tokenEmail = $request->token;

        // Tìm token trong database
        $verify = EmailVerification::where('token', $tokenEmail)->first();

        if(!$verify){
            return response()->json(['message' => 'Invalid token'], 400);
        }

        // Kiểm tra token quá 24h chưa
        if ($verify->expires_at->isPast()) {
            return response()->json(['message' => 'Token expired'], 400);
        }

        // Lấy user
        $user = $verify->user;

        // Update trạng thái verify
        $user->update([
            'email_verified_at' => now(),
        ]);

        //Xóa tokenMail để tránh dùng lại
        $verify->delete();

        return response()->json([
            'message' => 'Email verified successfully.',
        ]);
    }
}
