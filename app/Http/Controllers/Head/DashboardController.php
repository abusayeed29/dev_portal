<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TktSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $serv_dept_id =  getServiceHeadDeptment(Auth::user()->id);

        $query = "SELECT user_id,
                        username,
                        emp_id,
                        u_status,
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
                        users.status as u_status,
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
                        emp_id
                ORDER BY u_status ASC";

        $usertTktByStatus = DB::select($query);


        $statuses = DB::table('tkt_statuses')
                ->join('tickets', 'tickets.tkt_status_id','=', 'tkt_statuses.id')
                ->select('tkt_statuses.id as status_id','tkt_statuses.name as status_name','tkt_statuses.color' , 'tickets.*', DB::raw('count(tickets.id) as total'))
                ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                ->groupBy('tickets.tkt_status_id')
                ->get();

        $labels = []; $data = []; $color = [];
        foreach($statuses as $status){
            $labels[] = $status->status_name;
            $data[] = $status->total;
            $color[] = $status->color;
        }

        $statuses_c = DB::table('tkt_statuses')
                ->join('tickets', 'tickets.tkt_status_id','=', 'tkt_statuses.id')
                ->select('tkt_statuses.name as status_name','tkt_statuses.color' , 'tickets.*', DB::raw('count(tickets.id) as total'))
                ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                ->whereDate('tickets.created_at', Carbon::today())
                ->groupBy('tickets.tkt_status_id')
                ->get();
        //dd($statuses_c);
        //dd(Carbon::now()->toDateString());
        $clabels = []; $cdata = []; $ccolor = [];
        foreach($statuses_c as $status){
            $clabels[] = $status->status_name;
            $cdata[] = $status->total;
            $ccolor[] = $status->color;
        }

        $monthTkts = Ticket::select(DB::raw("(COUNT(*)) as count"),DB::raw("MONTHNAME(created_at) as monthname"))
        ->whereYear('created_at', date('Y'))
        ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
        ->groupBy('monthname')
        ->orderBy('created_at', 'ASC')
        ->get();

        $mlabels = []; $mdata = [];
        foreach($monthTkts as $mtkt){
            $mlabels[] = $mtkt->monthname;
            $mdata[] = $mtkt->count;
        }

        $weeklyTkts = Ticket::select(DB::raw("(COUNT(*)) as count"), DB::raw('DATE_FORMAT(created_at, "%W") as dayname'))
                        //->whereBetween('created_at', [Carbon::now()->startOfWeek()->subDays(0), Carbon::now()->endOfWeek()])
                        ->whereDate('created_at','>=', Carbon::now()->subDays(6))
                        ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                        ->groupBy('dayname')
                        ->orderBy('created_at', 'ASC')
                        ->get();
        $wlabels = []; $wdata = [];
        foreach($weeklyTkts as $wtkt){
            $wlabels[] = $wtkt->dayname;
            $wdata[] = $wtkt->count;
        }

        $pending = Ticket::select('tickets.description','tickets.created_at')
                        ->whereDate('created_at','<', Carbon::now()->subDays(3))
                        ->whereIn('tkt_status_id',[2,3])
                        ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                        ->orderBy('created_at', 'DESC')
                        ->get()->count();

        $cmptkts = DB::table('companies')
                    ->join('tickets', 'tickets.company_id','=', 'companies.comp_id')
                    ->select('companies.comp_id as company_id','companies.slug as comp_name', DB::raw('count(tickets.id) as total'))
                    ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                    ->groupBy('tickets.company_id')
                    ->orderBy('companies.comp_id', 'asc')
                    ->get();

        $cmplabels = []; $cmpdata = [];
        foreach($cmptkts as $cmptkt){
            $cmplabels[] = $cmptkt->comp_name;
            $cmpdata[] = $cmptkt->total;
        }
        //today company wise ticket
        $tcmptkts = DB::table('companies')
                    ->join('tickets', 'tickets.company_id','=', 'companies.comp_id')
                    ->select('companies.comp_id as company_id','companies.slug as comp_name', DB::raw('count(tickets.id) as total'))
                    ->groupBy('tickets.company_id')
                    ->whereDate('tickets.created_at', Carbon::today())
                    ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                    ->orderBy('companies.comp_id', 'asc')
                    ->get();

        $tcmplabels = []; $tcmpdata = [];
        foreach($tcmptkts as $tcmptkt){
            $tcmplabels[] = $tcmptkt->comp_name;
            $tcmpdata[] = $tcmptkt->total;
        }

        // current date total tiket count

        $today_total = DB::table('tickets')
                    ->whereDate('tickets.created_at', Carbon::today())
                    ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                    ->count();

        return view('backend.head.index', ['usertTktByStatus'=> $usertTktByStatus, 'labels' => $labels, 'data' => $data, 'bg_color'=>$color, 'clabels' => $clabels, 'cdata' => $cdata, 'cbg_color'=>$ccolor,'mlabels' => $mlabels, 'mdata' => $mdata, 'wlabels' => $wlabels, 'wdata' => $wdata, 'statuses'=>$statuses, 'pending' => $pending,'cmplabels'=>$cmplabels, 'cmpdata'=>$cmpdata, 'tcmplabels'=>$tcmplabels, 'tcmpdata'=>$tcmpdata, 'today_total'=>$today_total]);
    }

    public function show(Request $request)
    {
        return view('backend.head.profile.index');
    }
}
