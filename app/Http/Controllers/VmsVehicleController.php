<?php

namespace App\Http\Controllers;

use App\Models\VmsVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VmsVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicle_data = VmsVehicle::all();
        return view('backend.common.vms.vehicles', ['vehicle_data' => $vehicle_data]);
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

        if (getCreatePermission(16, 1) == false) {
            return response()->json(['errors' => [0 => 'You have not permission for creating vehicle']]);
        }

        $validator = Validator::make($request->all(), [
            'vehicle_no'        => 'required',
            'vehicle_model'     => 'required',
            'department'        => 'required',
            'company'           => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $v_id = $request->vehicle_id;

        if($v_id):
            
            $vehicle = VmsVehicle::find($v_id);
            $vehicle->vehicle_no     = $request->vehicle_no;
            $vehicle->v_model        = $request->vehicle_model;
            $vehicle->assign_team    = $request->department;
            $vehicle->company        = $request->company;
            $vehicle->regi_date      = $request->registration_date;
            $vehicle->seat_capicity  = $request->seat_capacity;
            $vehicle->driver_id      = $request->driver_name;
            $vehicle->save();

        else:

            $vehicle = new VmsVehicle;
            $vehicle->vehicle_no     = $request->vehicle_no;
            $vehicle->v_model        = $request->vehicle_model;
            $vehicle->assign_team    = $request->department;
            $vehicle->company        = $request->company;
            $vehicle->regi_date      = $request->registration_date;
            $vehicle->seat_capicity  = $request->seat_capacity;
            $vehicle->driver_id      = $request->driver_name;
            $vehicle->save();

        endif;

        return response()->json(['success' => 'Your data has been submitted successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VmsVehicle  $vmsVehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $vData = VmsVehicle::find($id);

        return view('backend.common.vms.vehicle-show', ['vdata' => $vData]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VmsVehicle  $vmsVehicle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vData = VmsVehicle::find($id);
        return response()->json(['vData' => $vData ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VmsVehicle  $vmsVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VmsVehicle $vmsVehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VmsVehicle  $vmsVehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(VmsVehicle $vmsVehicle)
    {
        //
    }
}
