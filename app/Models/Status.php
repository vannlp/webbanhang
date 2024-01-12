<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends BaseModel
{
    protected $table = "status";

    protected $fillable = [
        'id',
        'code',
        'name',
        'table',
        'created_at',
        'updated_at',

    ];

    public function cartDetails() {
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }
   

}