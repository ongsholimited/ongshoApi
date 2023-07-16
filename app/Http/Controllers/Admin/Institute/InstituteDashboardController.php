<?php

namespace App\Http\Controllers\Admin\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstituteDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function homePage()
    {
        return view('institute.dashboard');
    }
}
