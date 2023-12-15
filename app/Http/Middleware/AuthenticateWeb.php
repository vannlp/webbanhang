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

class AuthenticateWeb 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next, $permission_code = null)
    {
        $enviroment = 'web';
        $isAdminRoute = false;

        if ($request->is('admin/*') || $request->is('admin')) {
            $isAdminRoute = true;
        }
        if (!auth($enviroment)->check()) {
            if($request->ajax()) {
                return response()->json([
                    'message' => 'Lỗi xác thực'
                ], 300);
            }

            if($isAdminRoute) {
                return redirect('/admin/login');
            }else{
                return redirect('/login');
            }
        }

        if($isAdminRoute) {
            $role_code = auth($enviroment)->user()->role->code;
            if($role_code == 'CUSTOMER') {
                return abort(300);
            }
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
            if($isAdminRoute) {
                if($request->ajax()) {
                    return response()->json([
                        'message' => 'Lỗi xác thực'
                    ], 300);
                }
                return abort(304);
            }
        }

        return $next($request);
    }
}
