<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Category;
use App\Models\LearningPathAssignments;
use App\Models\LearningPathStep;
use App\Models\LearningPathCourse;
use App\Models\LearningPathPrivate;
use App\Models\LearningPathRecommend;
use Spatie\Translatable\HasTranslations;

class LearningPaths extends Model
{
    use HasTranslations, HasFactory;

    protected $table = 'learning_paths';

    public $translatable = ['title', 'about'];

    protected $fillable = [
        'title',
        'about',
        'category_id',
        'sub_category_id',
        'primary_skill_id',
        'secondary_skill_id',
        'is_recommended_for',
        'path_type',
        'path_scope',
        'path_level',
        'thumbnail',
        'duration',
        'approve_type',
        'course_sequence',
        'has_certificate',
        'created_by',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'learning_path_courses',
            'learning_path_id',
            'course_id'
        );
    }

    public function assignments()
    {
        return $this->hasMany(LearningPathAssignments::class, 'learning_path_id');
    }

    public function steps()
    {
        return $this->hasMany(LearningPathStep::class, 'learning_path_id');
    }

    public function plancourses()
    {
        return $this->hasMany(LearningPathCourse::class, 'learning_path_id');
    }

    public function learningPathRecommend()
    {
        return $this->hasMany(LearningPathRecommend::class, 'learning_path_id', 'id');
    }

    public function learningPathPrivate()
    {
        return $this->hasMany(LearningPathPrivate::class, 'learning_path_id', 'id');
    }
}