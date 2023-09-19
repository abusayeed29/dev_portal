<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmsLookUp extends Model
{
    use HasFactory;
    
    public $connection = 'mysql2';

    protected $fillable = [
        'data_type', 'data_keys', 'data_values'
    ];

    public $timestamps = false;
}
