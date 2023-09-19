<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        //$tkt_status = $this->supportTktByStatus();
        
        // ticket eng daily activities
        $dailytks = Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                        ->where('created_at','>=', Carbon::now()->subDays(30))
                        ->where('supporter_uid', Auth::user()->id)
                        ->groupBy('date')
                        ->orderBy('created_at', 'ASC')
                        ->get();
        $daylabels = []; $daydata = [];
        foreach($dailytks as $dtkt){
            $daylabels[] = $dtkt->date;
            $daydata[] = $dtkt->total;
        }
          // end ticket eng daily activities

        // start eng ticket by status
        $engTktsByStatus = DB::table('tkt_statuses')
                ->where('supporter_uid', Auth::user()->id)
                ->join('tickets', 'tickets.tkt_status_id','=', 'tkt_statuses.id')
                ->select('tkt_statuses.id as status_id','tkt_statuses.name as status_name','tkt_statuses.color' , 'tickets.*', DB::raw('count(tickets.id) as total'))
                ->groupBy('tickets.tkt_status_id')
                ->get();

        $labels = []; $data = []; $color = [];
        foreach($engTktsByStatus as $status){
            $labels[] = $status->status_name;
            $data[] = $status->total;
            $color[] = $status->color;
        }
        // end ticket by status

        $new_tickets = Ticket::where([['user_id', Auth::user()->id],['tkt_status_id', 1]])
                        ->orderBy('created_at', 'DESC')
                        ->get();

        $engTktsDashboard = Permission::where([
                            ['user_id', '=', Auth::user()->id], ['module_id', '=', 7], ['update','=', 1]])
                           ->first();

        return view('backend.user.index', ['new_tickets'=>$new_tickets, 'engTktsDashboard'=>$engTktsDashboard, 'labels'=>$labels, 'data'=>$data,'color'=>$color,  'daylabels'=>$daylabels, 'daydata'=>$daydata]);


    }


    public function supportTktByStatus(){

        $query = "SELECT user_id,
                        username,
                        emp_id,
                        SUM(new_tkt) AS new_tkt,
                        SUM(open_tkt) AS open_tkt,
                        SUM(ongoing) AS ongoing,
                        SUM(completed_tkt) AS completed,
                        COUNT(tkt_id) AS total
                FROM
                (SELECT users.id AS user_id,
                        users.name AS username,
                        users.emp_id,
                        tickets.id AS tkt_id,
                        CASE
                            WHEN tickets.tkt_status_id = 1 THEN 1
                            ELSE 0
                        END new_tkt,
                        CASE
                            WHEN tickets.tkt_status_id = 2 THEN 1
                            ELSE 0
                        END open_tkt,
                        CASE
                            WHEN tickets.tkt_status_id = 3 THEN 1
                            ELSE 0
                        END ongoing,
                        CASE
                            WHEN tickets.tkt_status_id = 4 THEN 1
                            ELSE 0
                        END completed_tkt
                        
                    FROM tickets
                    LEFT JOIN users ON users.id = tickets.supporter_uid
                    WHERE tickets.supporter_uid IS NOT NULL) AS ss
                GROUP BY user_id,
                        username,
                        emp_id";

        $usertTktByStatus = DB::select($query); 

        return $usertTktByStatus;

    }


    public function userTktByStatus(){

        $query ="SELECT count(ID) as ID, SUM(status_C) completed ,SUM(status_O) ongoing, SUM(status_op) as open from (
            SELECT ID,
                CASE WHEN tickets.tkt_status_id = 4 THEN 1 else 0
                END as status_C,
                CASE WHEN tickets.tkt_status_id = 3 THEN 1 else 0
                END as status_O,
                CASE WHEN tickets.tkt_status_id = 2 THEN 1 else 0
                END as status_op
            FROM `tickets` WHERE tickets.user_id = ".Auth::user()->id.") as SS ";

        $tkt_status = DB::select($query);
        return $tkt_status ;

    }



}
