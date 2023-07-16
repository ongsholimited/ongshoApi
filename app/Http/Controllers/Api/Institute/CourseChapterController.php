<?php

namespace App\Http\Controllers\Api\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
