<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
class TestController extends Controller
{
    public function test()
    {
        return HomeSection::with('post')->get();
    }
}