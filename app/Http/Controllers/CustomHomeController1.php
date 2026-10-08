<?php



namespace App\Http\Controllers;



use App\Http\Controllers\Controller;

use Modules\CourseSetting\Entities\Category;

use Modules\CourseSetting\Entities\Course;

use Illuminate\Http\Request;



class CustomHomeController extends Controller

{

    public function index()

    {

        $categories = Category::whereNull('parent_id') ->where('status', 1)->take(12)->get();

        $courses = Course::where('type', 1)->where('organization_id', 12)->where('status', 1)->where('scope',1)->latest()->take(10)->get();

        $course_count = Course::where('type', 1)->where('status', 1)->count();

        // print_r($courses); exit;



         return view(theme('home.index'), compact('categories', 'courses' , 'course_count'));

    }

}