<?php

namespace App\Http\Controllers;

use App\Http\Helper\AuthHelper;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        // dd(AuthHelper::checkPermission(["LISTROLE"]));
        // dd();
        $user = User::find(1);
        return view('admin.dashboard', [
            'user' => $user
        ]);
    }
}
