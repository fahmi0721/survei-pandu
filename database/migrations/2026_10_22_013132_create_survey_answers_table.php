<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_response_id')
                ->constrained('survey_responses')
                ->cascadeOnDelete();

            $table->foreignId('survey_section_id')
                ->constrained('survey_sections')
                ->restrictOnDelete();

            $table->foreignId('survey_question_id')
                ->constrained('survey_questions')
                ->restrictOnDelete();

            $table->unsignedTinyInteger('ranking');

            $table->timestamps();

            // Pertanyaan hanya boleh dijawab sekali
            $table->unique(
                ['survey_response_id', 'survey_question_id'],
                'unique_response_question'
            );

            // Tidak boleh ada ranking sama dalam satu bagian
            $table->unique(
                [
                    'survey_response_id',
                    'survey_section_id',
                    'ranking'
                ],
                'unique_response_section_rank'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};