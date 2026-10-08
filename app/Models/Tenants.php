<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use App\Models\TenantType;
use App\Models\User;
use App\Models\TenantReviews;
use Modules\CourseSetting\Entities\Course;

class Tenants extends Model
{
    //
    protected $table = 'tenant_list';
    
    protected $guarded = ['id'];


    protected $fillable = ['tenant_name', 'tenant_email', 'tenant_slug', 'tenant_type', 'tenant_logo', 'tenant_banner', 'fav_icon', 'phone', 'tenant_slogan', 'brouchure', 'facebook_link', 'twitter_link', 'linkedin_link', 'instagram_link', 'youtube_link', 'created_at', 'updated_at', 'address', 'tenant_experience', 'public_student_reg', 'public_teacher_reg', 'tenant_services', 'tenant_plan_id', 'verified_status'];


    public function tenantType()
    {
        return $this->belongsTo(TenantType::class, 'tenant_type', 'id');
    }

    public function tenantUsers()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }

    public function waitingUsers()
    {
        return $this->hasMany(User::class, 'organization_id', 'id')->where('status', '2');
    }

    public function tenant_reviews()
    {
        return $this->hasMany(TenantReviews::class, 'organization_id', 'id');
    }

    public function tenant_courses()
    {
        return $this->hasMany(Course::class, 'organization_id', 'id');
    }

}
