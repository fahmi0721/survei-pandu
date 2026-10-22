<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'survey_response_id',
        'survey_section_id',
        'survey_question_id',
        'ranking',
    ];

    protected function casts(): array
    {
        return [
            'ranking' => 'integer',
        ];
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(SurveySection::class, 'survey_section_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}