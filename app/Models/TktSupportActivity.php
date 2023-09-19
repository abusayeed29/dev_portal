<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TktSupportActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'activity', 'ticket_id', 'supporter_id','added_on', 'ext_data'
    ];

    public $timestamps = false;
    
}
