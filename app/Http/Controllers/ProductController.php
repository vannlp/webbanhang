<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Repositories\ProductRepository;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct(protected ProductRepository $productRepository)
    {
        
    }

    public function index(Request $request) {
        if($request->ajax()){
            $query = Product::query();
            $query = $this->handleQueryDataTabel($query, $request);
            $data_table = DataTables::of($query);
            $data_table = $this->handleColumnDataTable($data_table, $request);
            return $data_table->make(true);
        }

        return view('admin.product.index', []);
    }

    protected function handleQueryDataTabel(Builder $productQuery, Request $request) {
        $productQuery->with(['categories', 'avatar']);

        $productQuery->orderBy('created_at', 'desc');
        
        return $productQuery;
    }

    protected function handleColumnDataTable($dataTable, Request $request) {
        $dataTable->addColumn('category_name', function($data) {
            $categories = $data->categories;
            $category_name_array = [];
            foreach($categories as $category) {
                $category_name_array[] = $category->name;
            }
            $category_name_string = implode(', ',$category_name_array);
            return $category_name_string;
        });

        $dataTable->addColumn('avatar_link', function($data) {
            $avatar = $data->avatar;
            $avatar_link = asset($avatar->url);
            return $avatar_link;
        });

        $dataTable->addColumn('category_ids', function ($data) {
            $categories = $data->categories;
            $category_ids = $categories->pluck('category_id')->toArray();
            return $category_ids;
        });

        return $dataTable;
    }

    public function create(Request $request) {
        $categories = Category::where('is_active', 1)->where('type', "PRODUCT")->get(); 

        return view('admin.product.create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'code' => ['required', 'unique:products,code'],
            'slug' => ['required', 'unique:products,slug'],
            'name' => ['required'],
            'category_ids' => ['required', 'array'],
            'is_active' => ['nullable'],
            'avatar_link' => ['required', 'image'],
            'photo_collection' => ['nullable', 'array'],
            'price' => ['required', 'numeric'],
            'short_description' => ['required', 'max:500'],
            'description' => ['required'],
            'price_down' => ['nullable', 'lt:price']
        ], [], [
            'code' => "Mã sản phẩm",
            'slug' => 'Đường dẫn', 
            'name' => "Tên sản phẩm",
            'category_ids' => "Danh mục",
            'avatar_link' => "Hình ảnh đại diện",
            'price' => "Giá sản phẩm",
            'short_description' => "Mô tả ngắn",
            'description' => "Mô tả",
            'price_down' => "Giá giảm"
        ]);

        try {
            DB::beginTransaction();
            $code = str_replace([' ', "\t", "\n", "\r", "\0", "\x0B"], '', $request->code);
            // xử lý file
            $fileController = new FileController();
            $avatar_link = $fileController->uploadImage(['file' => $request->file('avatar_link')]);
            $photo_collection = [];

            foreach($request->file('photo_collection') as $file) {
                $input = [
                    'file' => $file,
                    'alt' => null
                ];
                $fileUpload = $fileController->uploadImage($input);
                // dd($fileUpload);
                $photo_collection[] = $fileUpload->id;
            }

            $category_ids = array_map(function($item) {
                return (int) $item;
            }, $request->category_ids);

            $product = Product::create([
                'code' =>  $code,
                'slug' => Str::slug($request->slug),
                'name' => $request->name,
                'avatar_id' => $avatar_link->id,
                'photo_collection' => $photo_collection,
                'price' => $request->price,
                'short_description' => $request->short_description,
                'description' => $request->description ?? null,
                'is_active' => $request->is_active ? 1: 0,
                'price_down' => $request->price_down ?? null
            ]);

            $productCategories = [];
            foreach($category_ids as $category_id) {
                $productCategories[] = [
                    'product_id' => $product->id,
                    'category_id' => $category_id
                ];
            }
            ProductCategory::insert($productCategories);


            DB::commit();
            return redirect()->back()->with('message', 'Thêm mới thành công');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            if(env('APP_DEBUG')){
                dd($th);
            }
            abort(500);
        }

    }

    public function edit(Request $request, $id) {
        $product = Product::with('categories')->find($id);
        $categories = Category::where('is_active', 1)->where('type', "PRODUCT")->get(); 
        // $category_ids = ProductCategory::where('product_id', $id)->pluck('category_id')->toArray();

        $link = File::whereIn('id', $product->photo_collection)->pluck('url');
        $link_asset = [];
        foreach($link as $item) {
            $link_asset[] = asset($item);
        }

        // custom product
        $product->category_ids = $product->categories()->pluck('category_id')->toArray();


        $product->photo_collection_link = $link_asset;
        return view('admin.product.edit', [
            'categories' => $categories,
            'product' => $product
        ]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'code' => ['required', Rule::unique('products')->ignore($id)],
            'slug' => ['required', Rule::unique('products')->ignore($id)],
            'name' => ['required'],
            'category_ids' => ['nullable', 'array'],
            'is_active' => ['nullable'],
            'avatar_link' => ['nullable'],
            'photo_collection' => ['nullable', 'array'],
            'price' => ['required', 'numeric'],
            'short_description' => ['required'],
            'description' => ['required'],
            'price_down' => ['nullable', 'lt:price']
        ], [], [
            'code' => "Mã sản phẩm",
            'slug' => 'Đường dẫn', 
            'name' => "Tên sản phẩm",
            'category_ids' => "Danh mục",
            'avatar_link' => "Hình ảnh đại diện",
            'price' => "Giá sản phẩm",
            'short_description' => "Mô tả ngắn",
            'description' => "Mô tả",
            'price_down' => "Giá giảm"
        ]);

        try {
            DB::beginTransaction();
            $category_ids = array_map(function($item) {
                return (int) $item;
            }, $request->category_ids);
            $product = Product::find($id);
            // xử lý file
            $fileController = new FileController();
            if($request->avatar_link){
                $avatar_link = $fileController->uploadImage(['file' => $request->file('avatar_link')]);
                // xoa file
                $file_ids[] = $product->avatar->id;
                $fileController->deleteFile($file_ids);
            }
            
            if($request->file('photo_collection')){
                $photo_collection = [];

                foreach($request->file('photo_collection') as $file) {
                    $input = [
                        'file' => $file,
                        'alt' => null
                    ];
                    $fileUpload = $fileController->uploadImage($input);
                    $photo_collection[] = $fileUpload->id;
                }

                $file_ids = $product->photo_collection;
                $fileController->deleteFile($file_ids);
            }
            

            $product->update([
                // 'code' =>  $code,
                'slug' => Str::slug($request->slug),
                'name' => $request->name,
                'avatar_id' =>  isset($avatar_link) ? $avatar_link->id : $product->avatar_id,
                'photo_collection' => $photo_collection ?? $product->photo_collection,
                'price' => $request->price,
                'short_description' => $request->short_description,
                'description' => $request->description ?? null,
                'is_active' => $request->is_active ? 1: 0,
                'price_down' => $request->price_down ?? null
            ]);

            $productCategories = [];
            foreach($category_ids as $category_id) {
                $productCategories[] = [
                    'product_id' => $product->id,
                    'category_id' => $category_id
                ];
            }

            ProductCategory::upsert($productCategories, ['category_id', 'product_id', 'updated_at']);

            DB::commit();
            return redirect()->back()->with('message', 'Cập nhập thành công');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            if(env('APP_DEBUG')){
                dd($th);
            }
            abort(500);
        }
    }

    public function delete() {
        
    }

    public function cloneData(Request $request) {
        $link = $request->link;
        $client = new Client();
        try {
            //code...
            $response =  $client->request('GET', $link);
            $html = $response->getBody()->getContents();
            // dd($html);

            $productList = $this->productRepository->extractProductListWithHtml($html, 'item __cate_44');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    
}
