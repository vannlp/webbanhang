<?php 
namespace App\Repositories;

use App\Models\Category;
use App\Models\Post;
use Prettus\Repository\Traits\CacheableRepository;

class CategoryRepository extends Repository {
    use CacheableRepository;

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }


    public function getAllPosts($id) {
        return Post::whereJsonContains('category_ids', $id)->get();
    }

    
}
