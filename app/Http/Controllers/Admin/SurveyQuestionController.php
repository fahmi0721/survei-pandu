<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreQuestionRequest;
use App\Http\Requests\Survey\UpdateQuestionRequest;
use App\Http\Requests\Survey\ReorderQuestionRequest;
use App\Models\Survey;
use App\Models\SurveySection;
use App\Models\SurveyQuestion;
use App\Services\Survey\SurveyQuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SurveyQuestionController extends Controller
{
    public function __construct(
        private SurveyQuestionService $service
    ) {}

    public function index(
        Survey $survey,
        SurveySection $section
    ): View {

        $this->service->ensureSection($survey, $section);

        return view('admin.questions.index', compact(
            'survey',
            'section'
        ));
    }

    public function data(
        Survey $survey,
        SurveySection $section
    ): JsonResponse {

        return response()->json([
            'success' => true,
            'data' => $this->service->getAll($survey, $section),
        ]);
    }

    public function store(
        StoreQuestionRequest $request,
        Survey $survey,
        SurveySection $section
    ): JsonResponse {

        $question = $this->service->create(
            $survey,
            $section,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil ditambahkan.',
            'data' => $question,
        ], 201);
    }

    public function show(
        Survey $survey,
        SurveySection $section,
        SurveyQuestion $question
    ): JsonResponse {

        $this->service->ensureSection($survey, $section);
        $this->service->ensureQuestion($section, $question);

        return response()->json([
            'success' => true,
            'data' => $question,
        ]);
    }

    public function update(
        UpdateQuestionRequest $request,
        Survey $survey,
        SurveySection $section,
        SurveyQuestion $question
    ): JsonResponse {

        $question = $this->service->update(
            $survey,
            $section,
            $question,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil diperbarui.',
            'data' => $question,
        ]);
    }

    public function destroy(
        Survey $survey,
        SurveySection $section,
        SurveyQuestion $question
    ): JsonResponse {

        $this->service->delete(
            $survey,
            $section,
            $question
        );

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil dihapus.',
        ]);
    }

    public function reorder(
        ReorderQuestionRequest $request,
        Survey $survey,
        SurveySection $section
    ): JsonResponse {

        $this->service->reorder(
            $survey,
            $section,
            $request->validated('ids')
        );

        return response()->json([
            'success' => true,
            'message' => 'Urutan pertanyaan berhasil disimpan.',
        ]);
    }
}