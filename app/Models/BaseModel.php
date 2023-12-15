<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model {
    use HasFactory;

    public function delete()
    {
        if(Schema::hasColumn($this->table,  "deleted")){
            $this->deleted = 1;
        }

        if(Schema::hasColumn($this->table, "deleted_by")){
            $this->deleted_by = auth()->user()->id;
        }
        $this->save();
        parent::delete();
    }
}