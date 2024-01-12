<?php 
namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Traits\CacheableRepository;

class CartDetailRepository extends Repository {
    use CacheableRepository;

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return CartDetail::class;
    }


    public function updateCartDetail($cartDetailInput) {
        try {
            // get CartDetail;

            foreach($cartDetailInput as $cartDetailInputItem) {
                $cartDetail = CartDetail::find($cartDetailInputItem['id']);
                $product = Product::find($cartDetail->product_id);
                $cartDetail->quantity = $cartDetailInputItem['quantity'];
                $cartDetail->price = $product->price * $cartDetailInputItem['quantity'];
                $cartDetail->handled_price = $product->handlePrice() * $cartDetailInputItem['quantity'];
                $cartDetail->save();
            }

            return true;
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('updateCartDetail', [$th->getFile(), $th->getMessage(), $th->getLine()]);
            return false;
        }
    }

    
}
