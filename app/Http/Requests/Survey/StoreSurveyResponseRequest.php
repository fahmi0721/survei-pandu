<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSurveyResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Survei publik tidak membutuhkan akun admin.
        return true;
    }

    public function rules(): array
    {
        return [
            'submission_token' => [
                'required',
                'uuid',
            ],

            'respondent' => [
                'required',
                'array:status,work_area_id,kelas_pandu,kode_anonim',
            ],

            'respondent.status' => [
                'required',
                Rule::in(['aktif', 'tidak_aktif']),
            ],

            'respondent.work_area_id' => [
                'nullable',
                'required_if:respondent.status,aktif',
                'prohibited_unless:respondent.status,aktif',
                'integer',
                Rule::exists('work_areas', 'id')
                    ->where('is_active', true),
            ],

            'respondent.kelas_pandu' => [
                'nullable', 'string', 'max:100',
            ],


            'respondent.kode_anonim' => [
                'nullable', 'string', 'max:100',
            ],

            'saran' => [
                'nullable', 'string', 'max:5000',
            ],

            'answers' => [
                'required', 'array', 'min:1',
            ],

            'answers.*' => [
                'required',
                'array:survey_section_id,survey_question_id,ranking',
            ],

            'answers.*.survey_section_id' => [
                'required',
                'integer',
                Rule::exists('survey_sections', 'id'),
            ],

            'answers.*.survey_question_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('survey_questions', 'id'),
            ],

            'answers.*.ranking' => [
                'required',
                'integer',
                'between:1,10',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'respondent.status.required' =>
                'Status kerja wajib dipilih.',

            'respondent.work_area_id.required_if' =>
                'Wilayah kerja wajib dipilih untuk responden aktif.',

            'respondent.work_area_id.prohibited_unless' =>
                'Wilayah kerja harus kosong untuk responden tidak aktif.',

            'answers.required' =>
                'Jawaban survei wajib diisi.',

            'answers.*.survey_question_id.distinct' =>
                'Ada pertanyaan yang dikirim lebih dari satu kali.',

            'answers.*.ranking.between' =>
                'Ranking harus berada antara 1 sampai 10.',
        ];
    }
}