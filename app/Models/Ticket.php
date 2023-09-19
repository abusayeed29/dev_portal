<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function ticketType()
    {
        /*return $this->belongsTo('App\Category')->withTimestamps();*/
       return $this->belongsTo(TicketType::class, 'tkt_type_id','id');
    }

    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function tktSupportActivity()
    {
        return $this->hasMany(TktSupportActivity::class,'ticket_id')
                ->leftJoin('tkt_statuses', 'tkt_support_activities.activity', '=', 'tkt_statuses.id')
                ->select('tkt_support_activities.added_on','tkt_support_activities.activity','tkt_statuses.name as status_name', 'tkt_statuses.color as color')
                ->orderBy('tkt_support_activities.added_on', 'ASC');
    }

    public function files()
    {
        return $this->hasMany('App\Models\TicketFile','ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'comp_id', 'company_id');
    }
    public function userAssign()
    {
        return $this->belongsTo(User::class, 'supporter_uid', 'id');
    }
    public function tktStatus()
    {
        return $this->belongsTo(TktStatus::class);
    }

    public function lookUp()
    {
        return $this->belongsTo(LookUp::class,'location','id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class,'designaiton_id','id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }
}
