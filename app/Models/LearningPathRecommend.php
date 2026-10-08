<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPathRecommend extends Model
{
    //
    protected $table = 'learning_path_recommend';

    protected $fillable = [
        'learning_path_id',
        'group_id',
        'grp_type',
        'created_at',
        'updated_at'
    ];
}
