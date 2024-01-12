<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends BaseModel
{
    protected $table = "cart_details";

    protected $fillable = [
        'id',
        'cart_id',
        'product_id',
        'price',
        'handled_price',
        'quantity',
        'created_at',
        'updated_at',
    ];


    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}