<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use SoftDeletes;
    protected $table = "categories";

    protected $fillable = [
        'name',
        'code',
        'description',
        'slug',
        // 'short_description',
        'parent_id',
        'type', //PRODUCT || POST
        'image_id',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];


    public function file() {
        return $this->belongsTo(File::class, 'image_id', 'id');
    }

    // Mối quan hệ giữa danh mục con và danh mục cha
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    // Phương thức lấy tất cả các danh mục con và các danh mục con cấp dưới
    public function allSubCategories()
    {
        return $this->subCategories()->with('allSubCategories');
    }
}