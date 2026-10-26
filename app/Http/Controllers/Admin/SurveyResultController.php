<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\WorkArea;
use App\Services\Survey\SurveyResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SurveyResultController extends Controller
{
    public function index(): View
    {
        return view('admin.survey-results.index', [
            'surveys' => Survey::orderBy('title')->get(['id', 'title']),
            'workAreas' => WorkArea::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function data(
        Request $request,
        SurveyResultService $service
    ): JsonResponse {
        $filters = $request->validate([
            'survey_id' => [
                'required',
                'integer',
                Rule::exists('surveys', 'id'),
            ],
            'status' => [
                'nullable',
                Rule::in(['aktif', 'tidak_aktif']),
            ],
            'work_area_id' => [
                'nullable',
                'integer',
                Rule::exists('work_areas', 'id'),
            ],
        ]);

        $survey = Survey::findOrFail($filters['survey_id']);

        $status = $filters['status'] ?? null;

        $workAreaId = isset($filters['work_area_id'])
            ? (int) $filters['work_area_id']
            : null;

        return response()->json([
            'success' => true,
            'respondents' => $service->perRespondent(
                $survey,
                $status,
                $workAreaId
            ),
            'summary' => $service->summary(
                $survey,
                $status,
                $workAreaId
            ),
        ]);
    }
}