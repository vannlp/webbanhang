<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index(Request $request) {
        $posts = Post::query();
        // dd($posts->get());
        if($request->ajax()) {
            $data_table = DataTables::of($posts);
            $data_table->addColumn('category_name', function($data) {
                $categories = $data->categories();
                $category_name_array = [];
                foreach($categories as $category) {
                    $category_name_array[] = $category->name;
                }
                $category_name_string = implode(', ',$category_name_array);
                return $category_name_string;
            });
            return $data_table->make(true);
        }

        return view('admin.post.indexPost', []);
    }

    public function create(Request $request) {
        // Tạo mã code ngẫu nhiên
        // POST_123
        $ramdomCode = "POST_".Str::random(10);
        while( Post::where('code', $ramdomCode)->exists() ) {
            $ramdomCode = "POST_".Str::random(10);
        }

        $categories = Category::where('is_active', 1)->where('type', "POST")->get(); 
        return view('admin.post.createPost',[
            'ramdomCode' => $ramdomCode,
            'categories' => $categories
        ]);
    }

    public function handleCreate(Request $request) {
        $input = $request->all();

        $request->validate([
            'code' => ['required', 'unique:posts'],
            'name' => ['required', 'min:8'],
            'slug' => ['required', 'min:8'],
            'avatar_link' => ['required'],
            'category_ids' => ['required', 'array'],
            'short_description' => ['required', 'min:8', 'max:255'],
            'description' => ['required', 'min:8']
        ],[
            'required' => "Không được bỏ trống trường :attribute",
            'min' => 'Trường :attribute phải có ít nhất :min ký tự',
            'max' => 'Trường :attribute tối đa :max ký tự',
            'unique' => 'Trường :attribute đã tồn tại'
        ], [
            'code' => "mã code",
            'name' => "Tên danh mục",
            'avatar_link' => 'Hình ảnh',
            'short_description' => 'mô tả ngắn',
            'description' => 'mô tả'
        ]);
        try {
            //code...
            DB::beginTransaction();
            $avatar_link = $this->parseUrl($input['avatar_link']);
            $input['category_ids'] = array_map(function($item) {
                return (int) $item;
            }, $input['category_ids']);
            $post = Post::create([
                'code' => $input['code'],
                'slug' =>Str::slug($input['slug']),
                'name' => $input['name'],
                'avatar_link' => $avatar_link,
                'short_description' => $input['short_description'],
                'description' => $input['description'],
                'category_ids' => $input['category_ids'],
                'is_active' => isset($input['is_active']) ? 1: 0 
            ]); 

            DB::commit();
            return redirect()->back()->with('message', 'Thêm mới thành công');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            if(env('APP_DEBUG')){
                dd($th);
            }
            Log::error($th->getMessage());
            return redirect()->back()->with('message', 'Có lỗi xảy ra');
        }
    }

    private function parseUrl($link) {
        $parsedUrl = parse_url($link);
        if (isset($parsedUrl['path'])) {
            $pathWithoutDomain = $parsedUrl['path'];
            return $pathWithoutDomain; // Kết quả: /apps/ckfinder/userfiles/files/audi%20r8.jpg
        } else {
            return false;
        }
    }

    public function edit(Request $request, $id) {
        $post = Post::find($id);
        $categories = Category::where('is_active', 1)->where('type', "POST")->get(); 
        return view('admin.post.editPost', [
            'categories' => $categories,
            'post' => $post
        ]);
    }
}
