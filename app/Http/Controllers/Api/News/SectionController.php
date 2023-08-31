<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
class SectionController extends Controller
{
    public function getSection()
    {
        $section=HomeSection::orderBy('serial','asc')->get();
        return response()->json($section);
    }
}