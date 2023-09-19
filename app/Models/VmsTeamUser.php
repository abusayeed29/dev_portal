<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmsTeamUser extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $connection = 'mysql2';
}
