<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FeedbackAnswers;

use App\Models\forms;

use App\Models\FeedbackForms;

use App\Models\FeedbackQuestionOption;

use App\Models\FeedbackQuestion;

class FeedbackQuestion extends Model
{
    protected $table = 'feedback_questions';
    //
    protected $fillable = [
        'question_text',
        'display_order',
        'question_type',
        'status',
        'linear_points',
        'linear_max_label',
        'linear_min_label',

        'is_required',
        'organization_id',
        'feedback_form_id',
        'created_at',
        'updated_at',
    ];
    

    public function forms()
    {
        return $this->belongsToMany(
            FeedbackForms::class,
            'feedback_form_questions',
            'feedback_question_id',
            'feedback_form_id'
        );
    }

    public function answers()
    {
        return $this->hasMany(
            FeedbackAnswers::class,
            'question_id'
        );
    }

    public function options()
    {
        return $this->hasMany(
            FeedbackQuestionOption::class,
            'feedback_question_id'
        )->orderBy('sort_order');
    }

    public function feedbackForms()
    {
        return $this->belongsToMany(
            FeedbackForms::class,
            'feedback_form_questions',
            'feedback_question_id',
            'feedback_form_id'
        );
    }

    public function questions()
    {
        return $this->belongsTo(
            FeedbackQuestion::class,
            'feedback_question_id'
        );
    }

    
}
