<?php

namespace Modules\Site\Http\Controllers;

use App\Http\Controllers\Controller ;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CartController extends Controller
{

    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductRepository $productRepository,
        protected CartRepository $cartRepository,
    )
    {
    }


    public function addToCartForClient(Request $request) {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:1', 'max:10']
        ]);

        if(!$request->ajax()) {
            return $this->responseError('Chức năng chỉ hỗ trợ ajax hoặc api', 400);
        }

        if(auth()->check()) {
            $user_id = auth()->user()->id;
            $session = null;
        }else{
            $user_id = null;
            $session = Str::random(10);
        }

        $input = $request->all();

        $inputCart[] = $input;

        $checkHandleCart = $this->addToCart($user_id, $session, $inputCart);

        if($checkHandleCart) {
            return $this->responseSuccess('Thêm vào giỏ hàng thành công', 200);
        } else {
            return $this->responseError('Thêm vào giỏ hàng thất bại', 400);
        }

    }

    public function addToCart($user_id = null, $session = null, $input = []) {
        
        try {
            DB::beginTransaction();
            // get cart
            if($user_id) {
                $cart = Cart::query()->where('user_id', $user_id)->first();
            }else{
                $cart = Cart::query()->where('session', $session)->first();
            }

            // check exist
            if(!$cart) { // new cart

                $inputNewCart = [
                    'user_id' => $user_id ?? null,
                    'price' => 0,
                    'handled_price' => 0,
                    'type' => '',
                    'session' => $session ?? null,
                    'type' => $user_id ? 'guest' : 'visiting_guest'
                ];

                Event::dispatch('cart.create', [&$inputNewCart, $cart]);

                $cart = Cart::create($inputNewCart);
            }

            // add product to cart
            $cartDetails = CartDetail::whereIn('product_id', array_column($input, 'product_id'))->get();


            // handle data cart detail
            foreach ($input as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];

                $cartDetail = $cartDetails->where('product_id' ,$product_id)->first();

                $product = $this->productRepository->find($product_id);
                if(!$cartDetail) { // new cart detail

                    $inputNewCartDetail = [
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'price' => $product->price * $quantity,
                        'handled_price' => $product->handlePrice() * $quantity,
                        'quantity' => $quantity
                    ];

                    Event::dispatch('cartDetail.create', [&$inputNewCartDetail, $cart, $product]);

                    $cartDetailNew = CartDetail::create($inputNewCartDetail);
                } else {
                    $quan = $cartDetail->quantity + $quantity;

                    $inputCartDetail = [
                        'quantity' => $quan,
                        'price' => $product->price * $quan,
                        'handled_price' => $product->handlePrice() * $quan,
                    ];

                    $cartDetailNew = $cartDetail->update($inputCartDetail);
                }
            }

            $this->handlePriceCart($cart);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;

            DB::rollBack();
            Log::error('addToCart', [$th->getFile(), $th->getMessage(), $th->getLine()]);
            return false;
        }

    }


    public function handlePriceCart($cart) {
        // xử lý trường price
        // lấy tất cả sản phẩm 
        $cart = $cart->load('cartDetail');
        $price = $cart->cartDetail()->sum('price');

        // xử lý price handle
        $priceHandled = $cart->cartDetail()->sum('handled_price');
        Event::dispatch('cart.handled_price', [&$priceHandled, $cart]);

        $cart->price = $price;
        $cart->handled_price = $priceHandled;

        $cart->save();

        return $cart;
    }
    
}
