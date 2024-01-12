<?php

namespace Modules\Order\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends BaseModel
{
    // use SoftDeletes;

    protected $table = "order_details";

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'qty',
        'price',
        'final_price',
        'total_price',
        'total_price_final',
        'status_id',
        'created_at',
        'updated_at',
    ];

}