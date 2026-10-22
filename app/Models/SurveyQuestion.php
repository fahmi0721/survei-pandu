<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SurveyQuestion extends Model
{
     protected $fillable = [
        'survey_section_id',
        'question_text',
        'sort_order',
        'description',
        'is_active',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(
            SurveySection::class,
            'survey_section_id'
        );
    }
}
