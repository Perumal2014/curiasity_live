<?php

namespace App\View\Components;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use App\Models\LearningPaths;
use Modules\CPD\Repositories\Interfaces\CpdRepositoryInterface;

class LearningPathPrivateCoursesSection extends Component
{
    public function render()
    {
        $type = 1;

        $with = [
            'user',
        ];

        

        $per_page = 16;

        $category_id = request()->category ?? '';
        $search = request()->search ?? '';

        $user = Auth::user();

        // dd(
        //     LearningPaths::with(['learningPathRecommend', 'learningPathPrivate'])->get()
        // );

        $groupConditions = [
            1 => $user->profile_id,
            2 => $user->bu,
            3 => $user->dept_id,
            4 => $user->country,
            5 => $user->state,
            6 => $user->city,
            7 => $user->location_code,
            8 => $user->mgt_id,
        ];

        $query = LearningPaths::with([
                'learningPathRecommend',
                'learningPathPrivate'
            ])
            ->withCount('courses') // 👈 THIS LINE
            ->where('organization_id', $user->organization_id)
            ->where(function ($mainQuery) use ($groupConditions) {

                // ✅ Recommended
                $mainQuery->where(function ($q) use ($groupConditions) {

                    $q->where('is_recommended_for', 1)
                    ->whereHas('learningPathRecommend', function ($q) use ($groupConditions) {

                        $q->where(function ($sub) use ($groupConditions) {
                            foreach ($groupConditions as $type => $id) {
                                if (!empty($id)) {
                                    $sub->orWhere(function ($q) use ($type, $id) {
                                        $q->where('grp_type', $type)
                                            ->where('group_id', $id);
                                    });
                                }
                            }
                        });

                    });

                });

                // ✅ Private
                $mainQuery->orWhere(function ($q) use ($groupConditions) {

                    $q->where('path_scope', 0)
                    ->whereHas('learningPathPrivate', function ($q) use ($groupConditions) {

                        $q->where(function ($sub) use ($groupConditions) {
                            foreach ($groupConditions as $type => $id) {
                                if (!empty($id)) {
                                    $sub->orWhere(function ($q) use ($type, $id) {
                                        $q->where('grp_type', $type)
                                            ->where('group_id', $id);
                                    });
                                }
                            }
                        });

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
            theme('components.learning-path-private-courses-section'),
            array_merge(
                $data,
                compact('category_id', 'search', 'courses', 'categories')
            )
        );
    }
}