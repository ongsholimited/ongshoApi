<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Menu;
class MenuController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    public function getMenu()
    {
        return response()->json(Menu::with('category')->get());
    }
}
