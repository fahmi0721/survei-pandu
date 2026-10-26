<?php

namespace App\Services\Admin;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStatistics(): array
    {
        $today = now()->toDateString();

        $totalSurveys = Survey::query()->count();

        $activeSurveys = Survey::query()
            ->where('status', 'active')
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            })
            ->count();

        $totalRespondents = SurveyResponse::query()->count();

        $totalAnswers = SurveyAnswer::query()->count();

        $statusCounts = SurveyResponse::query()
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $latestResponses = SurveyResponse::query()
            ->with([
                'survey:id,title',
                'workArea:id,nama',
            ])
            ->withCount('answers')
            ->orderByDesc('submitted_at')
            ->limit(5)
            ->get();

        return [
            'total_surveys' => $totalSurveys,
            'active_surveys' => $activeSurveys,
            'total_respondents' => $totalRespondents,
            'total_answers' => $totalAnswers,

            'status_counts' => [
                'aktif' => (int) ($statusCounts['aktif'] ?? 0),
                'tidak_aktif' => (int) ($statusCounts['tidak_aktif'] ?? 0),
            ],

            'latest_responses' => $latestResponses,
        ];
    }
}