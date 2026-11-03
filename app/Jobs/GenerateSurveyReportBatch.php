<?php
namespace App\Jobs;

use App\Models\SurveyReport;
use App\Models\WorkArea;
use App\Services\Survey\SurveyResultService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateSurveyReportBatch implements ShouldQueue
{
    use Queueable;

    public int $timeout = 180;
    public int $tries = 1;

    public function __construct(
        public int $reportId,
        public int $page,
        public int $perPage = 10
    ) {}

    public function handle(SurveyResultService $service): void
    {
        $report = SurveyReport::findOrFail($this->reportId);

        $report->update(['status' => 'processing']);

        $survey = $report->survey_id
            ? \App\Models\Survey::findOrFail($report->survey_id)
            : null;

        $filters = $report->filters ?? [];

        $status = $filters['status'] ?? null;
        $workAreaId = $filters['work_area_id'] ?? null;

        $respondents = $service->respondentDetails(
            $survey,
            $status,
            $workAreaId,
            $this->page,
            $this->perPage
        );

        // Ringkasan dicetak sekali pada batch pertama.
        $summary = $this->page === 1
            ? $service->summary($survey, $status, $workAreaId)
            : null;

        $workArea = $workAreaId
            ? WorkArea::find($workAreaId)
            : null;

        $pdf = Pdf::loadView(
            'admin.survey-results.print',
            [
                'survey' => $survey,
                'summary' => $summary,
                'respondents' => $respondents,
                'statusFilter' => $status,
                'workArea' => $workArea,
                'printedAt' => now(),
                'page' => $this->page,
                'perPage' => $this->perPage,
                'totalPages' => $report->total_batches,
            ]
        )->setPaper('a4', 'portrait');

        $path = sprintf(
            'survey-reports/%d/batch-%04d.pdf',
            $report->id,
            $this->page
        );

        Storage::disk('local')->put(
            $path,
            $pdf->output()
        );

        unset($pdf, $respondents, $summary);

        $report->increment('completed_batches');
    }

    public function failed(?Throwable $exception): void
    {
        SurveyReport::whereKey($this->reportId)->update([
            'status' => 'failed',
            'error_message' => $exception?->getMessage()
                ?? 'Job pembuatan PDF gagal.',
        ]);
    }
}
