<?php

namespace App\Services\Survey;

use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class SurveyResultService
{
    private function responseIds(
        int $surveyId,
        ?string $status = null,
        ?int $workAreaId = null
    ) {
        return DB::table('survey_responses')
            ->select('id')
            ->where('survey_id', $surveyId)
            ->when(
                $status !== null,
                fn ($q) => $q->where('status', $status)
            )
            ->when(
                $workAreaId !== null,
                fn ($q) => $q->where('work_area_id', $workAreaId)
            );
    }

    public function perRespondent(
        Survey $survey,
        ?string $status = null,
        ?int $workAreaId = null
    ): array {
        return DB::table('survey_answers as a')
            ->join(
                'survey_responses as r',
                'r.id',
                '=',
                'a.survey_response_id'
            )
            ->join(
                'survey_sections as s',
                's.id',
                '=',
                'a.survey_section_id'
            )
            ->join(
                'survey_questions as q',
                'q.id',
                '=',
                'a.survey_question_id'
            )
            ->leftJoin(
                'work_areas as w',
                'w.id',
                '=',
                'r.work_area_id'
            )
            ->where('r.survey_id', $survey->id)
            ->where('a.ranking', 1)
            ->when(
                $status !== null,
                fn ($query) => $query->where('r.status', $status)
            )
            ->when(
                $workAreaId !== null,
                fn ($query) => $query->where('r.work_area_id', $workAreaId)
            )
            ->select([
                'r.id as response_id',
                'r.status',
                'w.nama as wilayah',
                'r.kelas_pandu',
                'r.submitted_at',
                's.id as section_id',
                's.title as section_title',
                's.sort_order as section_order',
                'q.id as question_id',
                'q.question_text',
            ])
            ->orderByDesc('r.submitted_at')
            ->orderByDesc('r.id')
            ->orderBy('s.sort_order')
            ->orderBy('s.id')
            ->get()
            ->groupBy('response_id')
            ->map(function ($items, $responseId) {
                $first = $items->first();

                return [
                    'response_id' => (int) $responseId,
                    'status' => $first->status,
                    'wilayah' => $first->wilayah ?? '—',
                    'kelas_pandu' => $first->kelas_pandu ?? '—',
                    'submitted_at' => $first->submitted_at,
                    'priorities' => $items->map(fn ($item) => [
                        'section_id' => $item->section_id,
                        'section_title' => $item->section_title,
                        'question_id' => $item->question_id,
                        'question_text' => $item->question_text,
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    public function summary(
        Survey $survey,
        ?string $status = null,
        ?int $workAreaId = null
    ): array {
        // Jumlah seluruh responden dalam filter.
        $totalRespondents = DB::table('survey_responses')
            ->where('survey_id', $survey->id)
            ->when(
                $status !== null,
                fn ($q) => $q->where('status', $status)
            )
            ->when(
                $workAreaId !== null,
                fn ($q) => $q->where('work_area_id', $workAreaId)
            )
            ->count();
        
        $statusCounts = DB::table('survey_responses')
        ->where('survey_id', $survey->id)
        ->when(
            $status !== null,
            fn ($q) => $q->where('status', $status)
        )
        ->when(
            $workAreaId !== null,
            fn ($q) => $q->where('work_area_id', $workAreaId)
        )
        ->select('status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

        // Frekuensi ranking 1 untuk setiap pertanyaan.
        $frequencies = DB::table('survey_answers as a')
            ->join(
                'survey_responses as r',
                'r.id',
                '=',
                'a.survey_response_id'
            )
            ->where('r.survey_id', $survey->id)
            ->where('a.ranking', 1)
            ->when(
                $status !== null,
                fn ($q) => $q->where('r.status', $status)
            )
            ->when(
                $workAreaId !== null,
                fn ($q) => $q->where('r.work_area_id', $workAreaId)
            )
            ->groupBy('a.survey_section_id', 'a.survey_question_id')
            ->select([
                'a.survey_section_id',
                'a.survey_question_id',
            ])
            ->selectRaw('COUNT(*) as total')
            ->get()
            ->keyBy(
                fn ($row) =>
                    "{$row->survey_section_id}:{$row->survey_question_id}"
            );

        // Jumlah responden yang mempunyai ranking 1
        // pada masing-masing bagian.
        $sectionDenominators = DB::table('survey_answers as a')
            ->join(
                'survey_responses as r',
                'r.id',
                '=',
                'a.survey_response_id'
            )
            ->where('r.survey_id', $survey->id)
            ->where('a.ranking', 1)
            ->when(
                $status !== null,
                fn ($q) => $q->where('r.status', $status)
            )
            ->when(
                $workAreaId !== null,
                fn ($q) => $q->where('r.work_area_id', $workAreaId)
            )
            ->groupBy('a.survey_section_id')
            ->select('a.survey_section_id')
            ->selectRaw(
                'COUNT(DISTINCT a.survey_response_id) as total'
            )
            ->pluck('total', 'survey_section_id');

        $sections = $survey->sections()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with([
                'questions' => fn ($q) =>
                    $q->orderBy('sort_order')->orderBy('id'),
            ])
            ->get();

        $results = $sections->map(function ($section) use (
            $frequencies,
            $sectionDenominators
        ) {
            $respondents = (int) (
                $sectionDenominators[$section->id] ?? 0
            );

            $questions = $section->questions
                ->map(function ($question) use (
                    $section,
                    $frequencies,
                    $respondents
                ) {
                    $key = "{$section->id}:{$question->id}";

                    $count = (int) (
                        $frequencies->get($key)?->total ?? 0
                    );

                    return [
                        'question_id' => $question->id,
                        'question_text' => $question->question_text,
                        'total_rank_1' => $count,
                        'percentage' => $respondents > 0
                            ? round($count / $respondents * 100, 2)
                            : 0,
                    ];
                })
                ->sortByDesc('total_rank_1')
                ->values();

            $max = (int) $questions->max('total_rank_1');

            $winners = $max > 0
                ? $questions
                    ->where('total_rank_1', $max)
                    ->values()
                : collect();

            return [
                'section_id' => $section->id,
                'section_title' => $section->title,
                'respondents' => $respondents,
                'questions' => $questions->all(),
                'winners' => $winners->all(),
                'conclusion' => $this->makeConclusion(
                    $section->title,
                    $winners->all(),
                    $respondents
                ),
            ];
        });

        return [
            'total_respondents' => $totalRespondents,
            'status_counts' => [
                'aktif' => (int) ($statusCounts['aktif'] ?? 0),
                'tidak_aktif' => (int) ($statusCounts['tidak_aktif'] ?? 0),
            ],
            'sections' => $results->values()->all(),
        ];
    }

    private function makeConclusion(
        string $sectionTitle,
        array $winners,
        int $respondents
    ): string {
        if ($respondents === 0 || count($winners) === 0) {
            return "Belum tersedia jawaban ranking 1 pada bagian {$sectionTitle}.";
        }

        $first = $winners[0];

        if (count($winners) === 1) {
            return sprintf(
                'Pada bagian %s, aspek "%s" merupakan prioritas yang paling banyak dipilih sebagai ranking 1 oleh %d responden (%.2f%%).',
                $sectionTitle,
                $first['question_text'],
                $first['total_rank_1'],
                $first['percentage']
            );
        }

        $names = collect($winners)
            ->pluck('question_text')
            ->map(fn ($name) => "\"{$name}\"")
            ->implode(', ');

        return sprintf(
            'Pada bagian %s, terdapat prioritas bersama: %s. Masing-masing dipilih oleh %d responden (%.2f%%).',
            $sectionTitle,
            $names,
            $first['total_rank_1'],
            $first['percentage']
        );
    }
}