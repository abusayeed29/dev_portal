<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmsVehicle extends Model
{
    use HasFactory;
    
    public $connection = 'mysql2';

    public function assignTeam(){
        return $this->belongsTo(VmsLookUp::class,'assign_team','data_keys')->where('data_type','req.team');
    }

    public function allBookingStatus(){
        return $this->hasMany(VehicleRequisition::class,'vehicle_id','id');
    }

    public function bookingStatus(){
        return $this->hasMany(VehicleRequisition::class,'vehicle_id','id')
                    ->whereDate('pick_time','>=', Carbon::today());
    }

    public function Driver(){
        return $this->hasOne(VmsDriver::class, 'id', 'driver_id', );
    }


}
