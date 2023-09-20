<?php

namespace App\Http\Controllers\Admin\Sms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        return view('sms.dashboard');
    }
}
