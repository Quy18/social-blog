<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService {
    public function login(string $email, string $password): array {
        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Sai email hoặc mật khẩu.'],
            ]);
        }

        if(!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Tài khoản đã bị khóa.'],
            ]);
        }

        $token = JWTAuth::fromUser($user);
        $ttlMinutes = config('jwt.ttl', 60);  // Lấy từ config/jwt.php, mặc định 60 phút
        $expiresInSeconds = $ttlMinutes * 60;

        return [
            'user' => $user,
            'token' => $token,
            'expires_in' => $expiresInSeconds, // mặc định 1  giờ 
        ];
    }

    public function logout(): void
    {
        // try/catch để tránh lỗi token hết hạn
        // phải bật blacklist_enabled => true trong config/jwt.php
        try {
            // JWTAuth::getToken() → lấy token từ Authorization header
            JWTAuth::parseToken()->invalidate(); // blacklist token hiện tại
        } catch (JWTException $e) {
            // nếu token đã hết hạn hoặc không có thì cũng coi như logout thành công
        }
    }

    public function refresh(): array
    {
        try {
            // Dùng parseToken() để tự động lấy từ header/cookie
            $newToken = JWTAuth::parseToken()->refresh();

            // Lấy TTL đúng (dùng config JWT_REFRESH_TTL nếu có, fallback JWT_TTL)
            // $refreshTtlMinutes = config('jwt.refresh_ttl', config('jwt.ttl', 60));
            // $expiresInSeconds = $refreshTtlMinutes * 60;

            return [
                'access_token' => $newToken,
                'token_type'   => 'bearer',
                'expires_in'   => config('jwt.ttl') * 60, // access token mới có TTL mới
            ];
        } catch (JWTException $e) {
            throw ValidationException::withMessages([
                'token' => ['Token đã hết hạn hoặc không hợp lệ. Vui lòng đăng nhập lại.'],
            ]);
        }
    }

    // Bonus: Hàm me() để lấy user hiện tại
    public function me(): ?User
    {
        try {
            // Trả về User
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return null;
        }
    }
}