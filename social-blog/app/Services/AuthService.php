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
            'user' => $user->makeVisible(['email']),
            'token' => $token,
            'expires_in' => $expiresInSeconds, // mặc định 1  giờ 
        ];
    }

    public function logout(): void
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken()); // blacklist token hiện tại
        } catch (JWTException $e) {
            // nếu token đã hết hạn hoặc không có thì cũng coi như logout thành công
        }
    }

    public function refresh(): array
    {
        try {
            // Dùng Facade để refresh – tự động dùng JWT_REFRESH_TTL nếu token expired
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            // Lấy TTL đúng (dùng config JWT_REFRESH_TTL nếu có, fallback JWT_TTL)
            $refreshTtlMinutes = config('jwt.refresh_ttl', config('jwt.ttl', 60));
            $expiresInSeconds = $refreshTtlMinutes * 60;

            return [
                'token'      => $newToken,
                'expires_in' => $expiresInSeconds,
            ];
        } catch (JWTException $e) {
            // Nếu token không refresh được (hết hạn refresh hoặc invalid)
            throw ValidationException::withMessages([
                'token' => ['Token không thể refresh. Vui lòng đăng nhập lại.'],
            ]);
        }
    }

    // Bonus: Hàm me() để lấy user hiện tại
    public function me(): ?User
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return null;
        }
    }
}