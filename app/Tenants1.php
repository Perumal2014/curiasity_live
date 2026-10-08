<?php

namespace App\Models;

use App\Models\LmsInstitute;
use App\Models\UserEducation;
use App\Models\User;
use App\Models\TenantType;




//class User extends Authenticatable
class Tenants extends Model
{
    use HasSlug;
    use HasApiTokens, Notifiable, UserChatMethods, Liker;

   // protected $connection = null;
    protected $table = 'tenant_list';
    protected $guarded = ['id'];
    

    public function TenantUsers()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }

    public function TenantType()
    {
        return $this->hasMany(TenantType::class, 'tenant_type', 'id');
    }


}




