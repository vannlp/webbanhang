<?php
namespace App\Helpers;

use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartDetailRepository;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Str;


class CartHelper {
    protected $cart;


    public function __construct(
        protected CartRepository $cartRepository,
        protected ProductRepository $productRepository,
        protected CartDetailRepository $cartDetailRepository
    )
    {
        $this->initCart();
    }


    /**
     * Returns cart.
     *
     * @return Cart|null
     */
    public function initCart()
    {
        $cart = $this->getCart();

        if(!$cart) {
            $this->create();
        }
    }

    /**
     * Returns cart.
     *
     * @return Cart|null
     */
    public function getCart($configs = []): ?Cart
    {
        if ($this->cart) {
            if(isset($configs['load'])) {
                $this->cart = $this->cart->load($configs['load']);
            }

            if($this->cart && isset($configs['loadCartDetail'])) {
                $this->loadCartDetail($configs['loadCartDetailAgain'] ?? false);
            }
            return $this->cart;
        }

        if (auth()->guard()->check()) {
            $this->cart = $this->cartRepository->findOneWhere([
                'user_id' => auth()->guard()->user()->id
            ]);
        } elseif (session()->has('cart')) {
            $this->cart = $this->cartRepository->findOneWhere([
                'session' => session()->get('cartSession')
            ]);
        }

        if(isset($configs['load'])) {
            $this->cart = $this->cart->load($configs['load']);
        }

        if($this->cart && isset($configs['loadCartDetail'])) {
            $this->loadCartDetail($configs['loadCartDetailAgain'] ?? false);
        }

        return $this->cart;
    }

    /**
     * Set cart model to the variable for reuse
     *
     * @param Cart
     * @return  void
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * Reset cart
     *
     * @return  void
     */
    public function resetCart()
    {
        $this->cart = null;
    }


    /**
     * New cart
     * 
     */
    public function create($data = []) {
        $cartData = [
            'price' => 0,
            'handle_price' => 0
        ];

        $cartData = array_merge($cartData, $data);

        /**
         * Fill in the customer data, as far as possible.
         */
        if (auth()->guard()->check()) {
            $customer = auth()->guard()->user();

            $cartData = array_merge($cartData, [
                'user_id'             => $customer->id,
                'type'                => 'guest',
            ]);
        } else {
            $cartData['type'] = 'visiting_guest';
            if(session()->has('cartSession')){
                $cartData['session'] = session()->get('cartSession');
            } else {
                $cartData['session'] = session()->put('cartSession', Str::random(20));
            }
        }

        // create cart
        $cart = $this->cartRepository->create($cartData);

        $this->getCart(['loadCartDetail' => true]);

        return $cart;
    }

    /**
     * put product in cart
     * 
     */
    public function addProductToCart($productId, $data) {
        $cart = $this->getCart(['loadCartDetail' => true]);
        
        if(!$cart) {
            $cart = $this->create();
        }

        $product = $this->productRepository->find($productId);


        $cartDetails = $cart->cartDetails;

        $cartDetail = $cartDetails->where('product_id', $product->id)->where('cart_id', $cart->id)->first();

        if(!$cartDetail) {
            $cartDetailInput = [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'price' => $product->price * $data['quantity'] ?? 0,
                'handled_price' => $product->handlePrice() * $data['quantity'] ?? 0,
            ];

            $this->cartDetailRepository->create($cartDetailInput);

            return $this->getCart(['loadCartDetail' => true, 'loadCartDetailAgain' => true]);
        }

        $cartDetail->price = $product->price * $data['quantity'] ?? 0;
        $cartDetail->handled_price = $product->handlePrice() * $data['quantity'] ?? 0;

        $cartDetail->save();
        $this->resetCart();
        $this->handleTotalsCart();
        return $this->getCart(['loadCartDetail' => true, 'loadCartDetailAgain' => true]);
    }

    /**
     *  get cart detail
     * 
     */
    public function loadCartDetail($loadAgain = false) {
        if(
            $this->cart &&
            isset($this->cart->cartDetails) &&
            !$loadAgain
        ){
            return $this->cart;
        }

        $this->cart = $this->cart->load('cartDetails');

        return $this->cart;
    }


    /**
     * handle totals cart
     * 
     */
    public function handleTotalsCart() {
        $cart = $this->getCart(['loadCartDetail' => true, 'loadCartDetailAgain' => true]);
        $cartDetails = $cart->cartDetails ?? [];

        $price = 0;
        $handled_price = 0;
        // get and sum price
        if(count($cartDetails) > 0) {
            $price = $cartDetails->sum('price');
            $handled_price = $cartDetails->sum('handled_price');
        }

        $cart->price = $price;
        $cart->handled_price = $handled_price;
        $cart->save();
        return $cart;
    }

    /**
     * delete cart item
     * 
     */
    public function deleteCartDetail($idCartDetail) {
        $this->getCart(['loadCartDetail' => true]);

        $this->cart->cartDetails()->find($idCartDetail)->delete();

        return $this->cart;
    }

    /**
     * update cart detail
     * 
     */
    public function updateCartDetail($idCartDetail, $data) {
        $this->getCart(['loadCartDetail' => true]);

        $cartDetail = $this->cart->cartDetails()->find($idCartDetail);

        // get product by cart detail
        $product = $this->productRepository->find($cartDetail->product_id);

        $cartDetail->quantity = $data['quantity'] ?? $cartDetail->quantity;
        $cartDetail->price = $product->price * ($data['quantity'] ?? $cartDetail->quantity);
        $cartDetail->handled_price = $product->handlePrice() * ($data['quantity'] ?? $cartDetail->quantity);

        $cartDetail->save();
        // $this->resetCart();
        $this->handleTotalsCart();
        return $this->cart;
    }

}