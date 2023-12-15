<?php 
namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Category;
use App\Models\Post;
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


    
}
