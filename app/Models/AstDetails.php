<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AstDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'component_id',
    ];


}
