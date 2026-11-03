<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateSurveyReportBatch;
use App\Jobs\MergeSurveyReport;
use App\Models\Survey;
use App\Models\SurveyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SurveyReportController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'survey_id' => [
                'required', 'integer',
                Rule::exists('surveys', 'id'),
            ],
            'status' => [
                'nullable',
                Rule::in(['aktif', 'tidak_aktif']),
            ],
            'work_area_id' => [
                'nullable', 'integer',
                Rule::exists('work_areas', 'id'),
            ],
        ]);

        $survey = Survey::findOrFail($data['survey_id']);

        $filters = [
            'status' => $data['status'] ?? null,
            'work_area_id' => isset($data['work_area_id'])
                ? (int) $data['work_area_id']
                : null,
        ];

        $query = DB::table('survey_responses')
            ->where('survey_id', $survey->id)
            ->when(
                $filters['status'],
                fn ($q) => $q->where('status', $filters['status'])
            )
            ->when(
                $filters['work_area_id'],
                fn ($q) => $q->where(
                    'work_area_id',
                    $filters['work_area_id']
                )
            );

        $total = $query->count();

        if ($total === 0) {
            return response()->json([
                'message' => 'Belum ada responden untuk filter ini.',
            ], 422);
        }

        $perPage = 10;
        $totalBatches = (int) ceil($total / $perPage);

        $report = SurveyReport::create([
            'survey_id' => $survey->id,
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'filters' => $filters,
            'total_respondents' => $total,
            'total_batches' => $totalBatches,
        ]);

        try {
            $jobs = [];

            for ($page = 1; $page <= $totalBatches; $page++) {
                $jobs[] = new GenerateSurveyReportBatch(
                    $report->id,
                    $page,
                    $perPage
                );
            }

            $jobs[] = new MergeSurveyReport($report->id);

            Bus::chain($jobs)
                ->onConnection('database')
                ->onQueue('survey-reports')
                ->dispatch();

        } catch (\Throwable $e) {
            $report->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }

        return response()->json([
            'success' => true,
            'report_id' => $report->id,
            'message' => 'Laporan masuk antrean pemrosesan.',
        ], 202);
    }

    public function status(Request $request, SurveyReport $report)
    {
        abort_unless(
            (int) $report->user_id === (int) $request->user()->id,
            403
        );

        $progress = $report->total_batches > 0
            ? (int) floor(
                $report->completed_batches
                / $report->total_batches * 95
            )
            : 0;

        if ($report->status === 'completed') {
            $progress = 100;
        }

        return response()->json([
            'status' => $report->status,
            'progress' => $progress,
            'completed_batches' => $report->completed_batches,
            'total_batches' => $report->total_batches,
            'message' => $report->status === 'failed'
                ? 'Pembuatan laporan gagal. Hubungi administrator.'
                : null,
            'download_url' => $report->status === 'completed'
                ? route('admin.survey-reports.download', $report)
                : null,
        ]);
    }

    public function download(Request $request, SurveyReport $report)
    {
        abort_unless(
            (int) $report->user_id === (int) $request->user()->id,
            403
        );

        abort_unless(
            $report->status === 'completed'
            && $report->file_path
            && Storage::disk('local')->exists($report->file_path),
            404
        );

        return Storage::disk('local')->download(
            $report->file_path,
            'Laporan-Survei-Lengkap-'.$report->id.'.pdf'
        );
    }
}
