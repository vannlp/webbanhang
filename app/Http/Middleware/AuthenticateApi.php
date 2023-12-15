<?php

namespace App\Http\Middleware;

use App\Models\BlockPermission;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateApi
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next, $permission_code = null)
    {
        // $guards = empty($guards) ? [null] : $guards;
        $enviroment = 'web';
        if ($request->is('api/*')) {
            $enviroment = 'api';
        }
        if (!auth($enviroment)->check()) {
            return response()->json([
                'message' => 'Vui lòng đăng nhập'
            ], 400);
        }

        if(!$permission_code){
            return $next($request);
        }

        $role_id =  auth($enviroment)->user()->role_id ?? null;

        if(auth($enviroment)->user()->role->code == 'SUPERADMIN') {
            return $next($request);
        }

        $permission_id = Permission::where('code', $permission_code)->value('id');
        $role_permission = RolePermission::where('role_id', $role_id)
            ->where('permission_id', $permission_id)
            ->where('is_active', 1)
            ->first();

        if(!$role_permission) {
            return response()->json([
                'message' => 'Không có quyền truy cập'
            ], 300);
        }

        // $check_bock_permission = BlockPermission::where('user_id', auth()->user()->id)
        //     ->where('permission_id', $permission_id)
        //     ->where('is_active', 1)
        //     ->exists();

        // if($check_bock_permission) {
        //     return response()->json([
        //         'message' => 'Bạn đã bị chặn quyền'
        //     ], 300);
        // }

        return $next($request);
    }
}
