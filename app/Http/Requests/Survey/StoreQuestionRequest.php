<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            'question_text' => [
                'required',
                'string',
                'max:5000',
            ],

            'description' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}