<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Category;
use App\Models\Post;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function __construct(
        protected CategoryRepository $categoryRepository
        )
    {
        
    }


    public function addToCartForClient(Request $request) {
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        try {
            DB::beginTransaction();
            cart()->addProductToCart($product_id, [
                'quantity' => $quantity
            ]);
            DB::commit();
            return $this->responseSuccess('Thêm giỏ hàng thành công');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error($th);
            if(env('APP_DEBUG')){
                dd($th);
            }
            return $this->responseError('Thêm giỏ hàng thất bại', 500);
        }

    }

    public function updateCart(Request $request) {
        $cartDetails = $request->cartDetail;

        try {
            DB::beginTransaction();
            foreach($cartDetails as $cartDetail) {
                $data = [
                    'quantity' => $cartDetail['quantity']
                ];
                cart()->updateCartDetail($cartDetail['id'], $data);
            }

            DB::commit();
            return $this->responseSuccess('Cập nhập giỏ hàng thành công');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error('updateCart', [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'message' => $th->getMessage()
            ]);
            if(env('APP_DEBUG')){
                dd($th);
            }
            return $this->responseError('Cập nhập giỏ hàng thất bại', 500);
        }
    }

    public function removeCartDetail(Request $request, $idCartDetail) {
        try {
            DB::beginTransaction();
            cart()->deleteCartDetail($idCartDetail);
            DB::commit();
            return redirect()->back()->with('message', 'Xóa giỏ hàng thành công');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error('removeCartDetail', [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'message' => $th->getMessage()
            ]);
            return redirect()->back()->with('message', 'Xóa giỏ hàng thất bại');
        }
    }

    public function getCartApi(Request $request) {
        try {
            //code...
            $cart = cart()->getCart(['loadCartDetail' => true, 'load' => ['cartDetails.product', 'cartDetails.product.avatar']]);

            return new CartResource($cart);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error('removeCartDetail', [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'message' => $th->getMessage()
            ]);
            return $this->responseError('Có lỗi xảy ra', 500);
        }
    }
 
}
