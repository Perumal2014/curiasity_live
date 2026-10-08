<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FeedbackQuestion;

use App\Models\FeedbackSubmission;

class FeedbackAnswers extends Model
{
    //
    protected $table = 'feedback_answers';

    protected $fillable = [
        'feedback_submission_id',
        'question_id',
        'answer',
        'created_at',
        'updated_at',
    ];

    public function question()
    {
        return $this->belongsTo(
            FeedbackQuestion::class,
            'question_id'
        );
    }

    public function submission()
    {
        return $this->belongsTo(
            FeedbackSubmission::class,
            'submission_id'
        );
    }


}
