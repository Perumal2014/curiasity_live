<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //

    protected $guarded = ['id'];

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'route',
        'type',
        'backend',
        'parent_route',
        'ecommerce',
        'icon',
        'menu_status',
        'old_name',
        'old_type',
        'old_parent_route',
        'tenant_id',
        'section_id',
        'created_by',
        'updated_by',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }



}
