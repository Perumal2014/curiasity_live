<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackSubmission;

class FeedbackForms extends Model
{
    //
    protected $table = 'feedback_forms';

    protected $fillable = [
        'form_name',
        'status',
        'organization_id',
    ];

    public function formQuestions()
    {
        return $this->hasMany(
            FeedbackQuestion::class,
            'feedback_form_id'
        );
    }

    public function submissions()
    {
        return $this->hasMany(
            FeedbackSubmission::class,
            'feedback_form_id'
        );
    }

    public function feedbackQuestions()
    {
        return $this->belongsToMany(
            FeedbackQuestion::class,
            'feedback_form_questions',
            'feedback_form_id',
            'feedback_question_id'
        );
    }


    
}
