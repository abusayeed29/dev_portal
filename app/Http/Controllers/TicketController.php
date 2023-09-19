<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketFile;
use App\Models\TicketType;
use App\Models\TktDepartment;
use App\Models\TktSupportActivity;
use App\Models\TktTeamUser;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketEmailNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketTypeByDepartment(Request $request)
    {
        $tkt_types = TicketType::where('tkt_dprtmnt_id', $request->department_id)
            ->whereNull('parent_id')
            ->get();

        $output = '';
        foreach ($tkt_types  as $pr_type) :
            $output .=  '<option disabled selected></option>';
            $output .= '<optgroup label="' . $pr_type->name . '">';
            $sub_tkt_types = TicketType::where('parent_id', $pr_type->id)->get();
            foreach ($sub_tkt_types as $sub_type) :
                $output .= '<option value="' . $sub_type->id . '">' . $sub_type->name . '</option>';
            endforeach;
            $output .= '</<optgroup>';
        endforeach;

        return response()->json(['data' => $output]);
    }

    /**
     * get a newly created Ticket with Depertment .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function departmentTicket(Request $request)
    {

        if ($request->ajax()) {

            if ($request->status == 1) {
                if ($request->department) :
                    $depart_ment_id = TktDepartment::where('slug', $request->department)->pluck('id');
                    $query = Ticket::where([['user_id', Auth::user()->id], ['tkt_dprtmnt', $depart_ment_id], ['tkt_status_id',     $request->status]])
                        ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', 'tkt_statuses.id')
                        ->leftJoin('ticket_types', 'tickets.tkt_type_id', 'ticket_types.id')
                        ->select('tickets.*', 'tkt_statuses.name as s_name', 'tkt_statuses.color as s_color', 'ticket_types.name as tkt_type')
                        ->orderBy('tickets.created_at', 'DESC')
                        ->get();
                else :
                    $query = Ticket::where([['user_id', Auth::user()->id], ['tkt_status_id', $request->status]])
                        ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', 'tkt_statuses.id')
                        ->leftJoin('ticket_types', 'tickets.tkt_type_id', 'ticket_types.id')
                        ->select('tickets.*', 'tkt_statuses.name as s_name', 'tkt_statuses.color as s_color', 'ticket_types.name as tkt_type')
                        ->orderBy('tickets.created_at', 'DESC')->get();
                endif;
            } else {
                if ($request->department) :
                    $depart_ment_id = TktDepartment::where('slug', $request->department)->pluck('id');
                    $query = Ticket::where([['user_id', Auth::user()->id], ['tkt_dprtmnt', $depart_ment_id]])
                        ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', 'tkt_statuses.id')
                        ->leftJoin('ticket_types', 'tickets.tkt_type_id', 'ticket_types.id')
                        ->select('tickets.*', 'tkt_statuses.name as s_name', 'tkt_statuses.color as s_color', 'ticket_types.name as tkt_type')
                        ->orderBy('tickets.created_at', 'DESC')->get();
                else :
                    $query = Ticket::where([['user_id', Auth::user()->id]])
                        ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', 'tkt_statuses.id')
                        ->leftJoin('ticket_types', 'tickets.tkt_type_id', 'ticket_types.id')
                        ->select('tickets.*', 'tkt_statuses.name as s_name', 'tkt_statuses.color as s_color', 'ticket_types.name as tkt_type')
                        ->orderBy('tickets.created_at', 'DESC')->get();
                endif;
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('ticket_id', function ($data) {
                    $tkt_id = date('md', strtotime($data->created_at)) . $data->id;
                    return $tkt_id;
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge" style="color:#fff; background-color:' . $data->s_color . '">' . $data->s_name . '</span>';
                })
                ->addColumn('created_at', function ($data) {
                    return '<p>' . $data->created_at . '</p>';
                })
                ->addColumn('updated_at', function ($data) {
                    return '<p>' . $data->updated_at . '</p>';
                })
                ->addColumn('action', function ($data) {

                    $button = '<a href="' . route(Auth::user()->role->slug . '.ticket.show', $data->id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>';
                    return $button;
                })
                ->rawColumns(['action', 'status', 'created_at', 'updated_at', 'ticket_id'])
                ->toJson();
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tkt_department' => 'required',
            'type' => 'required',
            'location' => 'required|max:50',
            'description' => 'required|max:1000|unique:tickets',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $ticket  = new Ticket();
        $ticket->user_id        = Auth::id();
        $ticket->tkt_dprtmnt    = $request->tkt_department;
        $ticket->tkt_type_id    = $request->type;
        $ticket->location       = $request->location;
        $ticket->company_id     = Auth::user()->company_id;
        $ticket->description    = $request->description;
        $ticket->save();

        if ($request->file != 'undefined' && !empty($ticket->id)) {
            $file = $request->file('file');
            $slug = Str::slug(Auth::user()->name);
            $currentDate = Carbon::now()->toDateString();
            $new_name = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $new_name);
            $upload_path = 'uploads/tickets';
            $orginal_name = $file->getClientOriginalName();

            $tkt_file = new TicketFile;
            $tkt_file->purpose     = 'tkt';
            $tkt_file->name        = $new_name;
            $tkt_file->orgi_name   = $orginal_name;
            $tkt_file->path        = $upload_path;
            $tkt_file->type        = $file->getClientOriginalExtension();
            $tkt_file->size        = $_FILES['file']['size'];
            $tkt_file->ticket_id   = $ticket->id;
            $tkt_file->save();
        }

        $tkt_info = Ticket::where('tickets.id', $ticket->id)
            ->join('ticket_types', 'tickets.tkt_type_id', '=', 'ticket_types.id')
            ->join('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
            ->select('tickets.*', 'ticket_types.name as type_name', 'tkt_statuses.name as tkt_status', 'tkt_statuses.color as status_color')
            ->first();

        $output = '<tr>
                    <td>' . $tkt_info->id . '</td>
                    <td>' . $tkt_info->description . '</td>
                    <td>' . $tkt_info->type_name . '</td>
                    <td>' . $tkt_info->created_at . '</td>
                    <td>' . $tkt_info->updated_at . '</td>
                    <td><span class="badge" style="color:#fff; background-color:' . $tkt_info->status_color . '">' . $tkt_info->tkt_status . '</td>
                    <td> 
                        <a href="' . route(Auth::user()->role->slug . '.ticket.show', $ticket->id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>
                    </td>
                </tr>';

        // start email notification
        $users = User::leftJoin('tkt_supervisors', 'users.id', '=', 'tkt_supervisors.user_id')
            ->where('tkt_supervisors.tkt_dprtmnt_id', $tkt_info->tkt_dprtmnt)
            ->where('tkt_supervisors.module_id', 4)
            ->select('users.id', 'users.emp_id', 'users.name', 'users.department_id', 'users.company_id', 'users.reportto_id', 'users.email')
            ->get();

        $tkt_message = 'Ticket#' . $tkt_info->id . ' Created. <br> <strong>Issue Details: </strong>' . $ticket->description . '<br><strong>Raise From: </strong>' . getUserName($ticket->user_id);

        if (count($users) > 0) :
            foreach ($users as $user) {
                $this->sendNotification($user->id, $ticket->id, $tkt_message);
            }
        endif;

        if (Notification::send($users, new NewTicketNotification(Ticket::latest('id')->first()))) {
            return back();
        }
        // end email notification

        return response()->json(['success' => 'Your ticket has been submitted successfully', 'ticket' => $output]);
    }


    public function sendNotification($rep_id = null, $ticket_id = null, $description)
    {

        $user = User::find($rep_id); //31 97
        //$user->notify(new TicketEmailNotification($invoice));
        Notification::send($user, new TicketEmailNotification($description, Auth::user()->name, $ticket_id, $rep_id));
    }


    // *****************play start***************** 

    public function tktTeamUser()
    {

        $get_parent = TktTeamUser::where('user_id', 912)->first();

        $memebers = $this->teamMemberView($get_parent->id);

        dd($memebers);
    }

    // public function teamMemberView($parent_id){
    //     $users = TktTeamUser::where('parent_id', $parent_id)->get();
    //     $userId =[];
    //     if(count($users) > 0):
    //         foreach($users as $key => $data){
    //             echo $data->user_id. '<br>';
    //             $userId[] = $this->teamMemberView($data->id);

    //         }
    //     endif;
    //     return $userId; 


    // }



    public function teamMemberView($parent_id)
    {

        $users = TktTeamUser::where('parent_id', $parent_id)->get();
        $userId = [];
        if (count($users) > 0) :
            foreach ($users as $key => $data) {
                echo $data->user_id . '<br>';
                $userId[] = $this->teamSubMemberView($data->id, 0);
            }
        endif;

        return $userId;
    }

    function teamSubMemberView($parent_id, $level)
    {
        $users = TktTeamUser::where('parent_id', $parent_id)->get();
        $userId = [];
        $level++;
        foreach ($users as $key => $data) {
            echo str_repeat("-", ($level * 4)) . $data->user_id . '<br>';
            $userId[] = $this->teamSubMemberView($data->id, $level);
        }
        return $userId;
    }


    public function companyTickets(Request $request)
    {

        //$serv_dept_id =  getServiceDeptment(Auth::user()->id);

        // $tkt_users = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
        //     ->select('users.name as user_name', DB::raw('count(tickets.id) as total'))
        //     ->groupBy('tickets.user_id')
        //     ->where('tickets.tkt_dprtmnt',  1)
        //     ->orderBy('total', 'DESC')
        //     ->get();

        $cmptkts = DB::table('companies')
            ->join('tickets', 'tickets.company_id', '=', 'companies.comp_id')
            ->select('companies.comp_id as company_id', 'companies.name as comp_name', DB::raw('count(tickets.id) as total'))
            ->where('tickets.tkt_dprtmnt',  1)
            ->groupBy('tickets.company_id')
            ->orderBy('total', 'DESC')
            ->get();

        if ($request->ajax()) {

            $comp_id = $request->comp_id;

            if ($comp_id) :
                $query = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
                    ->join('companies', 'tickets.company_id', '=', 'companies.comp_id')
                    ->select('tickets.*', 'users.name as user_name', 'companies.name as comp_name')
                    ->where('tickets.company_id',  $comp_id)
                    ->where('tickets.tkt_dprtmnt',  1)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            else :
                $query = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
                    ->join('companies', 'tickets.company_id', '=', 'companies.comp_id')
                    ->select('tickets.*', 'users.name as user_name', 'companies.name as comp_name')
                    ->where('tickets.tkt_dprtmnt',  1)
                    ->orderBy('created_at', 'DESC')
                    ->limit(20);
            endif;

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('message', function ($data) {
                    return '<p>' . $data->description . '</p>';
                })
                ->addColumn('user_name', function ($data) {
                    return $data->user_name ;
                })
                ->addColumn('tkt_type', function ($data) {
                    return !empty($data->ticketType->name)?$data->ticketType->name:'' ;
                })
                ->addColumn('department', function ($data) {
                    return !empty($data->user->department->name)? $data->user->department->name:'';
                })
                ->addColumn('sup_officer', function ($data) {
                    return !empty($data->userAssign->name) ? $data->userAssign->name:'';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at;
                })
                ->addColumn('assigned_at', function ($data) {
                    return $data->assign_time;
                })
                ->addColumn('started_at', function ($data) {
                    $data_status = TktSupportActivity::where('ticket_id', $data->id)->where('activity', 3)->first();
                    if($data_status){
                       return $data_status->added_on;
                    }
                })
                ->addColumn('completed_at', function ($data) {
                    $data_status = TktSupportActivity::where('ticket_id', $data->id)->where('activity', 4)->first();
                    if($data_status){
                       return $data_status->added_on;
                    }
                })
                ->rawColumns(['message', 'created_at','assigned_at'])
                ->toJson();
        }

        return view('backend.common.ticket.tkt-company-report', compact('cmptkts'));
    }

    // public function companyTickets($id)
    // {

    //     $tkt_company = Ticket::join('users', 'users.id', '=', 'tickets.user_id')
    //         ->select('tickets.*', 'users.name as user_name')
    //         ->where('tickets.company_id',  $id)
    //         ->where('tickets.tkt_dprtmnt',  1)
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     dd();

    //     return view('backend.user.ticket.tkt-user-data', compact('tkt_company'));
    // }



    // public function teamMemberView($parent_id){
    //     $users = TktTeamUser::where('parent_id', $parent_id)->get();
    //     $userId = [];
    //     for ($i = 0; $i < count($users); $i++)  {
    //         echo $users[$i]['user_id'] ."<br />";
    //         $this->teamMemberView($users[$i]['id']);
    //         $userId[] = $users[$i]['user_id'];
    //     }

    //     return $userId;

    // }


    // test
    /*public function tktTeamUser(){

        $get_parent = TktTeamUser::where('user_id', 648)->first();

        $users = TktTeamUser::where('parent_id', $get_parent->id)->get();

        $user_id = '';
        foreach ( $users  as $user) {
            $user_id .= $user->user_id;
            if(count($user->teamMembers)) {
                $user_id .= $this->teamMemberView($user);
            }
        }

        dd($user_id);
        
    }

    public function teamMemberView($users){
        $user_id = '';
        foreach($users->teamMembers as $data){
            if(count($data->teamMembers)){
                $user_id .= $data->user_id;
                $user_id .= $this->teamMemberView($data);
            }else{
                $user_id .= $data->user_id;
            }
        }       
        

    } 

    
    /*
    public function tktTeamUser(){

        $members = TktTeamUser::where('user_id', 5)->get();
        
        $this->teamMemberView($members, 1);

    }
    
    public function teamMemberView($members, $parent, $level=0, $prelevel = -1){
        
        foreach($members as $id => $data){
            
            if($parent == $data->parent_id){

                if($level > $prelevel){
                    echo '<ol>';
                }
                if($level == $prelevel){
                    echo '<li>';
                }
                
                echo '<li>'.$data->user_id;

                if($level > $prelevel){
                    $prelevel = $level;
                }
                $level++;
    
                $this->teamMemberView($data->teamMembers, $id, $level, $prelevel);
                $level --;
         
            }
            
        }

        if($level == $prelevel){
            echo "</li></ol>";
        }

    } */
}
