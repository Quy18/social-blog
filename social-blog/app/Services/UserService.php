<?php
namespace App\Services;

use App\Jobs\SendVerifyEmailJob;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService {
    // Đăng ký người dùng mới
    public function register(array $data) : User
    {
        //Kiem tra email da ton tai chua
        if(User::where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => ['Email nay da duoc su dung.'],
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        // Tạo token để verify mail
        $tokenMail = Str::random(64);

        // Lưu vào bảng
        EmailVerification::create([
            'user_id' => $user->id,
            'token'   => $tokenMail,
            'expires_at' => now()->addHours(24),
        ]);

        // Gửi mail
        SendVerifyEmailJob::dispatch($user, $tokenMail);

        return $user;
    }

    // Thông tin người dùng
    public function me(): ?User
    {
        try{
            return JWTAuth::parseToken()->authenticate();
        }catch(JWTException $e){
            return null;
        }
    }
    
    public function updateProfile(User $user, array $data)
    {
        $user->fill($data);

        $user->save();

        return $user->fresh();
    }

    public function updateAvatar(User $user, array $data)
    {
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            // Xóa avatar cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu file mới
            $path = $data['avatar']->store('avatars', 'public');

            // Cập nhật user
            $user->avatar = $path;
            $user->save();
        }

        return $path;
    }
}