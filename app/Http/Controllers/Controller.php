<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function viewSite($patch, $data = []) {
        $sitePatch = 'site::pages';
        $patch = "{$sitePatch}.{$patch}";
        return response()->view($patch, $data);
    }

    public function responseSuccess($message, $status = 200) {
        return response()->json([
            'message' => $message
        ], $status);
    }

    public function responseError($message, $status = 400) {
        return response()->json([
            'message' => $message
        ], $status);
    }

    public function responseSuccessWithData($message = '', $data, $status = 200) {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function responseErrorWithData($message = '', $data, $status = 400) {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
