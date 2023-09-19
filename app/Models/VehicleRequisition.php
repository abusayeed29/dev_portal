<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\VmsLookUp;

class VehicleRequisition extends Model
{
    use HasFactory;
    public $connection = 'mysql2';

    protected $fillable = [
        'generate_id',
        'pick_from',
        'drop_to',
        'requi_date',
        'pick_time',
        'drop_time',
        'stage',
        'status',
        'description',
        'company_id',
        'driver_id',
        'traveler_no',
        'user_id'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
   
    public function Driver(){
        return $this->hasOne(VmsDriver::class, 'id', 'driver_id');
    }

    public function Vehicle(){
        return $this->hasOne(VmsVehicle::class, 'id', 'vehicle_id');
    }

    public function vehicleHistories(){
        return $this->hasMany(VmsActivity::class, 'data_id', 'id')
                    ->leftJoin('navana_portal.users', 'vms_activities.user_id', '=', 'users.id')
                    ->select('vms_activities.added_on','users.name as user_name', 'vms_activities.ext_data')
                    ->orderBy('vms_activities.added_on', 'DESC');
    }

    public function Status()
    {
        //return $this->belongsTo(VmsLookUp::class,'status','id');
        return $this->belongsTo(VmsLookUp::class,'status','data_keys')->where('data_type','req.status');
    }

}
