<?php

namespace App\Models;

use App\Models\Tenants;

use Illuminate\Database\Eloquent\Model;

class TenantType extends Model
{
    //
    protected $guarded = ['id'];

    protected $table = 'tenant_types';

    protected $fillable = [
        'tenant_id',
        'tenant_name',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenants::class, 'tenant_type', 'id');
    }

}
