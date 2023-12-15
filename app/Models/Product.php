<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Event;

class Product extends BaseModel
{
    use SoftDeletes;

    protected $table = "products";

    protected $fillable = [
        'id',
        'code',
        'name',
        'slug',
        'short_description',
        'description',
        'avatar_id',
        'photo_collection',
        'price',
        'price_down',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active'
    ];

    protected $casts = [
        'category_ids' => 'array',
        'photo_collection' => 'array'
    ];

    public function categories() {
        
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function avatar() {
        return $this->belongsTo(File::class, 'avatar_id', 'id');
    }

    public function photo_collections() {
        return File::whereIn('id', $this->photo_collection)->get();
    }


    /**
     * Handle price product when after get data
     * 
     */
    public function handlePrice() {
        $product = $this;

        if(!$product) {
            return null;
        }

        $price = $product->price;

        if(!empty($product->price_down) && $product->price_down != 0) {
            $price = $product->price_down;
        }

        Event::dispatch('product.handlePrice', [&$price, $product]);


        return $price;
    }


}