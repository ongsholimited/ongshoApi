<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Iman\Streamer\VideoStreamer;
use App\Helpers\VideoStream;
use App\Models\User;
use Spatie\Permission\Models\Role;
class TestController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    public function test(Request $request,$qr=null)
    {
        return $request->ip();
        return $all_users_with_all_their_roles = User::with('roles')->get();
        $all_users_with_all_direct_permissions = User::with('permissions')->get();
        $all_roles_in_database = Role::all()->pluck('name');
        $users_without_any_roles = User::doesntHave('roles')->get();
        $all_roles_except_a_and_b = Role::whereNotIn('name', ['role A', 'role B'])->get();
    }
}
