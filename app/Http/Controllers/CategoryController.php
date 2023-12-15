<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct(protected CategoryRepository $categoryRepository)
    {
        
    }

    public function indexCategoryPost(Request $request) {
        $categories_parent = Category::where('type', 'POST')
            ->get();

        $categories = Category::query();
        $categories->where('type', 'POST');
        if($request->ajax()) {
            $data_table = DataTables::of($categories);
            $data_table->addColumn('parent_name', function($data) {
                return $data->parentCategory->name ?? "Không";
            });
            return $data_table->make(true);

        }


        return view('admin.post.indexCategory', [
            'categories_parent' => $categories_parent,
            'type' => 'post'
        ]);
    }

    public function createCategoryPost(Request $request) {
        $this->validateCreate($request);

        try {
            DB::beginTransaction();
            $slug = Str::slug($request->slug);
            $category = Category::create([
                'code' => $request->code,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description ?? null,
                'parent_id' => $request->parent_id ?? null,
                'is_active' => 1,
                'type' => "POST",
            ]);
            DB::commit();
            return redirect()->back()->with('message', "Thêm mới thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('message', "Đã có lỗi phía server");
        }
    }

    public function getCategoryPost(Request $request, $id) {
        $category = Category::find($id);
        $categories_parent = Category::where('type', 'POST')
        ->where('id', '!=', $id)
        ->get();

        if($request->ajax()){
            return response()->json([
                'category' => $category,
                'categories_parent' => $categories_parent
            ], 200);
        }
    }

    public function updateCategoryPost(Request $request, $id) {
        if(!($request->ajax())){
            $request->is_active = $request->is_active == 'on' ? 1 : 0;
            // dd($request->is_active);
        }
        $request->validate([
            'code' => 'required',
            'name' => ['required', Rule::unique('categories')->ignore($id)],
            'slug' => [Rule::unique('categories')->ignore($id), 'required'],
            // 'is_active' => ['in:0,1'],
            'description' => ['nullable']
        ],[
            'required' => ':attribute không được bỏ trống',
            'unique' => ':attribute giá trị trùng',
        ],[
            'code' => 'Mã code',
            'name' => "Tên danh mục",
        ]);

        try {
            $input = [
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'is_active' => $request->is_active,
                'description' => $request->description ?? null
            ];

            $category = $this->categoryRepository->find($id);
            $category->update($input);

            if($request->ajax()){
                return $this->responseSuccess("Cập nhập thành công", 200);
            }

            return redirect()->back()->with('message', 'Cập nhập thành công');
        } catch (\Throwable $th) {
            //throw $th;
            if(env('APP_DEBUG')) {
                dd($th);
            }

            return abort(500);
        }
    }

    public function deleteCategoryPost(Request $request, $id) {
        try {
            $category = $this->categoryRepository->find($id);

            // check post
            $checkPost = $this->categoryRepository->getAllPosts($id);
            if(count($checkPost) > 0){
                return redirect()->back()->with('message', 'Vui lòng xóa hết bài post');
            }
            // check category
            // Lấy ra tất cả danh mục con và các danh mục con cấp dưới của danh mục gốc
            $subCategories = $category->allSubCategories()->get();
            if(count($subCategories) > 0) {
                return redirect()->back()->with('message', 'Vui lòng xóa hết bài danh mục con');
            } 

            $category->delete();


            return redirect()->back()->with('message', 'Xóa dữ liệu thành công');
        } catch (\Throwable $th) {
            //throw $th;
            if(env('APP_DEBUG')) {
                dd($th);
            }

            return abort(500);
        }
    }

    public function indexCategoryProduct(Request $request) {
        $categories_parent = Category::where('type', 'PRODUCT')
        ->get();

        $categories = Category::query();
        $categories->where('type', 'PRODUCT');
        if($request->ajax()) {
            $data_table = DataTables::of($categories->get());
            $data_table->addColumn('parent_name', function($data) {
                return $data->parentCategory->name ?? "Không";
            });
            return $data_table->make(true);

        }


        return view('admin.post.indexCategory', [
            'categories_parent' => $categories_parent,
            'type' => 'product'
        ]);
    }

    protected function validateCreate(Request $request) {
        $request->validate([
            'code' => 'required|unique:categories,deleted_at',
            'name' => 'required',
            'slug' => 'required',
            'description' => 'nullable',
            'parent_id' => 'nullable',
        ],[
            'required' => ':attribute không được bỏ trống',
            'unique' => ':attribute bị trùng, vui lòng chọn lại',
        ],[
            'code' => 'Mã code',
            'name' => 'Tên danh mục',
            'description' => "Mô tả",
            'parent_id' => "Danh mục cha",
        ]);
    }

    public function createCategoryProduct(Request $request) {
        $this->validateCreate($request);

        try {
            DB::beginTransaction();
            $slug = Str::slug($request->slug);
            $category = Category::create([
                'code' => $request->code,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description ?? null,
                'parent_id' => $request->parent_id ?? null,
                'is_active' => 1,
                'type' => "PRODUCT",
            ]);
            DB::commit();
            return redirect()->back()->with('message', "Thêm mới thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('message', "Đã có lỗi phía server");
        }
    }

    public function deleteCategoryProduct() {

    }

    public function getCategoryProduct (Request $request, $id) {
        $category = Category::find($id);
        $categories_parent = Category::where('type', 'PRODUCT')
        ->where('id', '!=', $id)
        ->get();

        if($request->ajax()){
            return response()->json([
                'category' => $category,
                'categories_parent' => $categories_parent
            ], 200);
        }
    }
}
