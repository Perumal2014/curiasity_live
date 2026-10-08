<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPlanGroup extends Model
{
    //
    protected $table = 'learning_plan_groups';

     protected $fillable = [
        'learning_plan_id',
        'group_id',
        'group_type',
        'created_at',
        'updated_at'
    ];
}
