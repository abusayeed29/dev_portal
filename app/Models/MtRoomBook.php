<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtRoomBook extends Model
{
    use HasFactory;


    protected $casts = [
        'foods' => 'array'
    ];

}
