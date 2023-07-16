<?php

namespace App\Http\Controllers\Api\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['getAllCourse']]);
    }

    public function getAllCourse(){
        $data=Course::with('user','category','chapter')->where('status',1)->get();
        return response()->json($data);
    }

    public function getCourseById($course_id)
    {
        $data=Course::with('user','category','chapter')->where('id',$course_id)->get();
        return response()->json($data);
    }
    public function getCourseByCat($category_id)
    {
        $data=Course::with('user','category','chapter')->where('id',$category_id)->get();
        return response()->json($data);
    }
}
