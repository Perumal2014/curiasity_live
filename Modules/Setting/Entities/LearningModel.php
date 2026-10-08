<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningModel extends Model
{
    //
    use HasFactory;

    //
    // use Tenantable;
    protected $table = 'learning_types';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];
}
