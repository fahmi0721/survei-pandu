<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\WorkArea;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class SurveyRespondentController extends Controller
{
    public function index(): View
    {
        $surveys = Survey::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $workAreas = WorkArea::query()
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('admin.respondents.index', compact(
            'surveys',
            'workAreas'
        ));
    }

    public function data(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'survey_id' => [
                'nullable',
                'integer',
                'exists:surveys,id',
            ],
            'status' => [
                'nullable',
                Rule::in(['aktif', 'tidak_aktif']),
            ],
            'work_area_id' => [
                'nullable',
                'integer',
                'exists:work_areas,id',
            ],
        ]);

        $rows = SurveyResponse::query()
            ->with([
                'survey:id,title',
                'workArea:id,nama',
            ])
            ->withCount('answers')

            ->when(
                $filters['survey_id'] ?? null,
                fn ($query, $id) =>
                    $query->where('survey_id', $id)
            )

            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) =>
                    $query->where('status', $status)
            )

            ->when(
                $filters['work_area_id'] ?? null,
                fn ($query, $id) =>
                    $query->where('work_area_id', $id)
            )

            ->latest('submitted_at')
            ->get();

        $data = $rows->map(fn ($row) => [
            'id' => $row->id,
            'survey' => $row->survey?->title ?? '—',
            'status' => $row->status,
            'work_area' => $row->workArea?->nama ?? '—',
            'kelas_pandu' => $row->kelas_pandu ?? '—',
            'answers_count' => $row->answers_count,
            'submitted_at' => $row->submitted_at
                ?->format('d/m/Y H:i'),
        ]);

        return response()->json([
            'data' => $data,
            'summary' => [
                'total' => $rows->count(),
                'aktif' => $rows
                    ->where('status_kerja', 'aktif')
                    ->count(),
                'tidak_aktif' => $rows
                    ->where('status_kerja', 'tidak_aktif')
                    ->count(),
            ],
        ]);
    }

    public function show(SurveyResponse $response): JsonResponse
    {
        $response->load([
            'survey:id,title',
            'workArea:id,nama',
            'answers.section:id,title,sort_order',
            'answers.question:id,question_text',
        ]);

        $answers = $response->answers
            ->sortBy(fn ($answer) =>
                sprintf(
                    '%06d-%03d',
                    $answer->section?->sort_order ?? 0,
                    $answer->ranking
                )
            )
            ->values()
            ->map(fn ($answer) => [
                'section_id' => $answer->survey_section_id,
                'section_title' =>
                    $answer->section?->title ?? '—',
                'section_order' =>
                    $answer->section?->sort_order ?? 0,
                'question_text' =>
                    $answer->question?->question_text ?? '—',
                'ranking' => $answer->ranking,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $response->id,
                'survey' => $response->survey?->title,
                'status' => $response->status,
                'work_area' => $response->workArea?->nama,
                'kelas_pandu' => $response->kelas_pandu,
                'kode_anonim' => $response->kode_anonim,
                'saran' => $response->saran,
                'submitted_at_sort' => $response->submitted_at
                    ?->format('Y-m-d H:i:s'),
                'submitted_at' =>
                    $response->submitted_at
                        ?->format('d/m/Y H:i'),
                'answers' => $answers,
            ],
        ]);
    }
}