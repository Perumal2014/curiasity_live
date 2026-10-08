<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TransferResourceDetail extends Model
{
    //
    protected $table = 'transfer_resource_detail';

    protected $fillable = [
        'transfer_user_id',
        'request_sender_id',
        'request_receiver_id',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public function transferUser()
    {
        return $this->belongsTo(
            User::class,
            'transfer_user_id'
        );
    }

   
}
