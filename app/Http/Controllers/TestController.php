<?php

namespace App\Http\Controllers;

use App\Helpers\Terminal as HelpersTerminal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Hexadog\ThemesManager\Facades\ThemesManager;

class TestController extends Controller
{

    public function __construct()
    {
        // parent::__construct();

        // Specify theme name with vendor
        // in case multiple themes with same name are provided by multiple vendor
        // ThemesManager::set('demo/demo');
    }
    public function index(Request $request) {

        (new HelpersTerminal('/c/laragon/www/webBanHang'))->getLL();
    }

    public function apiTest() {
        return response()->json([
            'message' => "ok"
        ]);
    }
}
