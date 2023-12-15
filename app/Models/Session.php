<?php

namespace App\Models;

use App\Models\BaseModel as ModelsBaseModel;
use BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends ModelsBaseModel
{
    use SoftDeletes;


    protected $table = "sessions";
    protected $fillable = [
        'session_id',
        'ip',
        'user_agent',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
}
