<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained('surveys')
                ->restrictOnDelete();

            $table->foreignId('work_area_id')
                ->nullable()
                ->constrained('work_areas')
                ->restrictOnDelete();

            $table->string('status_kerja', 30);

            $table->string('kelas_pandu', 100)->nullable();
            $table->string('masa_kerja', 100)->nullable();
            $table->string('kode_anonim', 100)->nullable();

            $table->text('saran')->nullable();

            // Mencegah request identik tersimpan dua kali
            $table->uuid('submission_token')->unique();

            $table->timestamp('submitted_at');

            $table->timestamps();

            $table->index(['survey_id', 'status_kerja']);
            $table->index(['survey_id', 'work_area_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};