<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSectionRequest;
use App\Http\Requests\Survey\UpdateSectionRequest;
use App\Http\Requests\Survey\ReorderSectionRequest;
use App\Models\Survey;
use App\Models\SurveySection;
use App\Services\Survey\SurveySectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SurveySectionController extends Controller
{
    public function __construct(
        private SurveySectionService $service
    ) {}

    public function index(Survey $survey): View
    {
        return view('admin.sections.index', compact('survey'));
    }

    public function data(Survey $survey): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getAll($survey),
        ]);
    }

    public function store(
        StoreSectionRequest $request,
        Survey $survey
    ): JsonResponse {

        $section = $this->service->create(
            $survey,
            $request->validated()
        );
        return response()->json([
            'success' => true,
            'message' => 'Bagian survei berhasil ditambahkan.',
            'data' => $section,
        ], 201);
    }

    public function show(
        Survey $survey,
        SurveySection $section
    ): JsonResponse {

        abort_unless($section->survey_id === $survey->id, 404);

        return response()->json([
            'success' => true,
            'data' => $section,
        ]);
    }

    public function update(
        UpdateSectionRequest $request,
        Survey $survey,
        SurveySection $section
    ): JsonResponse {

        $section = $this->service->update(
            $survey,
            $section,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Bagian survei berhasil diperbarui.',
            'data' => $section,
        ]);
    }

    public function destroy(
        Survey $survey,
        SurveySection $section
    ): JsonResponse {

        $this->service->delete($survey, $section);

        return response()->json([
            'success' => true,
            'message' => 'Bagian survei berhasil dihapus.',
        ]);
    }

    public function reorder(
        ReorderSectionRequest $request,
        Survey $survey
    ): JsonResponse {

        $this->service->reorder(
            $survey,
            $request->validated('ids')
        );

        return response()->json([
            'success' => true,
            'message' => 'Urutan bagian berhasil disimpan.',
        ]);
    }
}