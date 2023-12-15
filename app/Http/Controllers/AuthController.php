<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login () {
        // dd(123);
        $a = collect();
        if(auth()->check()) {
            return redirect('/admin');
        }
        return view('admin.login', [
            'title' => "Đăng nhập",
            'is_admin' => 1
        ]);
    }

    public function handleLogin(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => "required"
        ], 
        [
            'email.required' => "Vui lòng nhập email",
            'password.required' =>  'Vui lòng nhập password'
        ]);

        // check is_active
        $checkUser = User::where('email', $request->email)->where('is_active', 1)->exists();
        if(!$checkUser) {
            return redirect()->back()->withErrors(['error_message' => 'Có lỗi xảy ra! Vui lòng kiểm tra tài khoản mật khẩu']);
        }

        $credentials = $request->only(['email', 'password']);
        $checkAuth = auth()->attempt($credentials, true);
        if(!$checkAuth) {
            return redirect()->back()->withErrors(['error_message' => 'Có lỗi xảy ra! Vui lòng kiểm tra tài khoản mật khẩu']);
        }
        $user = auth()->user();
        // Tạo token cho user
        $token = JWTAuth::fromUser($user);
        // Cookie::make('jwt_token', $token, (30*24*7));
        session(['jwt_token' => $token]);

        if($request->is_admin) {
            return redirect('/admin');
        }
        
        return redirect('/');
    }

    public function logout() {
        auth()->logout();
        session()->forget('jwt_token');
        return redirect('/');
    }
}
