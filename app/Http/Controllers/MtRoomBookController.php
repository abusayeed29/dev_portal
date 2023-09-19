<?php

namespace App\Http\Controllers;

use App\Models\MtRoom;
use App\Models\MtRoomBook;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MtRoomBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_role = Auth::user()->role->slug;
        return view('backend.common.booking.book-calender', ['role' => $user_role]);
    }

    public function allBooked(Request $request)
    {
        $user_role = Auth::user()->role->slug;

        $date_data = MtRoomBook::select(DB::raw('Date(start_at) as start_date'), DB::raw('count(mt_room_books.id) as total'))
            ->groupBy('start_date')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->ajax()) {

            if ($request->book_date) :

                $bookings = MtRoomBook::leftJoin('users', 'mt_room_books.user_id', 'users.id')
                    ->leftJoin('departments', 'users.department_id', 'departments.id')
                    ->leftJoin('mt_rooms', 'mt_room_books.room_id', 'mt_rooms.id')
                    ->whereDate('start_at', $request->book_date)
                    ->select('mt_room_books.*', 'mt_rooms.name as mt_name', 'mt_rooms.location as mt_location', 'users.name as username', 'departments.name as dept_name')
                    ->orderBy('mt_room_books.start_at', 'ASC')
                    ->get();

            else :

                $bookings = MtRoomBook::leftJoin('users', 'mt_room_books.user_id', 'users.id')
                    ->leftJoin('departments', 'users.department_id', 'departments.id')
                    ->leftJoin('mt_rooms', 'mt_room_books.room_id', 'mt_rooms.id')
                    ->select('mt_room_books.*', 'mt_rooms.name as mt_name', 'mt_rooms.location as mt_location', 'users.name as username', 'departments.name as dept_name')
                    ->orderBy('mt_room_books.start_at', 'ASC')
                    ->get();

            endif;

            //date('Y-m-d H:i:s', strtotime("$request->start_date $request->startTime"));

            return DataTables::of($bookings)
                ->addIndexColumn()
                ->addColumn('booked_date', function ($data) {
                    return date('d-m-Y', strtotime($data->start_at));
                })
                ->addColumn('booked_time', function ($data) {
                    return date('H:i', strtotime($data->start_at)) . ' - ' . date('H:i', strtotime($data->end_at));
                })
                ->addColumn('created_at', function ($data) {
                    return date('d-m-Y:h:i:sA', strtotime($data->created_at));
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="d-flex"><a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-info btn-sm btn-sm-custom">View</a>
                    <a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-danger btn-sm btn-sm-custom deleteRecord">Delete</a></div>';
                    return $button;
                })
                ->rawColumns(['created_at', 'action'])
                ->toJson();
        }

        return view('backend.common.booking.all-book', ['role' => $user_role, 'date_data' => $date_data]);
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
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'purpose' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $start_at = date('Y-m-d H:i:s', strtotime("$request->start_date $request->startTime"));
        $end_at   = date('Y-m-d H:i:s', strtotime("$request->start_date $request->endTime"));


        $start_add_1 = date('Y-m-d H:i:s', strtotime('+1 minute', strtotime("$request->start_date $request->startTime")));
        $end_min_1   = date('Y-m-d H:i:s', strtotime('-1 minute', strtotime("$request->start_date $request->endTime")));

        // $checkStartAt = MtRoomBook::where('room_id1', $request->mettingRoom)
        //                         ->whereBetween($start_at,[$start_add_1, $end_min_1])
        //                         ->orWhereBetween('end_at',[$start_add_1, $end_min_1])
        //                         ->get();


        // $checkEndAt = MtRoomBook::where('room_id', $request->mettingRoom)
        //     ->whereBetween('end_at', [$start_add_1, $end_min_1])
        //     ->get();
        /* "select * from `mt_room_books` where `room_id` = 1 
                    and (`start_at` >= '2023-08-22 12:00:00' OR `end_at` >= '2023-08-22 12:00:00')" */

        $checkStartAt = MtRoomBook::where('room_id', $request->mettingRoom)
            ->where(function ($query) use ($start_add_1) {
                $query->where('start_at', '<=', $start_add_1)
                    ->where('end_at', '>=', $start_add_1);
            })
            ->get();

        $checkEndAt = MtRoomBook::where('room_id', $request->mettingRoom)
            ->where(function ($query) use ($end_min_1) {
                $query->where('start_at', '<=', $end_min_1)
                    ->where('end_at', '>=', $end_min_1);
            })
            ->get();

        //dd(count($checkStartAt).count($checkEndAt));

        if (count($checkStartAt) === 0 && count($checkEndAt) === 0) :

            //dd(count($checkStartAt).count($checkEndAt));

            $bkroom = new MtRoomBook;
            $bkroom->start_at       =  $start_at;
            $bkroom->end_at         =  $end_at;
            $bkroom->start_at       =  $start_at;
            $bkroom->title          =  $request->purpose;
            $bkroom->room_id        =  $request->mettingRoom;
            $bkroom->stationary     =  $request->stationary;
            $bkroom->it_service     =  $request->it_services;
            $bkroom->foods          =  $request->foods;
            $bkroom->user_id        =  Auth::user()->id;
            $bkroom->save();

            return response()->json(['success' => 'You have booked successfully']);

        else :

            return response()->json(['errors' => ['0' => 'Already booked. Please select another time slot']]);

        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $roster = MtRoomBook::where('id', $id)->where('user_id', Auth::user()->id)->first();
        
        if($roster):
            $roster->delete();
            return response()->json([
                'status' => 200,
                'success' => 'Roster Deleted Successfully'
            ]);
        else:
            return response()->json(['errors' => ['0' => 'You have not permission']]);
        endif;
    }


    public function getBooking(Request $request)
    {
        $events = MtRoomBook::leftJoin('mt_rooms', 'mt_room_books.room_id', 'mt_rooms.id')
            ->whereDate('start_at', '>=', $request->start)
            ->whereDate('end_at',   '<=', $request->end)
            ->select('mt_room_books.*', 'mt_rooms.name as room_name', 'mt_rooms.location as location')
            ->get();

        $data = [];

        foreach ($events as $col) {
            $data[] = [
                'id' => $col->id,
                'start' => date_format(date_create($col->start_at), DATE_ATOM),
                'end' =>  date_format(date_create($col->end_at), DATE_ATOM),
                'title' => date_format(date_create($col->start_at), "H:i") . ' - ' . date_format(date_create($col->end_at), "H:i") . ' - ' . $col->title . ' | ' . $col->room_name,
            ];
        }

        return response()->json($data);
    }


    public function meetingRoomSearch(Request $request)
    {

        if ($request->has('q')) {

            $search = $request->q;

            if ($search == '') :
                $rooms = MtRoom::all();
            else :
                $rooms = MtRoom::where('name', 'LIKE', "%$search%")
                    ->orWhere('location', 'LIKE', "%$search%")
                    ->get();
            endif;

            $response = array();
            foreach ($rooms as $room) {
                $response[] = array(
                    'id' => $room->id,
                    'text' => $room->name . '(' . $room->location . ')',
                );
            }
            return response()->json($response);
        }
    }
}
