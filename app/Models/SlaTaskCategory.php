<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SlaTaskCategory extends Model
{
    use HasFactory;


    public function slaSubCategory()
    {
        return $this->hasMany(SlaTaskCategory::class, 'parent_id', 'id')
                ->leftJoin('users', 'sla_task_categories.user_id', '=', 'users.id')
                ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
                ->select('users.name as username','sla_task_categories.*', 'designations.name as desg_name');
    }
}
