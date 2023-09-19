<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRoster extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'event_start', 
        'event_end',
        'user_id',
        'inserted_uid'
    ];  


    public function headUser()
    {
        return $this->belongsTo(User::class, 'head_uid', 'id')->select('users.name as headName');
    }
    


}
