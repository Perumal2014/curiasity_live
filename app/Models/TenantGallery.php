<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantGallery extends Model
{
    //
    protected $table = 'tenant_galleries';

    protected $fillable = [
        'organization_id',
        'image_url',
        'status',
    ];
}
