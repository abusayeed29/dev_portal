<?php

namespace App\Http\Controllers;

use App\Models\VmsDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Gd\Driver;

class VmsDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $driver_data = VmsDriver::all();
        return view('backend.common.vms.driver',['driver_data' => $driver_data]);
    }

    public function findDriver(Request $request)
    {
        //$users = [];
        if ($request->has('q')) {

            $search = $request->q;

            if ($search == '') :
                $drivers = VmsDriver::select('id','name', 'mobile')->get();
            else :
                $drivers = VmsDriver::select('id','name', 'mobile')
                        ->where('vms_drivers.name', 'LIKE', "%$search%")
                        ->orWhere('vms_drivers.mobile', 'LIKE', "%$search%")
                        ->get();
            endif;

            $response = array();
            foreach ($drivers as $driver) {
                $response[] = array(
                    'id' => $driver->id,
                    'text' => $driver->name . ' (' . $driver->mobile .') ',
                );
            }
            return response()->json($response);
        }

        // $data = $request->all();
        // $query  = $data['query'];
        // $filter_data = User::select('id','name')
        //                 ->where('name', 'LIKE', '%'.$query.'%')
        //                 ->get();

        // return response()->json($filter_data);


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

        if (getCreatePermission(15, 1) == false) {
            return response()->json(['errors' => [0 => 'Access Denied']]);
        }

        $validator = Validator::make($request->all(), [
            'driver_name'   => 'required',
            'mobile'        => 'required',
            'employee_id'   => 'required',
            'status'        => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $d_id = $request->driver_id;

        if($d_id):

            $driver = VmsDriver::find($d_id);
            $driver->name           = $request->driver_name;
            $driver->employee_id    = $request->employee_id;
            $driver->mobile         = $request->mobile;
            $driver->is_active      = $request->status;
            $driver->save();

        else:

            $driver = new VmsDriver;
            $driver->name           = $request->driver_name;
            $driver->employee_id    = $request->employee_id;
            $driver->mobile         = $request->mobile;
            $driver->is_active      = $request->status;
            $driver->save();

        endif;

        return response()->json(['success' => 'Your data has been submitted successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VmsDriver  $vmsDriver
     * @return \Illuminate\Http\Response
     */
    public function show(VmsDriver $vmsDriver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VmsDriver  $vmsDriver
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vData = VmsDriver::find($id);
        return response()->json(['vData' => $vData ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VmsDriver  $vmsDriver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VmsDriver $vmsDriver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VmsDriver  $vmsDriver
     * @return \Illuminate\Http\Response
     */
    public function destroy(VmsDriver $vmsDriver)
    {
        //
    }
}
