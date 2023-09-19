<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketFile;
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

        $tkt_status = DB::table('tkt_statuses')
            ->join('tickets', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
            ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', DB::raw('count(tickets.id) as total'))
            ->where('tickets.user_id', Auth::user()->id)
            ->groupBy('tickets.tkt_status_id')
            ->get();

        $tickets = Ticket::where([['user_id', Auth::user()->id], ['company_id', Auth::user()->company_id]])->orderBy('created_at', 'DESC')->get();

        return view('backend.user.ticket.index', compact('tickets', 'tkt_status'));
    }

    public function support()
    {
        $dactivity_perm = getReadPermission(7, 1);

        if ($dactivity_perm == true) {

            $tkt_status = DB::table('tkt_statuses')
                ->join('tickets', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
                ->select('tkt_statuses.id as status_id', 'tkt_statuses.name as status_name', 'tkt_statuses.color', 'tickets.*', DB::raw('count(tickets.id) as total'))
                ->where('tickets.supporter_uid', Auth::user()->id)
                ->groupBy('tickets.tkt_status_id')
                ->get();
                
            $tickets = Ticket::where('supporter_uid', Auth::user()->id)->get();

            return view('backend.user.ticket.support.index', compact('tickets', 'tkt_status'));
        } else {
            return back()->with('error', 'Access denied');
        }
    }

    public function supportTicketData()
    {

        $query = "SELECT tickets.id as ticket_id, tickets.user_id, tickets.tkt_type_id, tickets.is_dlog, tickets.company_id, tickets.priority, tickets.tkt_status_id, tickets.description, tickets.feedback, tickets.supporter_uid, tickets.assigner_uid, tickets.assign_time, tickets.created_at, tickets.updated_at, users.name as username, users.phone, users.email, tickets.location, look_ups.data as location_name, tkt_statuses.name as status_name, tkt_statuses.color as color
                FROM tickets
                LEFT OUTER JOIN ticket_types
                    ON tickets.tkt_type_id = ticket_types.id
                LEFT OUTER JOIN users
                    ON tickets.user_id = users.id
                LEFT OUTER JOIN tkt_statuses
                    ON tickets.tkt_status_id = tkt_statuses.id
                LEFT OUTER JOIN look_ups
                    ON tickets.location = look_ups.id
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
            ->addColumn('username', function ($data) {
                if (!empty($data->is_dlog)) {
                    $color = '#FF4500';
                } else {
                    $color = '#27ae60';
                }
                //$rest = strlen($data->username) > 15 ? '...' : '';
                return '<span style="color:' . $color . '" >' . $data->username . '</span><p>Location: ' . substr($data->location_name, 0, 15) . '</p><p>Mobile: ' . $data->phone . '</p>';
            })
            ->addColumn('description', function ($data) {
                $rest = strlen($data->description) > 100 ? '...' : '';
                $des = '<p>' . utf8_encode(substr($data->description, 0, 100)) . $rest . '</p>';
                return $des;
            })
            ->addColumn('status', function ($data) {
                return '<span class="badge" style="color:#fff; background-color:' . $data->color . '">' . $data->status_name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $button = '
                    <div class="d-flex"><a href="' . route('user.ticket.show', $data->ticket_id) . '" class="btn btn-light align-items-center btn-sm btn-sm-custom me-1">View</a>
                                    <a href="#" class="btn btn-light align-items-center btn-sm btn-sm-custom" 
                                        data-tid="' . $data->ticket_id . '"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#updateTicketModal">Status</i>
                                    </a></div>';
                /*$button .= '&nbsp;';*/
                return $button;
            })
            ->rawColumns(['username', 'description', 'status', 'action'])
            ->toJson();
    }

    public function supportShow($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $comments =  TicketComment::with('user')
            ->where('ticket_comments.ticket_id', $id)
            ->get();

        return view('backend.user.ticket.support.show', compact('ticket', 'comments'));
    }

       /**
     * Function for change Status by Engineer
     */
    public function updateStatusByEngineer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        //dd($validator->errors()->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //$status = TktSupportActivity::where([['ticket_id', '=',$request->ticket_id],['activity', '=',$request->status]])->first();
        //$status = Ticket::where([['id', '=',$request->ticket_id]])->first();

        $tkt_data = Ticket::select('tickets.*', 'tkt_statuses.name as status_name')
            ->leftJoin('tkt_statuses', 'tickets.tkt_status_id', '=', 'tkt_statuses.id')
            ->where('tickets.id', $request->ticket_id)
            ->first();

        if ($request->status == 3) {
            //dd($status);
            if ($tkt_data->tkt_status_id == 2) {
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

                $tkt_message = "Ticket# " . $tkt_data->id . " Ongoing By Support Officer : " . getUserName($tkt_data->supporter_uid);

                if ($tkt_data->assigner_uid) :
                    $this->sendNotification($tkt_data->assigner_uid, $request->ticket_id, $tkt_message);
                endif;

                return response()->json(['success' => 'Your ticket has been updated successfully']);

            } else {
                return response()->json(['errors' => [0 => 'Already you have changed.']]);
            }
        } elseif ($request->status == 4) {
            if ($tkt_data->tkt_status_id == 3) {
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

                $tkt_message = "Ticket# " . $tkt_data->id . " Completed By Support Officer : " . getUserName($tkt_data->supporter_uid);

                if ($tkt_data->user_id) :
                    $this->sendNotification($tkt_data->user_id, $request->ticket_id, $tkt_message);
                endif;

                if ($tkt_data->assigner_uid) :
                    $this->sendNotification($tkt_data->assigner_uid, $request->ticket_id, $tkt_message);
                endif;

                return response()->json(['success' => 'Your ticket has been updated successfully']);
            } else {
                return response()->json(['errors' => [0 => 'Already you have updated or Please at first click on ongoing.']]);
            }
        } else {
            return response()->json(['success' => 'You have not changed status.']);
        }
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

        return view('backend.user.ticket.show', compact('ticket', 'comments'));
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


    /**
     * Store daily logs by Engineer or Employee
     */

    public function storeDailyLogs(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'location' => 'required|max:50',
            'type' => 'required',
            'tkt_department' => 'required',
            'message' => 'required|max:2000',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
            // return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $ownompanyId = $this->getCompanyIdbyUserId($request->user_id);

        $ticket  = new Ticket();
        $ticket->user_id       = $request->user_id;
        $ticket->company_id    = $ownompanyId;
        $ticket->location      = $request->location;
        $ticket->supporter_uid = Auth::id();
        $ticket->description   = $request->message;
        $ticket->tkt_type_id   = $request->type;
        $ticket->assign_time   = Carbon::now();
        $ticket->tkt_dprtmnt   = $request->tkt_department;
        $ticket->tkt_status_id = 2;
        $ticket->is_dlog       = 1;
        $ticket->save();

        //$reporter_id = Auth::user()->reportto_id;

        $users = User::leftJoin('tkt_supervisors', 'users.id', '=', 'tkt_supervisors.user_id')
            ->where('tkt_supervisors.tkt_dprtmnt_id', $request->tkt_department)
            ->where('tkt_supervisors.module_id', 4)
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


}
