<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public function permissionCheck(){
        return $this->belongsTo(Permission::class, 'department_id', 'id');
    }

    public function modulePages(){
        return $this->hasMany(Module::class, 'parent_id', 'id');
    }


}
