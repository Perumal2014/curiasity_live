<?php

namespace App\View\Components;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use App\Models\LearningPaths;
use Modules\CPD\Repositories\Interfaces\CpdRepositoryInterface;

class LearningPathSection extends Component
{
    public function render()
    {
        $type = 1; // 1 for learning path, 2 for course

        $with = [
            'user',
        ];

        $per_page = 16;

        $category_id = request()->category ?? '';
        $search = request()->search ?? '';

        $user = Auth::user();

       
        $query = LearningPaths::with([
            'assignments',
            'steps',
            'plancourses'
            ])->whereHas('assignments', function ($q) use ($user) {
            $q->where(function ($subQuery) use ($user) {

                // Profile
                $subQuery->orWhere(function ($q1) use ($user) {
                    $q1->where('grp_type', 1)
                        ->where('group_id', $user->profile_id);
                });

                // Business Unit
                $subQuery->orWhere(function ($q2) use ($user) {
                    $q2->where('grp_type', 2)
                        ->where('group_id', $user->bu);
                });

                // Department
                $subQuery->orWhere(function ($q3) use ($user) {
                    $q3->where('grp_type', 3)
                        ->where('group_id', $user->dept_id);
                });

                // Location Code
                $subQuery->orWhere(function ($q4) use ($user) {
                    $q4->where('grp_type', 4)
                        ->where('group_id', $user->location_code);
                });
            });
            })->whereHas('assignments', function ($q) use ($user) {
            $q->where(function ($subQuery) use ($user) {

                // Profile
                $subQuery->orWhere(function ($q1) use ($user) {
                    $q1->where('grp_type', 1)
                        ->where('group_id', $user->profile_id);
                });

                // Business Unit
                $subQuery->orWhere(function ($q2) use ($user) {
                    $q2->where('grp_type', 2)
                        ->where('group_id', $user->bu);
                });

                // Department
                $subQuery->orWhere(function ($q3) use ($user) {
                    $q3->where('grp_type', 3)
                        ->where('group_id', $user->dept_id);
                });

                // Location Code
                $subQuery->orWhere(function ($q4) use ($user) {
                    $q4->where('grp_type', 4)
                        ->where('group_id', $user->location_code);
                });
            });
        });
        // dd($query->toSql(), $query->getBindings());
        if ($category_id) {
            $query->where(function ($query) use ($category_id) {
                $query->where('category_id', $category_id)
                    ->orWhere('subcategory_id', $category_id)
                    ->orWhereHas('quiz', function ($query) use ($category_id) {
                        $query->where('category_id', $category_id)
                            ->orWhere('sub_category_id', $category_id);
                    })
                    ->orWhereHas('virtualClass', function ($query) use ($category_id) {
                        $query->where('category_id', $category_id)
                            ->orWhere('sub_category_id', $category_id);
                    });
            });
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $courses = $query->with($with)
            ->latest()
            ->paginate($per_page);
        
        // print_r($courses); exit;

        $categories = Category::where('status', 1)
            ->with('activeSubcategories')
            ->orderBy('position_order', 'asc')
            ->get();

        $data = [];
        
        if (isModuleActive('CPD')) {
            $interface = App::make(CpdRepositoryInterface::class);
            $data['cpds'] = $interface->studentCpd(Auth::id());
        }
        
        return view(
            theme('components.learning-path-section'),
            array_merge(
                $data,
                compact('category_id', 'search', 'courses', 'categories')
            )
        );
    }
}