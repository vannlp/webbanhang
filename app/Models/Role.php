<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;


    protected $table = "roles";
    protected $fillable = [
        'code',
        'name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public function role_permission() {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }
}
