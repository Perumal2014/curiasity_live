<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPathAssignments extends Model
{
    //
     protected $table = 'learning_path_assignments';

     protected $fillable = [
        'learning_path_id',
        'user_id',
        'grp_type',
        'assigned_at',
        'status',
        'created_at',
        'updated_at'
    ];
}
