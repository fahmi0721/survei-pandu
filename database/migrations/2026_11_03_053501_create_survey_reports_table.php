<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        Schema::create('survey_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained('surveys')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('status')->default('pending');

            $table->json('filters')->nullable();

            $table->unsignedInteger('total_respondents')->default(0);
            $table->unsignedInteger('total_batches')->default(0);
            $table->unsignedInteger('completed_batches')->default(0);

            $table->string('file_path')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_reports');
    }
};
