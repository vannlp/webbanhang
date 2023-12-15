<?php

namespace App\Http\Controllers;

use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\TextUI\XmlConfiguration\Group;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller
{
    public function index(Request $request) {
        $roles = Role::query()
            ->where('code', '!=', 'SUPERADMIN')
            ->orderBy('created_at', 'desc')->paginate($request->limit ?? 10);
        return view('admin.role_permission.indexRole',[
            'roles' => $roles
        ]);
    }

    public function editRole(Request $request, $id) {
        $role = Role::find($id);
        $group_permissions = GroupPermission::with('permissions')->where('is_active', 1)->get();
        // handle permission
        foreach($group_permissions as $group_permission) {
            $permissions = $group_permission->permissions;
            $group_permission->permissions = $this->handlePermission($permissions, $id);
            // check group_permission is full permission
            $isChecked = true;
            foreach($group_permission->permissions as $permission) {
                if(!$permission->checkPermission) {
                    $isChecked = false;
                    break;
                }
            }

            if(count($group_permission->permissions) == 0) {
                $isChecked = false;
            }
            $group_permission->checked = $isChecked;
        }

        $permissions_not_groups = Permission::whereNull('group_permission_id')->where('is_active',1)->get();
        $permissions_not_groups = $this->handlePermission($permissions_not_groups, $id);
        // dd($group_permissions);

        return view('admin.role_permission.editRole',[
            'role' => $role,
            'group_permissions' => $group_permissions,
            'permissions_not_groups' => $permissions_not_groups
        ]);
    }

    public function handleEditRole(Request $request, $id) {
        $request->validate([
            'code' => ["required"],
            'name' => ["required"],
            // 'permission_ids' => ["array"]
        ]);

        try {
            DB::beginTransaction();
            $permission_id = $request->permission_ids ?? [];
            $role = Role::find($id);
            $role = $role->update([
                'code' => $request->code,
                'name' => $request->name,
            ]);
            
            if(count($permission_id) > 0) {
                foreach($request->permission_ids as $permission_id) {
                    $check = RolePermission::where('permission_id', $permission_id)->where('role_id', $id)->exists();
                    if(!$check){
                        RolePermission::create([
                            'role_id' => $id,
                            'permission_id' => $permission_id,
                            'is_active' => 1,
                            'created_by' => auth()->user()->id
                        ]);
                    }
                }
                // xóa những cái ko tích
                RolePermission::whereNotIn('permission_id', $request->permission_ids)
                    ->where('role_id', $id)->delete();
            }else{
                // xóa những cái ko tích
                RolePermission::where('role_id', $id)->delete();
            }

            DB::commit();

            return redirect()->back()->with('message', 'Cập nhập thành công');
        } catch (\Throwable $th) {
            DB::rollBack();
            if(env('APP_DEBUG')){
                dd($th);
            }
            Log::error($th->getMessage());
            return redirect()->back()->with('message', 'Có lỗi xảy ra');
        }
    }

    private function handlePermission($permissions, $role_id) {
        foreach($permissions as $permission) {
            $checkPermission = RolePermission::where('is_active', 1)->where('role_id', $role_id)
                ->where('permission_id', $permission->id)
                ->exists();

            if($checkPermission) {
                $permission->checkPermission = true;
            }else{
                $permission->checkPermission = false;
            }
        }

        return $permissions;
    }

    public function createRole(Request $request) {
        $request->validate([
            'code' => 'required|unique:roles,code',
            'name' => 'required',
        ]);

        try {
            $role = Role::create([
                'code' => $request->code,
                'name' => $request->name,
                'is_active' => $request->is_active ? 1 : 0,
            ]);
            return redirect()->back()->with('message', 'Thêm mới thành công');

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back(500)->with('message', 'Có lỗi xảy ra');
        }
    }

    public function indexPermission(Request $request) {
        if($request->ajax()){
            $query = Permission::query();
            $data_table = DataTables::of($query);
            return $data_table->make(true);
        }
        $group_permissions = GroupPermission::where('is_active', 1)->get();
        return view('admin.role_permission.indexPermission',[
            'group_permissions' => $group_permissions
        ]);
    }

    public function delete(Request $request) {
        $action_type = $request->action_type;
        if($action_type == 'many') {
            $records = $request->records;
            Permission::whereIn('id', $records)->delete();
        }

        if($request->ajax()){
            return $this->responseSuccess("Xóa thành công", 200);
        }

        return redirect()->back()->with('success', 'Xóa thành công');
    }

    public function createGroup(Request $request) {
        $request->validate([
            'code' => 'required|unique:group_permissions,code',
            'name' => 'required',
        ]);

        try {
            //code...
            $group_permission = GroupPermission::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description ?? null,
                'is_active' => 1,
                'created_by' => auth()->user()->id
            ]);
            return redirect()->back()->with('message', 'Thêm mới thành công');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            return redirect()->back(500)->with('message', 'Có lỗi xảy ra');
        }
    }

    public function createPermission(Request $request) {
        $request->validate([
            'code' => 'required|unique:permissions,code',
            'name' => 'required',
        ]);

        try {
            //code...
            $permission = Permission::create([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description ?? null,
                'is_active' => 1,
                'created_by' => auth()->user()->id,
                'group_permission_id' => $request->group_permission_id ?? null
            ]);
            return redirect()->back()->with('message', 'Thêm mới thành công');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            return redirect()->back(500)->with('message', 'Có lỗi xảy ra');
        }
    }
}
