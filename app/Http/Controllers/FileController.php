<?php

namespace App\Http\Controllers;

use App\Http\Helper\AuthHelper;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request) {
        if(isset($request->month)) {
            $month = $request->month;
        }else{
            $month = Carbon::now()->format('m-Y');
        }
        $monthsWithFile = File::select(DB::raw('DATE_FORMAT(created_at, "%m-%Y") as month'))
            ->groupBy('month')
            ->get();
        $files = File::whereRaw('DATE_FORMAT(created_at, "%m-%Y") = ?', [$month])->get();

        // handle url 
        foreach ($files as $file) {
            $file->url = url($file->url);
        }

        if($request->ajax()) {
            return response()->json([
                'files' => $files,
                'monthsWithFile' => $monthsWithFile
            ]);
        }
        return view('admin.media.index', [
            'files' => $files,
            'monthsWithFile' => $monthsWithFile
        ]);
    }

    public function uploadFileCk(Request $request) {
        $file = $request->file('upload');
        $input = [
            'alt' => null,
            'file' => $file,
        ];
        $image = $this->uploadImage($input);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $url = url($image->url);
        $msg = 'Image successfully uploaded';
        $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
         
        // Render HTML output
        @header('Content-type: text/html; charset=utf-8');
        echo $re;
    }

    public function uploadImage(array $input) {
        $fileInput = $input['file'];
        try {
            $path = $fileInput->store('images');
            $file = File::create([
                'url' => $path,
                'alt' => $input['alt'] ?? null,
                'type' => 'IMAGE',
            ]);

            return $file;
        } catch (\Throwable $th) {
            throw new $th;
        }
    }

    public function uploadImages(Request $request) {
        $request->validate([
            'files' => ['array', 'required'],
            'files.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        try {
            // $files = $request->files;
            DB::beginTransaction();
            foreach($request->file('files') as $file) {
                $input = [
                    'file' => $file,
                    'alt' => null
                ];

                $this->uploadImage($input);
            }
            
            DB::commit();
            return $this->responseSuccess("Tải lên thành công", 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            throw new \Exception("Lỗi upload file", 500);
        }
    }

    public function getOne(Request $request, $id) {
        $file = File::find($id);

        if($request->ajax()){
            return response()->json([
                'file' => $file
            ],200);
        }
    }

    public function handleUpdate(Request $request, $id) {
        $file = File::find($id);
        try {
            $alt = $request->alt ?? null;
            $file->alt = $alt;
            $file->save();

            if($request->ajax()) {
                return $this->responseSuccess('Cập nhập thành công', 200);
            }

            return redirect()->back()->with('message', 'Cập nhập thành công');

        } catch (\Throwable $th) {
            //throw $th;
            return $this->responseError("Có lỗi xảy ra", 500);
        }
    }

    /**
     * @param array $input (nhập input vào theo dạng id vd [1, 8, 6])
     */
    public function deleteFile($input) {
        $files = File::whereIn('id',$input)->get();
        try {
            DB::beginTransaction();
            foreach($files as $file) {
                Storage::delete($file->url);
                $file->delete();
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return false;
        }
        
    }
}
