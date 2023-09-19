<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketFile;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use PHPUnit\TextUI\XmlConfiguration\Group;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::where([['user_id', Auth::user()->id], ['company_id', Auth::user()->company_id]])->get();

        return view('backend.head.ticket.index', compact('tickets'));
    }

    public function support()
    {
        $query = "SELECT count(ID) as ID, SUM(status_C) completed ,SUM(status_O) ongoing, SUM(status_op) as open from (
            SELECT ID,
                CASE WHEN tickets.tkt_status_id='completed' THEN 1 else 0
                END as status_C,
                CASE WHEN tickets.tkt_status_id='ongoing' THEN 1 else 0
                END as status_O,
                CASE WHEN tickets.tkt_status_id='open' THEN 1 else 0
                END as status_op
            FROM `tickets`) as SS ";

        $tkt_status = DB::select($query);

        $tickets = Ticket::all();

        return view('backend.head.ticket.support.index', compact('tickets', 'tkt_status'));
    }

    public function supportShow($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $comments =  TicketComment::with('user')
            ->where('ticket_comments.ticket_id', $id)
            ->get();
        return view('backend.head.ticket.show', compact('ticket', 'comments'));
    }

    public function assignUserModal(Request $request)
    {

        $users =  User::where('department_id', Auth::user()->department_id)
            ->whereNotIn('id', [Auth::user()->id])->get();

        $option = '';
        foreach ($users as $user) {
            $option .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }

        $output = '<div class="form-group">
                    <label for="recipient-name" class="col-form-label mb-0">Assign To</label>
                    <select class="form-control mb-3" id="assignUser">' . $option . '</select> 
                    <input type="hidden" id="ticket_id" value="' . $request->tkt_id . '">
                    <input type="hidden" id="user_id" value="' . $request->userId . '">
                </div>';

        return response()->json(['output' => $output]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                        <a href="' . route('head.ticket.show', $ticket->id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $comments =  TicketComment::with('user')
            ->where('ticket_comments.ticket_id', $id)
            ->get();
        return view('backend.head.ticket.show', compact('ticket', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Ticket::where('id', $request->tkt_id)
            ->where('user_id', $request->user_id)
            ->update(['supporter_uid' => $request->staff_id, 'assigner_uid' => Auth::user()->id, 'assign_time' => Carbon::now()]);

        return response()->json(['success' => 'Your ticket has been submitted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display all ticket for Supervisor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function headManageTicket()
    {
        // $query ="SELECT count(ID) as ID, SUM(status_C) completed ,SUM(status_O) ongoing, SUM(status_op) as open from (
        //     SELECT ID,
        //         CASE WHEN tickets.tkt_status_id = 4 THEN 1 else 0
        //         END as status_C,
        //         CASE WHEN tickets.tkt_status_id = 3 THEN 1 else 0
        //         END as status_O,
        //         CASE WHEN tickets.tkt_status_id = 2 THEN 1 else 0
        //         END as status_op
        //     FROM `tickets`) as SS ";

        // $tkt_status = DB::select($query);

        $serv_dept_id =  getServiceHeadDeptment(Auth::user()->id);

        $statuses = DB::table('tkt_statuses')
            ->join('tickets', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
            ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', DB::raw('count(tickets.id) as total'))
            ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
            ->groupBy('tickets.tkt_status_id')
            ->get();
            
        $pending = Ticket::select('tickets.description', 'tickets.created_at')
            ->whereDate('created_at', '<', Carbon::now()->subDays(3))
            ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
            ->whereIn('tickets.tkt_status_id', [2, 3])
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        return view('backend.head.ticket.support.manage', compact('statuses', 'pending'));
    }


    public function headManageTicketData(Request $request)
    {
        $serv_dept_id =  getServiceHeadDeptment(Auth::user()->id);

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = date('Y-m-d', strtotime($request->to_date));
            //dd($request->user_id);
            $query = "SELECT tickets.id as ticket_id, tickets.user_id, tickets.tkt_type_id, tickets.is_dlog, tickets.company_id, tickets.priority, tickets.tkt_status_id, tickets.description, tickets.feedback, tickets.supporter_uid, tickets.assigner_uid, tickets.assign_time, tickets.created_at, tickets.updated_at, users.name as username, users.address, users.phone, users.email, tickets.location, look_ups.data as location_name, tkt_statuses.name as status_name, ticket_types.name as types_name, tkt_statuses.color as color, (SELECT name FROM users WHERE users.id = tickets.supporter_uid) AS engineer_name, companies.name as comp_name, companies.short_name as comp_shortname, (SELECT name FROM designations WHERE users.designation_id = designations.id) AS designation,(SELECT name FROM departments WHERE users.department_id = departments.id) AS department
                    FROM tickets
                    LEFT OUTER JOIN ticket_types
                        ON tickets.tkt_type_id = ticket_types.id
                    LEFT OUTER JOIN users
                        ON tickets.user_id = users.id
                    LEFT OUTER JOIN tkt_statuses
                        ON tickets.tkt_status_id = tkt_statuses.id
                    LEFT OUTER JOIN companies
                        ON tickets.company_id = companies.comp_id
                    LEFT OUTER JOIN look_ups
                        ON tickets.location = look_ups.id
                    WHERE date(tickets.created_at) BETWEEN '$from_date' AND '$to_date'
                    AND tickets.tkt_dprtmnt = $serv_dept_id
                    ORDER BY tickets.created_at ASC";
        } else {
            $query = "SELECT tickets.id as ticket_id, tickets.user_id, tickets.tkt_type_id, tickets.is_dlog, tickets.company_id, tickets.priority, tickets.tkt_status_id, tickets.description, tickets.feedback, tickets.supporter_uid, tickets.assigner_uid, tickets.assign_time, tickets.created_at, tickets.updated_at, users.name as username, users.address, users.phone, users.email, tickets.location, look_ups.data as location_name, tkt_statuses.name as status_name, ticket_types.name as types_name, tkt_statuses.color as color, (SELECT name FROM users WHERE users.id = tickets.supporter_uid) AS engineer_name, companies.name as comp_name, companies.short_name as comp_shortname,  (SELECT name FROM designations WHERE users.designation_id = designations.id) AS designation, (SELECT name FROM departments WHERE users.department_id = departments.id) AS department
                    FROM tickets
                    LEFT OUTER JOIN ticket_types
                        ON tickets.tkt_type_id = ticket_types.id
                    LEFT OUTER JOIN users
                        ON tickets.user_id = users.id
                    LEFT OUTER JOIN tkt_statuses
                        ON tickets.tkt_status_id = tkt_statuses.id
                    LEFT OUTER JOIN companies
                        ON tickets.company_id = companies.comp_id
                    LEFT OUTER JOIN look_ups
                        ON tickets.location = look_ups.id
                    WHERE tickets.tkt_dprtmnt = $serv_dept_id
                    ORDER BY tickets.created_at ASC";
        }

        $tickets = DB::select($query);

        return Datatables::of($tickets)
                ->addIndexColumn()
                ->addColumn('ticket_id', function ($data) {
                    $tkt_id = date('md', strtotime($data->created_at)) . $data->ticket_id;
                    return $tkt_id;
                })
                ->addColumn('username', function ($data) {
                    return '<span>' . $data->username . '</span><p>Work Place: ' . $data->location_name . '</p><p>Mobile: ' . $data->phone . '</p><p> Email: ' . $data->email . '</p><p>Designation: ' . $data->designation . '</p><p>Department: ' . $data->department . '</p>';
                })
                ->addColumn('engineer_name', function ($data) {
                    return '<span>' . $data->engineer_name . '</span>';
                })
                ->addColumn('description', function ($data) {
                    $rest = strlen($data->description) > 100 ? '...' : '';
                    $des = '<p>' . utf8_encode(substr($data->description, 0, 100)) . $rest . '</p>';
                    return $des;
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge" style="color:#fff; background-color:' . $data->color . '">' . $data->status_name . '</span>';
                })
                ->addColumn('date', function ($data) {
                    $status_time = "";
                    if($data->tkt_status_id == 3){
                        $status_time = 'Ongoing :'.$data->updated_at;
                    }elseif($data->tkt_status_id == 4){
                        $status_time = 'Completed :'.$data->updated_at;
                    }else{
                        $status_time = "";
                    }
                    return '<p>Created: ' . $data->created_at . '</p><p>Assigned: ' . $data->assign_time . '</p><p>'.$status_time. '</p>';
                })
                ->addColumn('action', function ($data) {
                    $button = '
                                <div class="d-flex">
                                <a href="' . route('head.ticket.show', $data->ticket_id) . '" class="btn btn-light align-items-center btn-sm btn-sm-custom">View</a></div>';
                    /*$button .= '&nbsp;';*/
                    return $button;
                })
                ->rawColumns(['description', 'status', 'username', 'engineer_name', 'date', 'action'])
                ->toJson();
    }


    public function showByStatus($type)
    {
        $serv_dept_id =  getServiceHeadDeptment(Auth::user()->id);
        if ($type === 'pend') {
            $data_status = Ticket::whereDate('tickets.created_at', '<', Carbon::now()->subDays(3))
                ->whereIn('tickets.tkt_status_id', [2, 3])
                ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->leftJoin('users', 'tickets.user_id', '=', 'users.id')
                ->leftJoin('companies', 'tickets.company_id', '=', 'companies.comp_id')
                ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tickets.*', 'users.name as tktowner', 'users.email', 'users.phone', 'look_ups.data as location_name', DB::raw("(SELECT users.name as enginer_name FROM users WHERE users.id = tickets.supporter_uid) AS engineer"), 'companies.name as company_name', 'companies.short_name as cmpsrt_name')
                ->get();
        } elseif ($type === 'today') {
            $data_status = Ticket::whereDate('tickets.created_at', Carbon::today())
                ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                ->join('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->join('users', 'tickets.user_id', '=', 'users.id')
                ->leftJoin('companies', 'tickets.company_id', '=', 'companies.comp_id')
                ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', 'users.name as tktowner', 'users.email', 'users.phone', 'look_ups.data as location_name', DB::raw("(SELECT users.name as enginer_name FROM users WHERE users.id = tickets.supporter_uid) AS engineer"), 'companies.name as company_name', 'companies.short_name as cmpsrt_name')
                ->get();
        } else {
            $data_status = Ticket::where('tickets.tkt_status_id', '=', $type)
                ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
                ->join('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->join('users', 'tickets.user_id', '=', 'users.id')
                ->leftJoin('companies', 'tickets.company_id', '=', 'companies.comp_id')
                ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', 'users.name as tktowner', 'users.email', 'users.phone', 'look_ups.data as location_name', DB::raw("(SELECT users.name as enginer_name FROM users WHERE users.id = tickets.supporter_uid) AS engineer"), 'companies.name as company_name', 'companies.short_name as cmpsrt_name')
                ->get();
        }
        return view('backend.head.ticket.status-data', compact('data_status'));
    }


    public function report()
    {
        $serv_dept_id =  getServiceHeadDeptment(Auth::user()->id);

        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
        $dateE = Carbon::now()->startOfMonth();
        $date = Carbon::now()->subDays(90);
        $tkt_month = Ticket::join('companies', 'companies.comp_id', '=', 'tickets.company_id')
            ->select('companies.name as company_name', DB::raw('count(tickets.id) as total'))
            //->whereBetween('tickets.created_at',[$dateS,$dateE])
            ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
            ->where('tickets.created_at', '>=', $date)
            ->groupBy('tickets.company_id')
            ->orderBy('total', 'DESC')
            ->get();

        $tkt_engin = Ticket::join('users', 'users.id', '=', 'tickets.supporter_uid')
            ->select('users.name as user_name', DB::raw('count(tickets.id) as total'))
            //->whereBetween('tickets.created_at',[$dateS,$dateE])
            ->where('tickets.created_at', '>=', $date)
            ->where('tickets.tkt_dprtmnt',  $serv_dept_id)
            ->groupBy('tickets.supporter_uid')
            ->orderBy('total', 'DESC')
            ->get();

        return view('backend.head.ticket.report-data', compact('tkt_month', 'tkt_engin'));
    }

}
