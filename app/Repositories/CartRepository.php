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

class CartRepository extends Repository {
    use CacheableRepository;

    public function __construct(
        protected ProductRepository $productRepository
    )
    {
        
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return Cart::class;
    }

    
    
}
