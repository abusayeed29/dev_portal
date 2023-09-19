<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmsActivity extends Model
{
    use HasFactory;
    
    public $connection = 'mysql2';

    protected $fillable = [
        'subject', 'activity', 'data_id', 'user_id', 'added_on','ext_data'
    ];

    public $timestamps = false;
}
