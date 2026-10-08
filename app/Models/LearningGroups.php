<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningGroups extends Model
{
    //
    protected $table = 'learning_groups';

    public $translatable = ['title', 'about'];

    protected $fillable = [
        'group_name',
        'group_status',
        'organization_id',
        'created_at',
        'updated_at'
    ];

}
