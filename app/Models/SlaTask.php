<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaTask extends Model
{
    use HasFactory;


    public function slaCategory()
    {
        return $this->belongsTo(SlaTaskCategory::class, 'cat_id', 'id');
    }


    public function slaCategories()
    {
        return $this->hasMany(SlaTaskCategory::class,'parent_id','cat_id');
    }



}
