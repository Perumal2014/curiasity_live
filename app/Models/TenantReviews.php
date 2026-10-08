<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantReviews extends Model
{
    //
    protected $table = 'tenant_reviews';

    protected $fillable = [
        'organization_id',
        'reviewer_id',
        'review_text',
        'rating',
        'status'
    ];
}
