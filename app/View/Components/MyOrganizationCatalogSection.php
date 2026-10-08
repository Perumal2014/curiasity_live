<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Category;
use Illuminate\Support\Facades\Auth;

class MyOrganizationCatalogSection extends Component
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

public function render()
{
    $per_page = 12;

    $category_id = request('category');
    $search = request('search');

    $query = Course::where('organization_id', Auth::user()->organization_id)
        ->where('status', 1)
        ->with(['category', 'courseLevel', 'user']);

    // ✅ CATEGORY FILTER (same logic as MyCourses)
    if ($category_id) {
        $query->where(function ($q) use ($category_id) {
            $q->where('category_id', $category_id)
              ->orWhere('subcategory_id', $category_id);
        });
    }

    // ✅ SEARCH FILTER
    if ($search) {
        $query->where('title', 'like', '%' . $search . '%');
    }

    $courses = $query->latest()->paginate($per_page)->withQueryString();

    // ✅ SAME CATEGORY STRUCTURE AS MY COURSES
    $categories = Category::where('status', 1)
        ->with('activeSubcategories')
        ->orderBy('position_order', 'asc')
        ->get();

    return view(
        theme('components.my-organization-catalog-section'),
        compact('courses', 'categories', 'category_id', 'search')
    );
}
    // public function render()
    // {
    //     $user = Auth::user();
        
    //     $query = Course::where('organization_id', Auth::user()->organization_id)->where('status', 1)
    //         ->with('category', 'quiz', 'user');

    //     if (request('category')) {
    //         $query->where('category_id', request('category'));
    //     }

    //     $categories = Category::get();

    //     $courses = $query->paginate(12)->withQueryString();

    //     return view(theme('components.my-organization-catalog-section'), compact('courses', 'categories'));
    // }
}