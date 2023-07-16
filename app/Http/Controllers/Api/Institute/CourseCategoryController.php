<?php

namespace App\Http\Controllers\Api\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
class CourseCategoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function getCourseCategory()
    {
        $data= CourseCategory::where('status',1)->get();
        return response()->json($data);
    }

    public function getCourseCategoryById($id)
    {
        $data= CourseCategory::where('id',$id)->where('status',1)->get();
        return response()->json($data);
    }
}
