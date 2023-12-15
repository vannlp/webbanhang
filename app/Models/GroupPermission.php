<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupPermission extends BaseModel
{
    use SoftDeletes;

    protected $table = "group_permissions";

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function permissions() {
        return $this->hasMany(Permission::class, 'group_permission_id', 'id');
    }
}