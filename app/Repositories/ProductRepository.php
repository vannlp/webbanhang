<?php 
namespace App\Repositories;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Prettus\Repository\Traits\CacheableRepository;

class ProductRepository extends Repository {
    use CacheableRepository;

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }


    public function extractProductListWithHtml($html, $classProductItem, $input = [])
    {
        $Product =  core()->getElementByClassname($html, 'item __cate_44');
        dd($Product );
        

        // foreach ($productItems as $item) {
        //     // Extract product information based on the structure of the HTML
        //     $productName = $item->getElementsByTagName('h3')->item(0)->textContent;
        //     $productPrice = $item->getElementsByClassName('price')->item(0)->textContent;

        //     // Create an array for each product and add it to the product list
        //     $productList[] = [
        //         'name' => $productName,
        //         'price' => $productPrice,
        //     ];
        // }

        // return $productList;
    }

    /**
     * Get list products by category id.
     *
     * @param array|string  $categoryIds
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function getListProductByCategory(array|string|int $categoryIds, array $columns = null, $width = []): Collection {
        $query = Product::query();

        $query->with($width);

        if($columns) {
            $query->select($columns);
        }

        // lấy tất cả các danh mục cấp dưới
        $query->whereIn('id', function($q) use ($categoryIds) {
            $q->from('product_category')->select('product_id')->whereIn('category_id', $categoryIds);
        });

        // $query->whereJsonContains('category_ids',$categoryIds);

        return $query->get();
    }

    /**
     * Get list products by search.
     *
     * @param array|string  $categoryIds
     * @param array $columns
     * 
     */
    public function searchProduct(array $input = [], array $columns = null, array $width = null){
        $query = $this->model->query();

        if($columns) {
            $query->select($columns);
        }

        if($width) {
            $query->with($width);
        }

        if(!empty($input['name'])) {
            $query->where('name', 'like', "%{$input['name']}%");
        }

        if(!empty($input['code'])) {
            $query->where('code', $input['code']);
        }

        if(!empty($input['limit'])){
            return $query->paginate($input['limit']);
        }
        else{
            return $query->get();
        }
    }


    
    
}
