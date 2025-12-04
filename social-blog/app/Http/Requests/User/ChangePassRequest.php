<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password_old'              => 'required|string|min:8',
            'password_new'              => 'required|string|min:8|confirmed',
            'password_new_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password_new.confirmed' => 'Mật khẩu xác nhận không đúng.',
        ];
    }
}
