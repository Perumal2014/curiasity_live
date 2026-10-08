<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackQuestionOption extends Model
{
    //
    protected $table = 'feedback_question_options';

    protected $fillable = [
        'feedback_question_id',
        'option_text',
        'sort_order',
        'created_at',
        'updated_at'
    ];

    
}
