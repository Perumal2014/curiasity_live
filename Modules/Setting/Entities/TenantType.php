<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Setting\Database\Factories\TenantTypeFactory;

class TenantType extends Model
{
    use HasFactory;

    //
    // use Tenantable;
    protected $table = 'tenant_types';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    // protected static function newFactory(): TenantTypeFactory
    // {
    //     // return TenantTypeFactory::new();
    // }
}
