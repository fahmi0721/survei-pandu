<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Survey;
use App\Services\Survey\SurveyService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SurveyController extends Controller
{
     public function __construct(
        private SurveyService $service
    ) {}

    public function index(): View
    {
        return view('admin.surveys.index');
    }

    public function data(): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getAll(),
        ]);
    }

    public function store(StoreSurveyRequest $request): JsonResponse
    {
        $survey = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil ditambahkan.',
            'data' => $survey,
        ], 201);
    }

    public function show(Survey $survey): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $survey,
        ]);
    }

    public function update(
        UpdateSurveyRequest $request,
        Survey $survey
    ): JsonResponse {

        $survey = $this->service->update(
            $survey,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil diperbarui.',
            'data' => $survey,
        ]);
    }

    public function destroy(Survey $survey): JsonResponse
    {
        $this->service->delete($survey);

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil dihapus.',
        ]);
    }
}
