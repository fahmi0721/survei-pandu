<?php

namespace App\Services\Survey;

use App\Models\Survey;
use App\Models\SurveySection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SurveySectionService
{
    public function getAll(Survey $survey)
    {
        return $survey->sections()
            ->withCount('questions')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function create(Survey $survey, array $data): SurveySection
    {
        return DB::transaction(function () use ($survey, $data) {

            // Kunci survei untuk mencegah nomor urut bentrok
            Survey::whereKey($survey->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lastOrder = $survey->sections()
                ->max('sort_order') ?? 0;
            return $survey->sections()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'],
                'sort_order' => $lastOrder + 1,
            ]);
        });
    }

    public function update(
        Survey $survey,
        SurveySection $section,
        array $data
    ): SurveySection {

        $this->ensureOwnership($survey, $section);

        $section->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        return $section->refresh();
    }

    public function delete(
        Survey $survey,
        SurveySection $section
    ): void {

        $this->ensureOwnership($survey, $section);

        DB::transaction(function () use ($section) {

            if ($section->questions()->exists()) {
                throw ValidationException::withMessages([
                    'section' =>
                        'Bagian tidak dapat dihapus karena sudah memiliki pertanyaan.',
                ]);
            }

            $section->delete();
        });
    }

    public function reorder(Survey $survey, array $ids): void
    {
        DB::transaction(function () use ($survey, $ids) {

            Survey::whereKey($survey->id)
                ->lockForUpdate()
                ->firstOrFail();

            $sections = $survey->sections()
                ->lockForUpdate()
                ->get(['id', 'sort_order']);

            $existingIds = $sections
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
                    'ids' => 'Daftar bagian tidak sesuai dengan survei.',
                ]);
            }

            // Tahap sementara untuk menghindari unique
            // constraint (survey_section_id, sort_order),
            // apabila constraint urutan diterapkan.
            $offset = (int) $sections->max('sort_order')
                + count($ids) + 1;

            foreach ($ids as $index => $id) {
                SurveySection::whereKey($id)
                    ->where('survey_id', $survey->id)
                    ->update([
                        'sort_order' => $offset + $index,
                    ]);
            }

            // Simpan urutan final mulai dari 1
            foreach ($ids as $index => $id) {
                SurveySection::whereKey($id)
                    ->where('survey_id', $survey->id)
                    ->update([
                        'sort_order' => $index + 1,
                    ]);
            }
        });
    }

    private function ensureOwnership(
        Survey $survey,
        SurveySection $section
    ): void {

        abort_unless(
            $section->survey_id === $survey->id,
            404,
            'Bagian survei tidak ditemukan.'
        );
    }
}