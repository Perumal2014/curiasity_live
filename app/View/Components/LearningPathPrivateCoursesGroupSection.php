<?php

namespace App\View\Components;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use App\Models\LearningPaths;
use App\Models\LearningPathProgress;
use App\LessonComplete;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CPD\Repositories\Interfaces\CpdRepositoryInterface;

class LearningPathPrivateCoursesGroupSection extends Component
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $user = Auth::user();

        $per_page = 16;

        $category_id = request()->category ?? '';
        $search = request()->search ?? '';

        // ✅ MAIN QUERY (FIXED)
        $query = LearningPaths::with([
                'steps.plancourses.course', // ✅ Proper nested relation
                'steps.plancourses.progress', // ✅ Load progress for each course
            ])
            ->where('organization_id', $user->organization_id);

        // ✅ Filter by specific learning path (IMPORTANT)
        if (!empty($this->id)) {
            $query->where('id', $this->id);
        }

        // ✅ Category Filter
        if ($category_id) {
            $query->where(function ($q) use ($category_id) {
                $q->where('category_id', $category_id)
                  ->orWhere('subcategory_id', $category_id)
                  ->orWhereHas('quiz', function ($q) use ($category_id) {
                      $q->where('category_id', $category_id)
                        ->orWhere('sub_category_id', $category_id);
                  })
                  ->orWhereHas('virtualClass', function ($q) use ($category_id) {
                      $q->where('category_id', $category_id)
                        ->orWhere('sub_category_id', $category_id);
                  });
            });
        }

        // ✅ Search Filter
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // ✅ FINAL DATA
        $courses = $query->latest()->paginate($per_page);

        // ✅ Categories
        $categories = Category::where('status', 1)
            ->with('activeSubcategories')
            ->orderBy('position_order', 'asc')
            ->get();

        $data = [];

        $progressData = LearningPathProgress::where('user_id', $user->id)
        ->get();

        $completedCourseIds = LessonComplete::where('user_id', auth()->id())
                            ->pluck('course_id') // only course IDs
                            ->toArray();

        // ✅ CPD Module
        if (isModuleActive('CPD')) {
            $interface = App::make(CpdRepositoryInterface::class);
            $data['cpds'] = $interface->studentCpd(Auth::id());
        }

        $enrolledCourseIds = CourseEnrolled::where('user_id', auth()->id())
                            ->pluck('course_id')
                            ->toArray();

        return view(
            theme('components.learning-path-private-courses-group-section'),
            array_merge(
                $data,
                compact('category_id', 'search', 'courses', 'categories', 'progressData', 'completedCourseIds', 'enrolledCourseIds')
            )
        );
    }
}