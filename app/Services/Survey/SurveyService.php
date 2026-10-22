<?php

namespace App\Services\Survey;

use App\Models\Survey;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SurveyService
{
    public function getAll()
    {
        return Survey::query()
            ->with('creator:id,name')
            ->withCount('sections')
            ->latest()
            ->get();
    }

    public function create(array $data): Survey
    {
        return DB::transaction(function () use ($data) {

            $data['slug'] = $this->generateSlug($data['title']);
            $data['created_by'] = Auth::id();

            return Survey::create($data);
        });
    }

    public function update(Survey $survey, array $data): Survey
    {
        return DB::transaction(function () use ($survey, $data) {

            // Slug tetap agar URL tidak berubah ketika judul diedit.
            $survey->update($data);

            return $survey->refresh();
        });
    }

    public function delete(Survey $survey): void
    {
        DB::transaction(function () use ($survey) {
            $survey->delete();
        });
    }

    private function generateSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'survei';

        do {
            $slug = $base . '-' . Str::lower(Str::random(8));
        } while (
            Survey::withTrashed()->where('slug', $slug)->exists()
        );

        return $slug;
    }
}