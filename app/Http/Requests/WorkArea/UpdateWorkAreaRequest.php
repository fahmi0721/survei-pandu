<?php

namespace App\Http\Requests\WorkArea;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkAreaRequest extends FormRequest
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
        $workArea = $this->route('workArea');

        return [
            'kode' => [
                'required',
                'string',
                'max:30',
                Rule::unique('work_areas', 'kode')
                    ->ignore($workArea?->id),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}