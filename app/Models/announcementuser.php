<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Announcements;

class announcementuser extends Model
{
    //
    protected $table = 'announcement_user';

    protected $fillable = [
        'user_id',
        'announcement_id',
        'status',
        'created_by',
        'updated_by'
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcements::class, 'announcement_id');
    }
    
}
