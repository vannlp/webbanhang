<?php

namespace Modules\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $newProducts = Product::with('avatar')->where('is_active', 1)->orderBy('updated_at', 'desc')->limit(10)->get();
        $test = "<x-site::Test />";

        return $this->viewSite('home', [
            'newProducts' => $newProducts,
            'title' => "Trang chủ - web bán hàng",
            'test' => $test
        ]);
    }
}
