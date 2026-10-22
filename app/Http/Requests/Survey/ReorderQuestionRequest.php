<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class ReorderQuestionRequest extends FormRequest
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
            'ids' => ['required', 'array', 'min:1'],

            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:survey_questions,id',
            ],
        ];
    }
}