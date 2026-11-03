<?php
namespace App\Jobs;

use App\Models\SurveyReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use RuntimeException;
use Throwable;

class MergeSurveyReport implements ShouldQueue
{
    use Queueable;

    public int $timeout = 240;
    public int $tries = 1;

    public function __construct(
        public int $reportId
    ) {}

    public function handle(): void
    {
        $report = SurveyReport::findOrFail($this->reportId);

        if ($report->completed_batches !== $report->total_batches) {
            throw new RuntimeException(
                'Belum semua batch berhasil dibuat.'
            );
        }

        $report->update(['status' => 'merging']);

        $pdf = new Fpdi();

        for ($page = 1; $page <= $report->total_batches; $page++) {

            $path = sprintf(
                'survey-reports/%d/batch-%04d.pdf',
                $report->id,
                $page
            );

            $disk = Storage::disk('local');

            if (!$disk->exists($path)) {
                throw new RuntimeException(
                    "File batch {$page} tidak ditemukan."
                );
            }

            $source = $disk->path($path);

            $pageCount = $pdf->setSourceFile($source);

            for ($i = 1; $i <= $pageCount; $i++) {

                $template = $pdf->importPage($i);

                $size = $pdf->getTemplateSize($template);

                $pdf->AddPage(
                    $size['orientation'],
                    [$size['width'], $size['height']]
                );

                $pdf->useTemplate($template);
            }
        }

        $finalPath = sprintf(
            'survey-reports/%d/laporan-lengkap.pdf',
            $report->id
        );

        Storage::disk('local')->put(
            $finalPath,
            $pdf->Output('S')
        );

        $report->update([
            'status' => 'completed',
            'file_path' => $finalPath,
            'completed_at' => now(),
            'error_message' => null,
        ]);

        // Hapus file batch setelah file gabungan tersimpan.
        for ($page = 1; $page <= $report->total_batches; $page++) {
            Storage::disk('local')->delete(sprintf(
                'survey-reports/%d/batch-%04d.pdf',
                $report->id,
                $page
            ));
        }
    }

    public function failed(?Throwable $exception): void
    {
        SurveyReport::whereKey($this->reportId)->update([
            'status' => 'failed',
            'error_message' => $exception?->getMessage()
                ?? 'Penggabungan PDF gagal.',
        ]);
    }
}
