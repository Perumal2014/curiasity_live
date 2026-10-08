<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPathPrivate extends Model
{
    //
    protected $table = 'learning_path_private';

    protected $fillable = [
        'learning_path_id',
        'group_id',
        'grp_type',
        'created_at',
        'updated_at'
    ];
}
