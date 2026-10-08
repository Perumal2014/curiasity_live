<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FeedbackForms;

use App\Models\FeedbackAnswers;


class FeedbackSubmission extends Model
{
    //
    protected $table = 'feedback_submissions';

    protected $fillable = [
        'name',
        'email',
        'user_id',
        'location',
        'department',
        'designation',
        'function_name',
        'feedback_form_id',
        'comments',
        'created_at',
        'updated_at',

    ];

    public function form()
    {
        return $this->belongsTo(
            FeedbackForms::class,
            'feedback_form_id'
        );
    }

    public function answers()
    {
        return $this->hasMany(
            FeedbackAnswers::class,
            'feedback_submission_id'
        );
    }

}
