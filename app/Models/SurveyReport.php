<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyReport extends Model
{
    protected $fillable = [
        'survey_id',
        'user_id',
        'status',
        'filters',
        'total_respondents',
        'total_batches',
        'completed_batches',
        'file_path',
        'error_message',
        'completed_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'completed_at' => 'datetime',
    ];
}
