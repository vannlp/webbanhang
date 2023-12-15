<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use SoftDeletes;

    protected $table = "posts";

    protected $fillable = [
        'name',
        'code',
        'description',
        'slug',
        'short_description',
        'avatar_link',
        'category_ids',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    protected $casts = [
        'category_ids' => 'array'
    ];

    public function categories() {
        
        return Category::whereIn('id', $this->category_ids)->get();
    }

}