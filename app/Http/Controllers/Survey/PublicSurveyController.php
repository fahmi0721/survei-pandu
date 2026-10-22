<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\WorkArea;
use Illuminate\View\View;
use App\Http\Requests\Survey\StoreSurveyResponseRequest;
use App\Services\Survey\SurveyResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicSurveyController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $surveys = Survey::query()
            ->where('status', 'active')

            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', $today);
            })

            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            })

            ->withCount([
                'sections as active_sections_count' => fn ($query) =>
                    $query->where('is_active', true),
            ])

            ->latest()
            ->get();

        return view('survey.index', compact('surveys'));
    }
    public function success(Request $request): View
    {
        $receipt = $request->session()->pull(
            'survey_submission_success'
        );

        if (!$receipt) {
            abort(404);
        }

        return view('survey.success', [
            'surveyTitle' => $receipt['survey_title'],
        ]);
    }
    public function submit(
        StoreSurveyResponseRequest $request,
        Survey $survey,
        SurveyResponseService $service
    ): JsonResponse {
        // return response()->json($request->all());
        $response = $service->store(
            $survey,
            $request->validated()
        );
         // Penanda sukses hanya dibuat setelah proses
        // penyimpanan database berhasil.
        $request->session()->put(
            'survey_submission_success',
            [
                'survey_title' => $survey->title,
                'response_id' => $response->id,
            ]
        );
        return response()->json([
            'success' => true,
            'message' => 'Jawaban survei berhasil disimpan. Terima kasih atas partisipasi Anda.',
            'redirect' => route('survey.public.success'),
            'data' => [
                'response_id' => $response->id,
                'submitted_at' => $response->submitted_at?->toIso8601String(),
            ],
        ], 201);
    }
    public function show(Survey $survey): View
    {
        abort_unless($survey->status === 'active', 404);

        $today = now()->startOfDay();
        abort_if($survey->start_date && $today->lt($survey->start_date->startOfDay()), 404);
        abort_if($survey->end_date && $today->gt($survey->end_date->endOfDay()), 404);

        $sections = $survey->sections()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with(['questions' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id');
            }])
            ->get();

        abort_if($sections->isEmpty(), 503, 'Survei belum memiliki bagian aktif.');
        abort_if($sections->contains(fn ($s) => $s->questions->count() !== 10),
            503, 'Setiap bagian aktif harus memiliki tepat 10 aspek aktif.');

        $sectionData = $sections->map(fn ($section) => [
            'id' => $section->id,
            'title' => $section->title,
            'question' => $section->description ?? '',
            'items' => $section->questions->map(fn ($question) => [
                'id' => $question->id,
                'text' => $question->question_text,
                // 'description' => $question->description ?? '',
            ])->values()->all(),
        ])->values();
        $workAreas = WorkArea::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('nama')
            ->get(['id', 'kode', 'nama']);

        return view('survey.show', compact('survey', 'sectionData', 'workAreas'));
    }
}
