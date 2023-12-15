<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request) {
        $roles = Role::all();

        if ($request->ajax()) {
            $user = User::query();
            // orderby
            $user->orderBy('updated_at', 'desc');
            $data_table = DataTables::of($user);
            $data_table->addColumn('role_name', function($data) {
                return $data->role->name;
            });

            $data_table->addColumn('updated_by_name', function($data) {
                
                return $data->updatedBy->name ?? "";
            });
            return $data_table->make(true);
        }
        return view('admin.users.index', [
            'roles' => $roles
        ]);
    }

    public function create(Request $request) {
        $input = $request->all();
        $request->validate([
            'username' => ['required', 'unique:users,username'],
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'role_id' => ['required'],
            'password' => ['required'],
            're_password' => ['required', 'same:password']
        ],
        [
            'username.required' => "Tên đăng nhập không được bỏ trống",
            'username.unique' => "Tên đăng nhập đã tồn tại",
            'name.required' => "Họ tên không được bỏ trống",
            'password.required' => "Vui lòng nhập mật khẩu",
            're_password.same' => "Nhập lại mật khẩu không đúng",
        ]);
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($input['role_id']);
            $user = User::create([
                'username' => $input['username'],
                'name' => $input['name'],
                'email' => $input['email'],
                'role_id' => $input['role_id'],
                'role_code' => $role->code,
                'password' => Hash::make($input['password']),
                'is_active' => $input['is_active'] ?? true,
            ]);

            DB::commit();

            return redirect()->back()->with('message', "Thêm mới thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back(500)->with('message', "Đã có lỗi phía server");
        }
    }

    public function getOne($id) {
        $user = User::find($id);
        $roles = Role::all();
        return response()->json([
            'user' => $user,
            'roles' =>$roles
        ], 200);
    }

    public function edit($id, Request $request) {
        $input = $request->all();
        $request->validate([
            'username' => ['required'],
            'name' => ['required'],
            'email' => ['required'],
            // 'role_id' => ['required'],
            // 'is_active' => ['required']
        ],
        [
            'username.required' => "Tên đăng nhập không được bỏ trống",
            'username.unique' => "Tên đăng nhập đã tồn tại",
            // 'name.required' => "Họ tên không được bỏ trống",
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id); 
            if(isset($request->isUpdateProfile)){
                $input['is_active'] = isset($input['is_active']) ? 1 : 0;
            }
            $input['updated_by'] = auth()->user()->id;
            $user->update($input);
            DB::commit();

            if(isset($request->isUpdateProfile)) {
                return $user;
            }
            return redirect()->back()->with('message', "Cập nhập thành công");

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back(500)->with('message', "Đã có lỗi phía server");
        }
    }

    public function profile() {
        $user = auth()->user();
        $user = $user->load('role');

        return $this->viewSite('profile', [
            'title' => "Trang profile của {$user->name}",
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request) {
        $id = auth()->user()->id;
        try {
            $request->role_id = auth()->user()->role_id;
            DB::beginTransaction();
            $request->isUpdateProfile = true;
            $request->is_active = 1;
            $user = $this->edit($id, $request);
            if($request->file('file')) {
                $file = (new FileController())->uploadImage([
                    'file' => $request->file('file'),
                    'alt' => $request->alt ?? null
                ]);
                $user->avatar_id = $file->id;
                $user->save();
            }
            DB::commit();
            return redirect()->back()->with('message', "Cập nhập thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with('message', "Đã có lỗi phía server");
        }
    }
}
