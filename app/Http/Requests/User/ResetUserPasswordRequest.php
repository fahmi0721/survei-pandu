<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetUserPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin'
            && $this->route('user')?->role === 'hrd';
    }

    public function rules(): array
    {
        return [
            'admin_password' => [
                'required',
                'string',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_password.required' =>
                'Password Admin wajib diisi.',

            'admin_password.current_password' =>
                'Password Admin tidak sesuai.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',
        ];
    }
}