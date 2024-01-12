<?php 
namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Support\Str;


class CartRepository extends Repository {
    use CacheableRepository;


    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return Cart::class;
    }


    public function getCart($input = []) {
        $configs = [
            'time_life_cart_session' => 60 * 24 * 7
        ];

        if(auth()->check()) {
            $user_id = auth()->user()->id;
            $session = null;
            $cart = $this->model->query()->where('user_id', $user_id)->first();
        }else{
            $user_id = null;
            $session = request()->cookie('cart_session');
            $cart = $this->model->query()->where('session', $session)->first();
        }


        if(isset($input['is_cartDetail'])) {
            $cart = $cart->load('cartDetails');
        }

        return $cart;
    }
    
    
}
