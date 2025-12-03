<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
}