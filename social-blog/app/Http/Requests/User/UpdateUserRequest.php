<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // chỉ sửa được thông tin của mình
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'name'          => 'nullable|string|max:255',
            'bio'           => 'nullable|string|max:1000',
            'location'      => 'nullable|string',
            'website'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'           => 'Tên không được quá 255 ký tự.',
            'bio.max'            => 'Giới thiệu không được quá 1000 ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'Tên hiển thị',
            'bio'      => 'Giới thiệu',
        ];
    }
}
