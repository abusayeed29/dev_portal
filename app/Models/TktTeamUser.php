<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TktTeamUser extends Model
{
    use HasFactory;

    public function teamMembers()
    {
        return $this->hasMany(TktTeamUser::class,'parent_id','id');
    }
}
