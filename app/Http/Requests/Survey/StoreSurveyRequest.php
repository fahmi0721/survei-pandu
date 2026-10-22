<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSurveyRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'start_date' => ['required', 'date'],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'active', 'closed']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Title
            'title.required' => 'Judul survei wajib diisi.',
            'title.string'   => 'Judul survei harus berupa teks.',
            'title.max'      => 'Judul survei maksimal 255 karakter.',

            // Description
            'description.string' => 'Deskripsi survei harus berupa teks.',

            // Start Date
            'start_date.required' => 'Tanggal mulai survei wajib diisi.',
            'start_date.date' => 'Tanggal mulai survei tidak valid.',

            // End Date
            'end_date.required'   => 'Tanggal selesai survei wajib diisi.',
            'end_date.date' => 'Tanggal selesai survei tidak valid.',
            'end_date.after_or_equal' =>
                'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',

            // Status
            'status.required' => 'Status survei wajib dipilih.',
            'status.in' =>
                'Status survei harus berupa Draft, Aktif, atau Ditutup.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'judul survei',
            'description' => 'deskripsi survei',
            'start_date'  => 'tanggal mulai',
            'end_date'    => 'tanggal selesai',
            'status'      => 'status survei',
        ];
    }
}