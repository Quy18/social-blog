<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
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
            'avatar'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'avatar.required' => 'Vui lòng chọn file ảnh.'
        ];
    }
}
