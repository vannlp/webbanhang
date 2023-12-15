<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends BaseModel
{
    use SoftDeletes;

    protected $table = "product_category";

    protected $fillable = [
        'id',
        'category_id',
        'product_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    
}