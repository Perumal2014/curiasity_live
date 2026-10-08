<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    //
    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'description',
        'announce_link',
        'start_date',
        'end_date',
        'organization_id',
        'created_by',
    ];

}
