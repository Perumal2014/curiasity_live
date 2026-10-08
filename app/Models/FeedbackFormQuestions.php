<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackFormQuestions;

class FeedbackFormQuestions extends Model
{
    //

    protected $table="feedback_form_questions";

    protected $fillable = [
        'feedback_form_id',
        'feedback_question_id',
    ];

    public function question()
    {
        return $this->belongsTo(
            FeedbackQuestion::class,
            'feedback_question_id'
        );
    }

    public function feedbackQuestion()
    {
        return $this->belongsTo(
            FeedbackQuestion::class,
            'feedback_question_id'
        );
    }

    public function feedbackForm()
    {
        return $this->belongsTo(
            FeedbackFormQuestions::class,
            'feedback_form_id'
        );
    }


}
