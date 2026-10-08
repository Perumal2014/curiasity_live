<?php

namespace App\View\Components;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use Modules\CPD\Repositories\Interfaces\CpdRepositoryInterface;

class PrivateCoursesSection extends Component
{
    public function render()
    {
        if (routeIs('myClasses')) {
            $type = 3;
        } elseif (routeIs('myQuizzes')) {
            $type = 2;
        } elseif (routeIs('tenant.myCourses')) {
            $type = 1;
        } elseif (routeIs('myCompletedCourses')) {
            $type = 1;
        } elseif (routeIs('PrivateCourses')) {
            $type = 1;
        } else {
            $type = 4;
        }

        $with = [
            'activeReviews',
            'courseLevel',
            'BookmarkUsers',
            'user',
            'reviews',
            'enrollUsers',
            'coursePrivate'
        ];

        if ($type == 1) {
            $with[] = 'completeLessons';
            $with[] = 'lessons';
        } elseif ($type == 2) {
            $with[] = 'quiz';
            $with[] = 'quiz.assign';
        } elseif ($type == 3) {
            $with[] = 'class';
            $with[] = 'class.zoomMeetings';

            if (isModuleActive('BBB')) {
                $with[] = 'class.bbbMeetings';
            }

            if (isModuleActive('Jisti')) {
                $with[] = 'class.jitsiMeetings';
            }
        }

        $per_page = 16;

        $category_id = request()->category ?? '';
        $search = request()->search ?? '';

        $user = Auth::user();

       

        $query = Course::query()
            ->where('type', $type)
            ->where('status', 1)
            ->where('scope', 0) // only private courses
            ->whereHas('coursePrivate', function ($q) use ($user) {
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
                    $subQuery->orWhere(function ($q) use ($user) {
                        $q->where('grp_type', 4)->where('role_id', $user->country);
                    });

                    $subQuery->orWhere(function ($q) use ($user) {
                        $q->where('grp_type', 5)->where('role_id', $user->state);
                    });

                    $subQuery->orWhere(function ($q) use ($user) {
                        $q->where('grp_type', 6)->where('role_id', $user->city);
                    });
                    $subQuery->orWhere(function ($q) use ($user) {
                        $q->where('grp_type', 7)->where('role_id', $user->location_code);
                    });
                    $subQuery->orWhere(function ($q) use ($user) {
                        $q->where('grp_type', 8)->where('role_id', $user->mgt_id);
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
            theme('components.private-courses-section'),
            array_merge(
                $data,
                compact('category_id', 'search', 'courses', 'categories')
            )
        );
    }
}