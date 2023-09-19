<?php

namespace App\Http\Controllers\Sub;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketFile;
use App\Models\TktSupervisor;
use App\Models\TktSupportActivity;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Notifications\TicketEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = "SELECT count(ID) as ID, SUM(status_C) completed ,SUM(status_O) ongoing, SUM(status_op) as open from (
            SELECT ID,
                CASE WHEN tickets.tkt_status_id = 4 THEN 1 else 0
                END as status_C,
                CASE WHEN tickets.tkt_status_id = 3 THEN 1 else 0
                END as status_O,
                CASE WHEN tickets.tkt_status_id = 2 THEN 1 else 0
                END as status_op
            FROM `tickets` WHERE tickets.user_id = " . Auth::user()->id . ") as SS ";

        $tkt_status = DB::select($query);
        $tickets = Ticket::where([['user_id', Auth::user()->id], ['company_id', Auth::user()->company_id]])->orderBy('created_at', 'DESC')->get();

        return view('backend.supervisor.ticket.index', compact('tickets', 'tkt_status'));
    }

    public function support()
    {
        $query = "SELECT count(ID) as ID, SUM(status_C) completed ,SUM(status_O) ongoing, SUM(status_new) new_tkt, SUM(status_op) as open from (
            SELECT ID,
                CASE WHEN tickets.tkt_status_id = 1 THEN 1 else 0
                END as status_new,
                CASE WHEN tickets.tkt_status_id = 4 THEN 1 else 0
                END as status_C,
                CASE WHEN tickets.tkt_status_id = 3 THEN 1 else 0
                END as status_O,
                CASE WHEN tickets.tkt_status_id = 2 THEN 1 else 0
                END as status_op
            FROM `tickets` WHERE tickets.supporter_uid = " . Auth::user()->id . ") as SS ";

        $tkt_status = DB::select($query);

        $tickets = Ticket::where('supporter_uid', Auth::user()->id)->get();

        return view('backend.supervisor.ticket.support.index', compact('tickets', 'tkt_status'));
    }

    public function supportTicketData()
    {

        $query = "SELECT tickets.id as ticket_id, tickets.user_id, tickets.tkt_type_id, tickets.company_id, tickets.priority, tickets.tkt_status_id, tickets.description, tickets.feedback, tickets.supporter_uid, tickets.assigner_uid, tickets.assign_time, tickets.created_at, tickets.updated_at, users.name as username, tkt_statuses.name as status_name, tkt_statuses.color as color
                FROM tickets
                LEFT OUTER JOIN ticket_types
                    ON tickets.tkt_type_id = ticket_types.id
                LEFT OUTER JOIN users
                    ON tickets.user_id = users.id
                LEFT OUTER JOIN tkt_statuses
                    ON tickets.tkt_status_id = tkt_statuses.id
                WHERE 
                    tickets.supporter_uid = " . Auth::user()->id . "
                ORDER BY users.company_id ASC";

        $tickets = DB::select($query);

        return Datatables::of($tickets)
            ->addIndexColumn()
            ->addColumn('ticket_id', function ($data) {
                $tkt_id = date('md', strtotime($data->created_at)) . $data->ticket_id;
                return $tkt_id;
            })
            ->addColumn('description', function ($data) {
                $rest = strlen($data->description) > 50 ? '...' : '';
                $des = '<p>' . substr($data->description, 0, 30) . $rest . '</p>';
                return $des;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge" style="color:#fff; background-color:' . $data->color . '">' . $data->status_name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $button = '
                    <a href="' . route('sub.ticket.support.show', $data->ticket_id) . '" class="btn btn-light align-items-center btn-sm btn-sm-custom">View</a>
                                    <a href="#" class="btn btn-light align-items-center btn-sm btn-sm-custom" 
                                        data-tid="' . $data->ticket_id . '"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#updateTicketModal">Edit</i>
                                    </a>';
                /*$button .= '&nbsp;';*/
                return $button;
            })
            ->rawColumns(['description', 'status', 'action'])
            ->toJson();
    }

    public function supportShow($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $comments =  TicketComment::with('user')
            ->where('ticket_comments.ticket_id', $id)
            ->get();
        return view('backend.supervisor.ticket.support.show', compact('ticket', 'comments'));
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
            'type' => 'required',
            'location' => 'required|max:50',
            'description' => 'required|max:2000|unique:tickets',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $ticket  = new Ticket();
        $ticket->user_id       = Auth::id();
        $ticket->tkt_type_id   = $request->type;
        $ticket->location      = $request->location;
        $ticket->company_id    = Auth::user()->company_id;
        $ticket->description   = $request->description;
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

        // start email notification
        $users = User::leftJoin('tkt_supervisors', 'users.id', '=', 'tkt_supervisors.user_id')
            ->where('tkt_supervisors.company_id', Auth::user()->company_id)
            ->select('users.id', 'users.emp_id', 'users.name', 'users.department_id', 'users.company_id', 'users.reportto_id', 'users.email')
            ->get();
        if (count($users) > 0) :
            foreach ($users as $user) {
                $this->sendNotification($user->id, $ticket->id, $ticket->description);
            }
        endif;
        if (Notification::send($users, new NewTicketNotification(Ticket::latest('id')->first()))) {
            return back();
        }
        // end notification

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
                        <a href="' . route('sub.ticket.show', $ticket->id) . '" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="edit" width="18" height="18"></i></a>
                        <a href="' . route('sub.ticket.show', $ticket->id) . '" class="btn btn-light btn-sm btn-sm-custom"><i data-feather="eye" width="18" height="18"></i></a>
                    </td>
                </tr>';

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
        return view('backend.supervisor.ticket.show', compact('ticket', 'comments'));
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
     * Modal Open for assign Engineer
     */
    public function assignUserModal(Request $request)
    {

        // $users =  User::where('reportto_id', Auth::user()->id)
        //         ->whereNotIn('id', [Auth::user()->id])->get();

        $users = User::leftJoin('tkt_support_teams', 'users.id', '=', 'tkt_support_teams.engineer_id')
            ->leftJoin('companies', 'users.company_id', '=', 'companies.comp_id')
            ->where('tkt_support_teams.supervisor_id', Auth::user()->id)
            ->select('users.id', 'users.emp_id', 'users.name', 'companies.short_name as comp_name')
            ->orderBy('companies.short_name', "ASC")
            ->get();

        $option = '<option value="" disabled selected>Select Employee</option>';

        foreach ($users as $user) {
            $option .= '<option value="' . $user->id . '">' . $user->comp_name . ' - ' . $user->name . '</option>';
        }
        $tkt_info = Ticket::leftJoin('users', 'tickets.user_id', '=', 'users.id')
            ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
            ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->leftJoin('ticket_types', 'tickets.tkt_type_id', '=', 'ticket_types.id')
            ->where('tickets.id', $request->tkt_id)
            ->select('ticket_types.name as type_name', 'users.name as username', 'users.email', 'users.phone', 'look_ups.data as location_name', 'designations.name as designation_name', 'departments.name as department_name')
            ->first();
        $output = '<p class="description"><strong>Description:</strong> ' . $request->description . '</p><strong>Category: </strong>' . $tkt_info->type_name . '</p><p><strong>Company: </strong> ' . $request->company . '<p><strong>Name: </strong>' . $tkt_info->username . '</p><p><strong>Work Place: </strong> ' . $tkt_info->location_name . '</p><p><strong>Email: </strong>' . $tkt_info->email . '</p><p><strong>Mobile: </strong>' . $tkt_info->phone . '</p><p><strong>Designation: </strong>' . $tkt_info->designation_name . '</p><p><strong>Department: </strong>' . $tkt_info->department_name . '</p>
                    <div class="form-group">
                    <label for="assignUser" class="col-form-label mb-0">Assign To</label>
                    <select class="form-control mb-3 assignUser" id="assignUser">' . $option . '</select> 
                    <input type="hidden" id="ticket_id" value="' . $request->tkt_id . '">
                    <input type="hidden" id="user_id" value="' . $request->userId . '">
                    <input type="hidden" id="tkt_type_id" value="' . $request->type . '">
                </div>';

        return response()->json(['output' => $output]);
    }

    /**
     * Update the ticket for assign enginner by Supervisor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBySuperVisor(Request $request)
    {

        if (!empty($request->type)) :

            $ticket = Ticket::where('id', $request->tkt_id)
                ->where('user_id', $request->user_id)
                ->update(['supporter_uid' => $request->staff_id, 'tkt_status_id' => 2, 'assigner_uid' => Auth::user()->id, 'assign_time' => Carbon::now()]);

            $tkt_description = "Ticket# " . $request->tkt_id . " assigned ";
            if ($request->staff_id) :
                $this->sendNotification($request->staff_id, $request->tkt_id, $tkt_description);
            endif;

            $users = User::where('id', $request->staff_id)
                ->select('id', 'emp_id', 'name', 'department_id', 'company_id', 'reportto_id', 'email')
                ->get();
            if (Notification::send($users, new NewTicketNotification(Ticket::where('id', $request->tkt_id)->first()))) {
                return back();
            }

            return response()->json($ticket);

        else :
            return response()->json(['error' => 'This tikcet from Support Engineer']);
        endif;
    }

    /**
     * Feedback function will give user
     */

    public function feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'feedback' => 'required|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $data = [
            'feedback'   => $request->feedback,
        ];
        Ticket::where('id', $request->ticket_id)
            ->update($data);
        return response()->json(['success' => 'Your ticket feedback has been submitted successfully']);
    }

    /**
     * Function for change Status by Engineer
     */
    public function updateStatusByEngineer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $status = TktSupportActivity::where([['ticket_id', '=', $request->ticket_id], ['activity', '=', $request->status]])->first();

        if ($request->status != 1) :
            if ($status == null) {
                $logdata = [
                    'subject'        => 'Status',
                    'activity'       => $request->status,
                    'ticket_id'      => $request->ticket_id,
                    'supporter_id'   => Auth::user()->id,
                    'added_on'       => new Carbon(),
                ];
                Ticket::where('id', $request->ticket_id)
                    ->update(['tkt_status_id' => $request->status]);
                TktSupportActivity::create($logdata);
                return response()->json(['success' => 'Your ticket has been updated successfully']);
            } else {
                return response()->json(['success' => 'Already you have updated status']);
            }
        else :
            return response()->json(['success' => 'You have not changed status.']);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //dd($request->ticket_id);
        $id = $request->ticket_id;

        TktSupportActivity::where('ticket_id', $id)->delete();

        TicketComment::where('ticket_id', $id)->delete();


        $files = TicketFile::where('ticket_id', $id)->get();
        if (count($files) > 0) {
            foreach ($files as $file) {
                TicketFile::where('ticket_id', $file->id)->delete();
                unlink("uploads/tickets/" . $file->name);
            }
        }

        Ticket::find($id)->delete();

        // if(!is_null(($status))){
        //     $status->delete();
        // }
        // if(!is_null(($comments))){
        //     $comments->delete();
        // }

        return response()->json(['success' => 'Ticket deleted successfully.']);
    }

    /**
     * Display all ticket for Supervisor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function manageTicket()
    {
        // $query ="SELECT count(ID) as ID, SUM(status_C) completed ,SUM(status_O) ongoing, SUM(status_new) new_tkt, SUM(status_op) as open from (
        //     SELECT ID,
        //         CASE WHEN tickets.tkt_status_id = 1 THEN 1 else 0
        //         END as status_new,
        //         CASE WHEN tickets.tkt_status_id = 4 THEN 1 else 0
        //         END as status_C,
        //         CASE WHEN tickets.tkt_status_id = 3 THEN 1 else 0
        //         END as status_O,
        //         CASE WHEN tickets.tkt_status_id = 2 THEN 1 else 0

        //         END as status_op
        //     FROM `tickets`) as SS ";

        // $tkt_status = DB::select($query);

        // $company_ids = tktSupervisor::where('user_id', Auth::user()->id)->select('company_id')->get();
        // $tickets = Ticket::whereIn('company_id', $company_ids)->get();

        // $query = "SELECT tickets.id as ticket_id, tickets.user_id, tickets.tkt_type_id, tickets.company_id, tickets.priority, tickets.tkt_status_id, tickets.description, tickets.feedback, tickets.supporter_uid, tickets.assigner_uid, tickets.assign_time, tickets.created_at, tickets.updated_at, users.name as username, tkt_statuses.name as status_name, tkt_statuses.color as color
        //         FROM tickets
        //         LEFT OUTER JOIN ticket_types
        //             ON tickets.tkt_type_id = ticket_types.id
        //         LEFT OUTER JOIN users
        //             ON tickets.user_id = users.id
        //         LEFT OUTER JOIN tkt_statuses
        //             ON tickets.tkt_status_id = tkt_statuses.id
        //         WHERE 
        //             tickets.company_id IN (SELECT company_id FROM tkt_supervisors WHERE user_id = ".Auth::user()->id.")
        //         ORDER BY users.company_id ASC";

        // $tickets = DB::select($query);

        //return view('backend.supervisor.ticket.support.manage',compact('tkt_status'));
        
    // $tickets = Ticket::with('tktSupportActivity')
        //             ->select('tickets.*', 'tkt_statuses.name as status_name', 'ticket_types.name', 'companies.short_name as comp_shortname', 'look_ups.data as location_name')
        //             ->leftJoin('tkt_statuses', 'tickets.tkt_status_id','=', 'tkt_statuses.id')
        //             ->leftJoin('ticket_types', 'tickets.tkt_type_id','=', 'ticket_types.id')
        //             ->leftJoin('companies', 'tickets.company_id','=', 'companies.comp_id')
        //             ->leftJoin('look_ups', 'tickets.location','=', 'look_ups.id')
        //             ->orderBy('tickets.id', 'ASC')
        //             ->get();

        $statuses = DB::table('tkt_statuses')
            ->join('tickets', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
            ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', DB::raw('count(tickets.id) as total'))
            ->groupBy('tickets.tkt_status_id')
            ->get();
        $pending = Ticket::select('tickets.description', 'tickets.created_at')
            ->whereDate('created_at', '<', Carbon::now()->subDays(3))
            ->whereIn('tickets.tkt_status_id', [2, 3])
            ->orderBy('created_at', 'DESC')
            ->get()->count();

        return view('backend.supervisor.ticket.support.manage', compact('statuses', 'pending'));
    }


    public function superVisorManageTicketData(Request $request)
    {

        if (request()->ajax()) {
            //dd($request->emp_id);
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
                        ORDER BY users.company_id ASC";
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
                        ORDER BY users.company_id ASC";
            }

            $tickets = DB::select($query);

            return Datatables::of($tickets)
                ->addIndexColumn()
                ->addColumn('ticket_id', function ($data) {
                    $tkt_id = date('md', strtotime($data->created_at)) . $data->ticket_id;
                    return $tkt_id;
                })
                ->addColumn('username', function ($data) {
                    return '<span>'.$data->username.'</span><p>Work Place: ' . $data->location_name . '</p><p>Mobile: ' . $data->phone . '</p><p> Email: ' . $data->email . '</p><p>Designation: ' . $data->designation . '</p><p>Department: ' . $data->department . '</p>';
                })
                ->addColumn('engineer_name', function ($data) {
                    return '<span>' . $data->engineer_name . '</span>';
                })
                ->addColumn('description', function ($data) {
                    $rest = strlen($data->description) > 100 ? '...' : '';
                    $des = '<p>' . substr($data->description, 0, 100) . $rest . '</p>';
                    return $des;
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge" style="color:#fff; background-color:' . $data->color . '">' . $data->status_name . '</span>';
                })
                ->addColumn('date', function ($data) {
                    return '<p>Created: ' . $data->created_at . '</p><p>Assigned: ' . $data->assign_time . '</p><p>Updated: ' . $data->updated_at . '</p>';
                })
                ->addColumn('action', function ($data) {
                    $button = '
                                <div class="d-flex"><a href="javascript:void(0)" class="asaignModal btn btn-light align-items-center btn-sm btn-sm-custom me-1"  
                                data-description="' . $data->description . '"
                                data-comp="' . $data->comp_name . '" data-id="' . $data->ticket_id . '" data-userid="' . $data->user_id . '" data-type="' . $data->tkt_type_id . '" >Assign</i>
                                </a>
                                <a href="' . route('sub.ticket.show', $data->ticket_id) . '" class="btn btn-light align-items-center btn-sm btn-sm-custom">View</a></div>';
                    /*$button .= '&nbsp;';*/
                    return $button;
                })
                ->rawColumns(['description', 'status', 'username', 'engineer_name', 'date', 'action'])
                ->toJson();
        }
    }

    public function showByStatus($type)
    {
        if ($type === 'pend') {
            $data_status = Ticket::whereDate('tickets.created_at', '<', Carbon::now()->subDays(3))
                ->whereIn('tickets.tkt_status_id', [2, 3])
                ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->leftJoin('users', 'tickets.user_id', '=', 'users.id')
                ->leftJoin('companies', 'tickets.company_id', '=', 'companies.comp_id')
                ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tickets.*', 'users.name as tktowner', 'users.email', 'users.phone', 'look_ups.data as location_name', DB::raw("(SELECT users.name as enginer_name FROM users WHERE users.id = tickets.supporter_uid) AS engineer"), 'companies.name as company_name', 'companies.short_name as cmpsrt_name')
                ->get();
        } elseif ($type === 'today') {
            $data_status = Ticket::whereDate('tickets.created_at', Carbon::today())
                ->join('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->join('users', 'tickets.user_id', '=', 'users.id')
                ->leftJoin('companies', 'tickets.company_id', '=', 'companies.comp_id')
                ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', 'users.name as tktowner', 'users.email', 'users.phone', 'look_ups.data as location_name', DB::raw("(SELECT users.name as enginer_name FROM users WHERE users.id = tickets.supporter_uid) AS engineer"), 'companies.name as company_name', 'companies.short_name as cmpsrt_name')
                ->get();
        } else {
            $data_status = Ticket::where('tickets.tkt_status_id', '=', $type)
                ->join('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->join('users', 'tickets.user_id', '=', 'users.id')
                ->leftJoin('companies', 'tickets.company_id', '=', 'companies.comp_id')
                ->leftJoin('look_ups', 'tickets.location', '=', 'look_ups.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', 'users.name as tktowner', 'users.email', 'users.phone', 'look_ups.data as location_name', DB::raw("(SELECT users.name as enginer_name FROM users WHERE users.id = tickets.supporter_uid) AS engineer"), 'companies.name as company_name', 'companies.short_name as cmpsrt_name')
                ->get();
        }
        return view('backend.supervisor.ticket.status-data', compact('data_status'));
    }

    /**
     * Store daily logs by Engineer or Employee
     */

    public function storeDailyLogs(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'message' => 'required|max:2000',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
            // return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $ownompanyId = $this->getCompanyIdbyUserId($request->user_id);

        $ticket  = new Ticket();
        $ticket->user_id       = $request->user_id;
        $ticket->company_id    = $ownompanyId;
        $ticket->supporter_uid = Auth::id();
        $ticket->description   = $request->message;
        $ticket->tkt_type_id   = $request->type;
        $ticket->is_dlog       = 1;
        $ticket->save();

        //$reporter_id = Auth::user()->reportto_id;

        $users = User::leftJoin('tkt_supervisors', 'users.id', '=', 'tkt_supervisors.user_id')
            ->where('tkt_supervisors.company_id', Auth::user()->company_id)
            ->select('users.id', 'users.emp_id', 'users.name', 'users.department_id', 'users.company_id', 'users.reportto_id', 'users.email')
            ->get();

        if (count($users) > 0) :
            foreach ($users as $user) {
                $this->sendNotification($user->id, $ticket->id, $ticket->description);
            }
        endif;

        //$user = User::where('id', '=',$reporter_id)->get();

        if (Notification::send($users, new NewTicketNotification(Ticket::latest('id')->first()))) {
            return back();
        }

        return redirect()->back()->with('success', 'Your data saved successfully');
    }


    public function getCompanyIdbyUserId($id)
    {
        $company_id = User::where('users.id', $id)->pluck('company_id')->first();
        return $company_id;
    }


    public function report()
    {

        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
        $dateE = Carbon::now()->startOfMonth();
        $date = Carbon::now()->subDays(90);

        $tkt_month = Ticket::join('companies', 'companies.comp_id', '=', 'tickets.company_id')
            ->select('companies.name as company_name', DB::raw('count(tickets.id) as total'))
            //->whereBetween('tickets.created_at',[$dateS,$dateE])
            ->whereDate('tickets.created_at', '>=', $date)
            ->groupBy('tickets.company_id')
            ->orderBy('total', 'DESC')
            ->get();

        $tkt_engin = Ticket::join('users', 'users.id', '=', 'tickets.supporter_uid')
            ->select('users.name as user_name', DB::raw('count(tickets.id) as total'))
            //->whereBetween('tickets.created_at',[$dateS,$dateE])
            ->whereDate('tickets.created_at', '>=', $date)
            ->groupBy('tickets.supporter_uid')
            ->orderBy('total', 'DESC')
            ->get();

        return view('backend.supervisor.ticket.report-data', compact('tkt_month', 'tkt_engin'));
    }
}
