<?php

namespace App\Http\Controllers;

use App\Models\EventRoster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EventCalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* if ($request->ajax()) {

            $data = EventRoster::whereDate('event_start', '>=', $request->start)
                ->whereDate('event_end',   '<=', $request->end)
                ->get(['id', 'title', 'event_start', 'event_end']);
            
            // $id = []; $start = []; $end = []; $user = [];
            // $event = [];
            // foreach($data as $row){
            //     $id[] = $col->id;
            //     $start[] = $col->start_event;
            //     $end[] = $col->end_event;
            //     $user[] = $col->user_id;
            // }
            

            return response()->json($data);
        } */

        $user_role = Auth::user()->role->slug;
        return view('backend.event.calendar', ['role' => $user_role]);
    }

    public function allRoster(Request $request)
    {

        $isViewReportRoster = getReadPermission(19, 1);
        if ($isViewReportRoster == false) :
            return response()->json(['errors' => ['0' => 'You have not permission']]);
        endif;

        $user_role = Auth::user()->role->slug;

        $date_data = EventRoster::select('event_start', DB::raw('count(event_rosters.id) as total'))
            ->whereMonth('event_start', Carbon::now()->month)
            ->orderBy('event_start', 'asc')
            ->groupBy('event_start')
            ->get();


        if ($request->ajax()) {

            if ($request->event_date) :

                $rosters = EventRoster::leftJoin('users', 'event_rosters.user_id', 'users.id')
                    ->leftJoin('departments', 'users.department_id', 'departments.id')
                    ->whereDate('event_start', $request->event_date)
                    ->select('event_rosters.*', 'users.name as username', 'departments.name as dept_name')
                    ->orderBy('event_rosters.event_start', 'ASC')
                    ->get();
            else :

                $rosters = EventRoster::leftJoin('users', 'event_rosters.user_id', 'users.id')
                    ->leftJoin('departments', 'users.department_id', 'departments.id')
                    ->whereMonth('event_start', Carbon::now()->month)
                    ->select('event_rosters.*', 'users.name as username', 'departments.name as dept_name')
                    ->orderBy('event_rosters.event_start', 'ASC')
                    ->get();

            endif;

            return DataTables::of($rosters)
                ->addIndexColumn()
                ->addColumn('event_start', function ($data) {
                    return date('d-m-Y', strtotime($data->event_start));
                })
                ->addColumn('created_at', function ($data) {
                    return date('d-m-Y', strtotime($data->created_at));
                })
                ->addColumn('head_name', function ($data) {
                    return '<p>'.$data->headUser->headName.'</p>';
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-id="'.$data->id.'" class="btn btn-danger btn-sm btn-sm-custom deleteRecord">Delete</a>';
                    return $button;
                })
                ->rawColumns(['created_at', 'head_name','action'])
                ->toJson();

        }

        return view('backend.event.roster', ['role' => $user_role, 'date_data' => $date_data]);
    }


    public function getRoster(Request $request)
    {
        $getHeadUid = EventRoster::where('head_uid', Auth::user()->id)->first();

        if ($getHeadUid) :
            $events = EventRoster::leftJoin('users', 'event_rosters.user_id', 'users.id')
                ->whereDate('event_start', '>=', $request->start)
                ->whereDate('event_end',   '<=', $request->end)
                ->where('head_uid', Auth::user()->id)
                ->select('event_rosters.*', 'users.name as username')
                ->get();
        else :
            $events = EventRoster::leftJoin('users', 'event_rosters.user_id', 'users.id')
                ->whereDate('event_start', '>=', $request->start)
                ->whereDate('event_end',   '<=', $request->end)
                ->where('user_id', Auth::user()->id)
                ->select('event_rosters.*', 'users.name as username')
                ->get();
        endif;

        $data = [];

        foreach ($events as $col) {
            $data[] = [
                'id' => $col->id,
                'start' => date_format(date_create($col->event_start), "Y-m-d"),
                'end' =>  date_format(date_create($col->event_end), "Y-m-d"),
                'title' => $col->title . ':' . $col->username,
            ];
        }

        return response()->json($data);
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

        $isCreateRoster = getCreatePermission(18, 1);
        if ($isCreateRoster == false) :
            return response()->json(['errors' => ['0' => 'You have not permission']]);
        endif;

        if (count((array)$request->user_id) > 0) :

            $validator = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'user_id.*' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            foreach ($request->user_id as $userId) {
                $isExistUser = $this->isExistUser((int)$userId, $request->start_date);
                if ($isExistUser == false) :
                    $event = new EventRoster();
                    $event->event_start     = $request->start_date;
                    $event->event_end       = $request->end_date;
                    $event->user_id         = (int)$userId;
                    $event->head_uid        = Auth::user()->id;
                    $event->inserted_uid    = Auth::user()->id;
                    $event->title  = 'Roster';
                    $event->save();
                else :
                    return response()->json(['errors' => ['0' => 'Already posted this employee']]);
                endif;
            }

            return response()->json(['success' => 'Data is successfully added']);

        else :
            return response()->json(['errors' => ['0' => 'Please select employees']]);
        endif;
    }

    public function isExistUser($user_id, $start_date)
    {
        $user = EventRoster::where('user_id', $user_id)->where('event_start', $start_date)->first();
        if ($user) {
            return  $user->user_id;
        } else {
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $isUpdateRoster = getUpdatePermission(18, 1);

        if ($isUpdateRoster == false) :
            return response()->json(['errors' => ['0' => 'You have not permission']]);
        endif;

        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $cmonth = date('m');
            $cday = date('d');
            $month = date("m", strtotime($request->start_date));
            $day = date("d", strtotime($request->start_date));

            if ($cmonth == $month && $cday < $day) :
                $data = [
                    'event_start' => $request->start_date,
                    'event_end' => $request->end_date,
                ];
                EventRoster::where('id', $request->id)->update($data);
                return response()->json(['success' => 'Data Updated Successfully']);
            else :
                return response()->json(['errors' => ['0' => 'Impossible to Update right now']]);
            endif;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isDeletRoster = getDeletePermission(18, 1);

        if ($isDeletRoster == false) :
            return response()->json(['errors' => ['0' => 'You have not permission']]);
        endif;

        $roster = EventRoster::find($id);
        $roster->delete();
        return response()->json([
            'status'=>200,
            'success' => 'Roster Deleted Successfully'
        ]);
    }


    /*
    public function calendarEvents(Request $request)
    {

        dd($request->all());

        switch ($request->type) {
            case 'create':
                $event = EventRoster::create([
                    'title' => $request->event_name,
                    'event_start' => $request->event_start,
                    'event_end' => $request->event_end,
                ]);

                return response()->json($event);
                break;

            case 'edit':
                $event = EventRoster::find($request->id)->update([
                    'event_name' => $request->event_name,
                    'event_start' => $request->event_start,
                    'event_end' => $request->event_end,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = EventRoster::find($request->id)->delete();

                return response()->json($event);
                break;

            default:
                # ...
                break;
        }
    }
*/
}
