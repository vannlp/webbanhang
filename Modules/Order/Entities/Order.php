<?php

namespace Modules\Order\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    // use SoftDeletes;

    protected $table = "orders";

    protected $fillable = [
        'id',
        'code',
        'cart_id',
        'total_price',
        'total_price_final',
        'user_id',
        'session',
        'created_at',
        'updated_at',
        'status_id',
        'address_id',
        'street',
        'city_id',
        'district_id',
        'ward_id',
    ];

}