<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    
    public $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'emp_id',
        'company_id',
        'phone',
        'work_place'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function company(){
        return $this->hasOne(Company::class, 'comp_id', 'company_id');
    }
    public function department(){
        return $this->hasOne(Department::class, 'id','department_id');
    }
    public function designation(){
        return $this->hasOne(Designation::class, 'id', 'designation_id', );
    }
    public function Section(){
        return $this->hasOne(Section::class, 'id','section_id');
    }
}
