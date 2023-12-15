<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends BaseModel
{
    protected $table = "carts";

    protected $fillable = [
        'id',
        'user_id',
        'price',
        'handled_price',
        'type',
        'session',
        'created_at',
        'updated_at',
    ];

    public function cartDetail() {
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }
   

}