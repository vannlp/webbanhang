<?php

namespace Modules\Site\Http\Controllers;

use App\Http\Controllers\Controller ;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class SiteController extends Controller
{

    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
    )
    {
    }

    public function detailProduct(Request $request, $slug) {
        $product = Product::where('slug', $slug)->first();
        $input = [
            'a' => null,
            'b' => 1
        ];
        // $all = compact();
        Event::dispatch('detailProduct.customInput', [&$input]);
        // dd($input);

        if(!$product) {
            return abort(404);
        }

        return $this->viewSite('product', [
            'product' => $product,
            'title' => $product->name,
        ]);
    }

    public function categoryPage(Request $request, $slug) {
        $category = $this->categoryRepository->getModel()->where('slug', $slug)->first();
        
        // lấy tất cả các danh mục con
        $childCategories = $category->allSubCategories()->get();
        $idChildCategories = $childCategories->pluck('id')->toArray();

        $idChildCategories[] = $category->id;
        $products = $this->productRepository->getListProductByCategory($idChildCategories, [], ['avatar']);
        
        return $this->viewSite('categoryPage', [
            'category' => $category,
            'products' => $products,  
        ]);
    }

    public function searchPage(Request $request) {
        $search = $request->search;
        // dd($search);
        
        $input = [
            'name' => $search,
            'limit' => 10
        ];
        $products = $this->productRepository->searchProduct($input);
        return $this->viewSite('searchPage', [
            'products' => $products,
        ]);
    }

    public function cartPage() {

        return $this->viewSite('cart', [
            // 'products' => $products,
        ]);
    }
}
