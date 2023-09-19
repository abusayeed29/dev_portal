<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaDepartment extends Model
{
    use HasFactory;

    public function slaTaskParentCategories()
    {
        return $this->hasMany(SlaTaskCategory::class, 'department_id', 'id')
                ->whereNull('parent_id');
                
    }

}
