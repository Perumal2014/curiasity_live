<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;

class CatalogPageSection extends Component
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function render()
    {
        $per_page = 15;

        $category_id = $this->request->category ?? '';
        $search = $this->request->search ?? '';

        
         $query = Course::where('status', 1)->where('organization_id', 12)->where('scope',1);

        // Category filter
        if ($category_id) {
            $query->where(function ($q) use ($category_id) {
                $q->where('category_id', $category_id)
                  ->orWhere('subcategory_id', $category_id);
            });
        }

        // Search filter
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Load relations (same as your LMS)
        $query->with([
            'activeReviews',
            'courseLevel',
            'user'
        ]);

        $courses = $query->latest()->paginate($per_page);

        $categories = Category::where('status', 1)
            ->with('activeSubcategories')
            ->orderBy('position_order', 'asc')
            ->get();

        return view(
            theme('components.catalog-page-section'),
            compact('courses', 'categories', 'category_id', 'search')
        );
    }
}