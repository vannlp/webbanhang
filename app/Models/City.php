<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends BaseModel
{
    use SoftDeletes;
    protected $table = "cities";

    protected $fillable = [
        'id',
        'code',
        'type',
        'name',
        'grab_code',
        'id_city_vnp',
        'name_city_vnp',
        'full_name',
        'description',
        'region_code',
        'region_name',
        'is_active',
        'deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'code_ghn',
    ];

    public function cartDetails() {
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }
   

}