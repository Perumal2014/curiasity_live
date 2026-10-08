<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\LearningPlan;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use Illuminate\Support\Facades\Auth;

class LearningPlanSection extends Component
{
    /**
     * Create a new component instance.
     */
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

        $query = LearningPlan::with([
                'milestones',
                'courses',
                'schedules' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ])
            ->whereHas('schedules', function ($q) use ($user) {
                $q->where('user_id', $user->id);
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
            theme('components.learning-plan-section'),
            array_merge(
                $data,
                compact('category_id', 'search', 'courses', 'categories')
            )
        );
    }
}
