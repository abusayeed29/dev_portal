<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleRequisition;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Yajra\DataTables\Facades\DataTables;

class VmsRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = VehicleRequisition::all();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('requister', function ($data) {
                    return $data->user->name;
                })
                ->addColumn('pick_drop_time', function ($data) {
                    return $data->pick_time . '<br>' . $data->drop_time;
                })
                ->addColumn('route', function ($data) {
                    return $data->pick_from . ' - ' . $data->drop_to;
                })
                ->addColumn('driver_name', function ($data) {
                    $d_name = !empty($data->Driver->name) ? $data->Driver->name : '';
                    $d_phone = !empty($data->Driver->mobile) ? $data->Driver->mobile : '';
                    return $d_name . '<br>' . $d_phone;
                })
                ->addColumn('status', function ($data) {
                    $jstatus = json_decode($data->Status->data_values);
                    $status = '<span class="badge" style="color:#fff; background-color:' . $jstatus->color . '">' . $jstatus->name . '</span>';
                    return $status;
                })
                ->addColumn('created_at', function ($data) {
                    return date('d-m-Y H:i:s', strtotime($data->created_at));
                })
                ->addColumn('action', function ($data) {
                    //$role =  Auth::user()->role->slug . '.vms.show';
                    $button = '<a href="' . route('vms.show', $data->generate_id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>';
                    return $button;
                })
                ->rawColumns(['pick_drop_time', 'driver_name', 'status', 'action'])
                ->toJson();
        }
        return view('backend.admin.vms.manage-requisition');
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
        //
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
        //
    }
}
