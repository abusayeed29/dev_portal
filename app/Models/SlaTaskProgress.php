<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaTaskProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'task_cat_id',
        'task_id',
        'started_at',
        'company_id',
        'completed_at',
        'status_percent'
    ];


}
