<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
use App\Http\Traits\SendDataApi;
class SectionController extends Controller
{
    public function getSection()
    {
        $section=HomeSection::orderBy('serial','asc')->get();
        if($section->count()>0){
            return SendDataApi::bind($section);
        }
        return SendDataApi::bind('data no found',404);
    }
}