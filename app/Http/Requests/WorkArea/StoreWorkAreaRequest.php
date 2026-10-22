<?php

namespace App\Http\Requests\WorkArea;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(
            $this->user()?->role,
            ['admin', 'hrd'],
            true
        );
    }

    public function rules(): array
    {
        return [
            'kode' => [
                'required', 'string', 'max:30',
                Rule::unique('work_areas', 'kode'),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode wilayah wajib diisi.',
            'kode.unique' => 'Kode wilayah sudah digunakan.',
            'nama.required' => 'Nama wilayah wajib diisi.',
            'is_active.required' => 'Status wilayah wajib ditentukan.',
        ];
    }
}