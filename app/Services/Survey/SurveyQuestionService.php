<?php

namespace App\Services\Survey;

use App\Models\Survey;
use App\Models\SurveySection;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SurveyQuestionService
{
    public function ensureSection(
        Survey $survey,
        SurveySection $section
    ): void {
        abort_unless(
            (int) $section->survey_id === (int) $survey->id,
            404,
            'Bagian survei tidak ditemukan.'
        );
    }

    public function ensureQuestion(
        SurveySection $section,
        SurveyQuestion $question
    ): void {
        abort_unless(
            (int) $question->survey_section_id === (int) $section->id,
            404,
            'Pertanyaan tidak ditemukan.'
        );
    }

    public function getAll(
        Survey $survey,
        SurveySection $section
    ) {
        $this->ensureSection($survey, $section);

        return $section->questions()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function create(
        Survey $survey,
        SurveySection $section,
        array $data
    ): SurveyQuestion {

        $this->ensureSection($survey, $section);

        return DB::transaction(function () use ($section, $data) {

            // Serialisasi proses penambahan per bagian
            SurveySection::whereKey($section->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lastOrder = $section->questions()
                ->max('sort_order') ?? 0;

            return $section->questions()->create([
                'question_text' => $data['question_text'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'],
                'sort_order' => $lastOrder + 1,
            ]);
        });
    }

    public function update(
        Survey $survey,
        SurveySection $section,
        SurveyQuestion $question,
        array $data
    ): SurveyQuestion {

        $this->ensureSection($survey, $section);
        $this->ensureQuestion($section, $question);

        $question->update([
            'question_text' => $data['question_text'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        return $question->refresh();
    }

    public function delete(
        Survey $survey,
        SurveySection $section,
        SurveyQuestion $question
    ): void {

        $this->ensureSection($survey, $section);
        $this->ensureQuestion($section, $question);

        DB::transaction(function () use ($section, $question) {

            SurveySection::whereKey($section->id)
                ->lockForUpdate()
                ->firstOrFail();

            $question->delete();

            // Nomor urut kembali berurutan setelah penghapusan.
            // Offset dipakai untuk menghindari tabrakan unique index.
            $remaining = $section->questions()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $offset = (int) $remaining->max('sort_order')
                + $remaining->count() + 1;

            foreach ($remaining as $index => $item) {
                $item->update([
                    'sort_order' => $offset + $index,
                ]);
            }

            foreach ($remaining as $index => $item) {
                $item->update([
                    'sort_order' => $index + 1,
                ]);
            }
        });
    }

    public function reorder(
        Survey $survey,
        SurveySection $section,
        array $ids
    ): void {

        $this->ensureSection($survey, $section);

        DB::transaction(function () use ($section, $ids) {

            SurveySection::whereKey($section->id)
                ->lockForUpdate()
                ->firstOrFail();

            $questions = $section->questions()
                ->lockForUpdate()
                ->get(['id', 'sort_order']);

            $existingIds = $questions
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            $incomingIds = collect($ids)
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            if ($existingIds !== $incomingIds) {
                throw ValidationException::withMessages([
                    'ids' => 'Urutan pertanyaan tidak sesuai dengan bagian survei.',
                ]);
            }

            $offset = (int) $questions->max('sort_order')
                + count($ids) + 1;

            // Tahap sementara untuk menghindari duplicate sort_order
            foreach ($ids as $index => $id) {
                SurveyQuestion::whereKey($id)
                    ->where('survey_section_id', $section->id)
                    ->update([
                        'sort_order' => $offset + $index,
                    ]);
            }

            // Urutan final
            foreach ($ids as $index => $id) {
                SurveyQuestion::whereKey($id)
                    ->where('survey_section_id', $section->id)
                    ->update([
                        'sort_order' => $index + 1,
                    ]);
            }
        });
    }
}