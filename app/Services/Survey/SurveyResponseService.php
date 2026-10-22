<?php

namespace App\Services\Survey;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SurveyResponseService
{
    public function store(Survey $survey, array $data): SurveyResponse
    {
        return DB::transaction(function () use ($survey, $data) {

            // Kunci survei selama pemeriksaan dan penyimpanan.
            $survey = Survey::query()
                ->whereKey($survey->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Proteksi request duplikat.
            $existing = SurveyResponse::where(
                'submission_token',
                $data['submission_token']
            )->first();

            if ($existing) {
                if ($existing->survey_id !== $survey->id) {
                    throw ValidationException::withMessages([
                        'submission_token' =>
                            'Token sudah digunakan pada survei lain.',
                    ]);
                }

                return $existing;
            }

            $this->ensureSurveyActive($survey);

            // Ambil seluruh bagian dan pertanyaan aktif.
            $sections = $survey->sections()
                ->where('is_active', true)
                ->with([
                    'questions' => fn ($query) =>
                        $query->where('is_active', true)
                            ->orderBy('sort_order')
                ])
                ->get();

            if ($sections->isEmpty()) {
                throw ValidationException::withMessages([
                    'answers' => 'Survei belum memiliki bagian aktif.',
                ]);
            }

            $this->validateAnswers(
                $sections,
                $data['answers']
            );

            $respondent = $data['respondent'];

            // Simpan header respons.
            $response = SurveyResponse::create([
                'survey_id' => $survey->id,

                'work_area_id' =>
                    $respondent['status'] === 'aktif'
                        ? ($respondent['work_area_id'] ?? null)
                        : null,

                'status' => $respondent['status'],
                'kelas_pandu' => $respondent['kelas_pandu'] ?? null,
                'masa_kerja' => $respondent['masa_kerja'] ?? null,
                'kode_anonim' => $respondent['kode_anonim'] ?? null,

                'saran' => $data['saran'] ?? null,
                'submission_token' => $data['submission_token'],
                'submitted_at' => now(),
            ]);

            // Simpan seluruh ranking sekaligus.
            $now = now();

            $rows = collect($data['answers'])
                ->map(fn ($answer) => [
                    'survey_response_id' => $response->id,
                    'survey_section_id' => $answer['survey_section_id'],
                    'survey_question_id' => $answer['survey_question_id'],
                    'ranking' => $answer['ranking'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
                ->all();

            DB::table('survey_answers')->insert($rows);

            return $response;
        });
    }

    private function ensureSurveyActive(Survey $survey): void
    {
        if ($survey->status !== 'active') {
            throw ValidationException::withMessages([
                'survey' => 'Survei tidak sedang aktif.',
            ]);
        }

        $today = now()->toDateString();

        if (
            ($survey->start_date &&
                $today < $survey->start_date->toDateString()) ||
            ($survey->end_date &&
                $today > $survey->end_date->toDateString())
        ) {
            throw ValidationException::withMessages([
                'survey' => 'Survei berada di luar periode pengisian.',
            ]);
        }
    }

    private function validateAnswers(
        $sections,
        array $answers
    ): void {

        $grouped = collect($answers)
            ->groupBy('survey_section_id');

        // Jumlah bagian yang dikirim harus sesuai.
        if ($grouped->count() !== $sections->count()) {
            throw ValidationException::withMessages([
                'answers' => 'Jumlah bagian survei tidak sesuai.',
            ]);
        }

        foreach ($sections as $section) {

            $questions = $section->questions;

            if ($questions->count() !== 10) {
                throw ValidationException::withMessages([
                    'answers' =>
                        "Bagian {$section->title} belum memiliki 10 aspek aktif.",
                ]);
            }

            $items = $grouped->get($section->id, collect());

            if ($items->count() !== 10) {
                throw ValidationException::withMessages([
                    'answers' =>
                        "Bagian {$section->title} harus memiliki 10 jawaban.",
                ]);
            }

            // Bandingkan ID pertanyaan dengan master.
            $expectedIds = $questions
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            $submittedIds = $items
                ->pluck('survey_question_id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            if ($expectedIds !== $submittedIds) {
                throw ValidationException::withMessages([
                    'answers' =>
                        "Pertanyaan pada bagian {$section->title} tidak sesuai master.",
                ]);
            }

            // Ranking wajib tepat 1 sampai 10, masing-masing sekali.
            $rankings = $items
                ->pluck('ranking')
                ->map(fn ($rank) => (int) $rank)
                ->sort()
                ->values()
                ->all();

            if ($rankings !== range(1, 10)) {
                throw ValidationException::withMessages([
                    'answers' =>
                        "Ranking pada bagian {$section->title} harus unik dari 1 sampai 10.",
                ]);
            }
        }
    }
}