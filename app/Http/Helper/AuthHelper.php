<?php
namespace App\Http\Helper;

use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;

class AuthHelper extends Auth {
    public static function checkPermission(string|array $permission_code) {
        if(!(auth()->check())) {
            return false;
        }
        $user = auth()->user();
        $role_id = $user->role_id;
        $role_code = $user->role->code;
        // dd($role_code);
        if($role_code == 'SUPERADMIN') {
            return true;
        }
        if(is_array($permission_code)) {
            // kiểm tra permisson
            $permission_code_array = self::getPermissionCode($role_id);
            $checkPermission = false;
            foreach($permission_code as $item) {
                if(in_array($item, $permission_code_array)) {
                    $checkPermission = true;
                    break;
                }
            }

            if($checkPermission) {
                return true;
            }

            return false;
        }else{
            $permission_code_array = self::getPermissionCode($role_id);
            if(in_array($permission_code, $permission_code_array)) {
                return true;
            }
            return false;
        }

    }
    /**
     * Lấy ra tất cả permisison code của user dựa vào role_id
     * @param int $role_id
     * @return array $permission_code_array
     */
    private static function getPermissionCode(int $role_id):array {
        $role_permission = RolePermission::with('permission')->where('role_id', $role_id)
                ->where('is_active', 1)->get();
            
        $permission_code_array = [];
        foreach($role_permission as $item) {
            $permission_code_array[] = $item->permission->code;
        }

        return $permission_code_array;
    }
}