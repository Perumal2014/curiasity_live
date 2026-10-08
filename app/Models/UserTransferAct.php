<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransferAct extends Model
{
    //
    protected $table = 'user_transfer_activities';

    protected $fillable = [
        'user_id',
        'sender_id',
        'receiver_id',
        'activity_type',
        'description',
    ];

}
