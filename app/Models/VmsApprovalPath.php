<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmsApprovalPath extends Model
{
    use HasFactory;
    public $connection = 'mysql2';

    public function usersInTeam()
    {
        return $this->hasMany(VmsTeamUser::class,'team_id', 'team_id')
        ->leftJoin('navana_portal.users', 'vms_team_users.user_id', '=', 'users.id')
        ->select('navana_portal.users.name as user_name', 'vms_team_users.user_id', 'navana_portal.users.emp_id')
        ->orderBy('navana_portal.users.name', 'ASC');
    }
}
