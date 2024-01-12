<?php

namespace App\Http\Controllers;

use App\Http\Helper\AuthHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

class ModuleController extends Controller
{
    public function index() {
        $modules = Module::all();
        return view('admin.module.index', [
            // 'user' => $user
            'modules' => $modules
        ]);
    }

    public function update(Request $request) {
        $status = $request->status;
        if($status) {

        }

        return redirect()->back()->with('message', 'Chỉnh sửa thành công');
    }
}
