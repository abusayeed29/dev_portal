<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
