<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VehicleRequisition;
use App\Models\VmsActivity;
use App\Models\VmsApprovalPath;
use App\Models\VmsTeamUser;
use App\Models\VmsVehicle;
use App\Notifications\RequisitionEmailNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
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

            $query = VehicleRequisition::where('user_id', Auth::user()->id)
                ->get();

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
                ->addColumn('release_time', function ($data) {
                    $rleaseTime = !empty($data->release_time) ? $data->release_time : '<a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom releaseModal">Release</a>';
                    return  $rleaseTime;
                })
                ->addColumn('status', function ($data) {
                    $jstatus = json_decode($data->Status->data_values);
                    $status = '<span class="badge" style="color:#fff; background-color:' . $jstatus->color . '">' . $jstatus->name . '</span>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    //$role =  Auth::user()->role->slug . '.vms.show';
                    $button = '<a href="' . route('vms.show', $data->generate_id) . '" class="btn btn-light btn-sm btn-sm-custom">View</a>';
                    return $button;
                })
                ->rawColumns(['release_time','pick_drop_time', 'driver_name', 'status', 'action'])
                ->toJson();
        }
        return view('backend.common.vms.v-requisition');
        // return view('backend.common.vms.v-requisition', ['requitions' => $requitions]);
    }
    

    public function requisitionByStage($stage=null){

        $getStatge = VmsApprovalPath::where('user_id', Auth::user()->id)
                    ->where('stage', $stage)
                    ->select('stage')
                    ->first();

        if(empty($getStatge)){
            return response()->json(['errors' => [0 => 'Permission denied.']]);
            //return redirect()->back()->with('error', 'Access denied: You have not any approval requisition');
        }

        if (request()->ajax()) {

            if ($getStatge) {

                $requitions = $this->getRequisitions($getStatge->stage);

                return DataTables::of($requitions)
                    ->addIndexColumn()
                    ->addColumn('requister', function ($data) {
                        return $data->user->name;
                    })
                    ->addColumn('pick_drop_time', function ($data) {
                        return $data->pick_time . '<br>' . $data->drop_time;
                    })
                    ->addColumn('release_time', function ($data) {
                        $rleaseTime = !empty($data->release_time) ? $data->release_time : '<a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom releaseModal">Add</a>';
                        return  $rleaseTime;
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
                        $is_approval = VmsActivity::where('data_id',$data->id)->where('activity', 3)->first();
                        $btn_approval="";
                        if(!$is_approval){
                            $btn_approval = '<a href="javascript:void(0)"  data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom approveModalSubmit">Approval</a>';
                        }else{
                            $btn_approval="";
                        }
                        $button = '<div class="d-flex">'.$btn_approval.' 
                                <a class="btn btn-light btn-sm btn-sm-custom ms-1" href="' . route('vms.show', $data->generate_id) . '" >View</a>
                                <a href="javascript:void(0)"  data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom ms-1">Edit</a> </div>';
                        return $button;
                    })
                    ->rawColumns(['pick_drop_time', 'release_time', 'driver_name', 'status', 'created_at', 'action',])
                    ->toJson();
            }
        }

        return view('backend.common.vms.stage-requisition', ['stage'=>$stage]);

    }

    /*public function approveByDepartment()
    {

        if (request()->ajax()) {

            $getStatge = VmsApprovalPath::where('user_id', Auth::user()->id)
                ->where('stage', 1)
                ->select('stage')
                ->first();

            if ($getStatge) {

                $requitions = $this->getRequisitions($getStatge->stage);

                return DataTables::of($requitions)
                    ->addIndexColumn()
                    ->addColumn('requister', function ($data) {
                        return $data->user->name;
                    })
                    ->addColumn('pick_drop_time', function ($data) {
                        return $data->pick_time . '<br>' . $data->drop_time;
                    })
                    ->addColumn('release_time', function ($data) {
                        $rleaseTime = !empty($data->release_time) ? $data->release_time : '<a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom releaseModal">Add</a>';
                        return  $rleaseTime;
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
                        $button = '<div class="d-flex">
                                <a href="javascript:void(0)"  data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom approveModalSubmit">Approval</a> 
                                <a class="btn btn-light btn-sm btn-sm-custom ms-1" href="' . route('vms.show', $data->generate_id) . '" >View</a>
                                <a href="javascript:void(0)"  data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom ms-1">Edit</a> </div>';
                        return $button;
                    })
                    ->rawColumns(['pick_drop_time', 'release_time', 'driver_name', 'status', 'created_at', 'action',])
                    ->toJson();
            }
        }

        return view('backend.common.vms.department-requisition');
    }

    public function newApprove()
    {

        if (request()->ajax()) {

            $getStatge = VmsApprovalPath::where('user_id', Auth::user()->id)
                ->whereNotIn('stage', [1])
                ->select('stage')
                ->first();

            if ($getStatge) {

                $requitions = $this->getRequisitions($getStatge->stage);

                return DataTables::of($requitions)
                    ->addIndexColumn()
                    ->addColumn('requister', function ($data) {
                        return $data->user->name;
                    })
                    ->addColumn('pick_drop_time', function ($data) {
                        return $data->pick_time . '<br>' . $data->drop_time;
                    })
                    ->addColumn('release_time', function ($data) {
                        $rleaseTime = !empty($data->release_time) ? $data->release_time : '<a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom releaseModal">Add</a>';
                        return  $rleaseTime;
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
                        $button = '<div class="d-flex">
                                <a href="javascript:void(0)"  data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom approveModalSubmit">Approval</a> 
                                <a class="btn btn-light btn-sm btn-sm-custom ms-1" href="' . route('vms.show', $data->generate_id) . '" >View</a>
                                <a href="javascript:void(0)"  data-id="' . $data->id . '" class="btn btn-light btn-sm btn-sm-custom ms-1">Edit</a> </div>';
                        return $button;
                    })
                    ->rawColumns(['pick_drop_time', 'release_time', 'driver_name', 'status', 'created_at', 'action',])
                    ->toJson();
            }
        }
        return view('backend.common.vms.manage-requisition');

    } */

    // function for vehicle requsitions
    public function getRequisitions($stage = null)
    {
        if ($stage === 1) :

            $getApprovalPath = $this->checkApprovalPath($stage);

            if ($getApprovalPath->team_id) {
                
                $usersTeam = VmsApprovalPath::where('user_id', Auth::user()->id)
                    ->pluck('team_id');

                $usersInTeam = VmsTeamUser::whereIn('team_id', $usersTeam)
                    ->pluck('user_id');

                $requitions = VehicleRequisition::whereIn('user_id', $usersInTeam)
                            ->where('is_team', 1)
                            ->orderBy('created_at', 'DESC')
                            ->get();
                return $requitions;

            } else {

                $usersDepartments = VmsApprovalPath::where('user_id', Auth::user()->id)
                                    ->where('stage',1)
                                    ->pluck('dept_id');

                $usersInDepartment = User::whereIn('department_id', $usersDepartments)
                                    ->pluck('id');

                $requitions = VehicleRequisition::whereIn('user_id', $usersInDepartment)
                            ->orderBy('created_at', 'DESC')
                            ->get();

                return $requitions;
            }
        else :
            $requitions = VehicleRequisition::where('stage', $stage)
                ->orderBy('created_at', 'DESC')
                ->get();
            return $requitions;
        endif;
    }

    public function checkApprovalPath($stage=null){
        $stage_path = VmsApprovalPath::where('user_id', Auth::user()->id)
                    ->where('stage', $stage)
                    ->first();
        if($stage_path ){
            return $stage_path;
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
    public function store(Request $request)
    {
        // team head setting
        $getDeptAssigner = VmsApprovalPath::where('dept_id', Auth::user()->department_id)
            ->where('stage', 1)
            ->first();

        $getTeamId = $this->getTeamId(); // check if user exist in team or not

        if (empty($getDeptAssigner->dept_id) && $getTeamId == false) {
            return response()->json(['errors' => [0 => 'Team Lead has not set yet.']]);
        }
        // end team head settings

        $validator = Validator::make($request->all(), [
            'is_dhaka'     => 'required',
            'pick_from'    => 'required',
            'drop_to'      => 'required',
            'pick_time'    => 'required',
            'drop_time'    => 'required',
            'description'  => 'required',
            'no_person'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $generateReqId = $this->generateRequisitionId();

        $vrequistion                  = new VehicleRequisition;
        $vrequistion->generate_id     = (int)$generateReqId;
        $vrequistion->is_dhaka        = $request->is_dhaka;
        $vrequistion->pick_from       = $request->pick_from;
        $vrequistion->drop_to         = $request->drop_to;
        $vrequistion->pick_time       = $request->pick_time;
        $vrequistion->drop_time       = $request->drop_time;
        $vrequistion->traveler_no     = $request->no_person;
        $vrequistion->description     = $request->description;
        $vrequistion->is_team         = $request->is_team;
        $vrequistion->user_id         = Auth::user()->id;
        $vrequistion->company_id      = Auth::user()->company_id;
        $vrequistion->save();

        if ($getTeamId && $request->is_team == 1) :

            $notifyHead  =  VmsApprovalPath::where([['navana_vms.vms_approval_paths.team_id', $getTeamId], ['navana_vms.vms_approval_paths.stage', 1]])
                ->get();
        else :
            $notifyHead  =  VmsApprovalPath::where([['navana_vms.vms_approval_paths.dept_id', Auth::user()->department_id], ['navana_vms.vms_approval_paths.stage', 1]])
                ->get();
        endif;
        
        $btnUrl = '<a style="background-color: #008CBA;border: none; color: white;padding: 15px 32px; text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px; border-radius: 5px" href="'.route('vms.requisition.stage', 1).'">View</a>';

        $req_message = 'Requisition#' . $generateReqId . ' Created. <br> <strong>Raise From: </strong>' . getUserName(Auth::user()->id) . '<br><strong>Requisition Date: </strong>' . $vrequistion->created_at . '<br><strong>Required Time: </strong>' . $vrequistion->pick_time . '-' . $vrequistion->drop_time . '<br><strong>Details <br>Pick & Drop: </strong>' . $vrequistion->pick_from . '-' . $vrequistion->drop_to . '.<br><br>'.$btnUrl;

        
        if (count($notifyHead) > 0) :

            foreach ($notifyHead as $user) {
                $this->sendNotification($user->user_id, $req_message, $generateReqId);
            }
        endif;

        return response()->json(['success' => 'Your Requistion has been submitted successfully']);
    }


    public function getTeamId()
    {

        $team_id = VmsTeamUser::where('user_id', Auth::user()->id)->first();

        if ($team_id) :
            return  $team_id->team_id;
        else :
            return  false;
        endif;
    }

    // function for notification
    public function sendNotification($notify_user_id, $message,  $requisition_id)
    {
        $user = User::find($notify_user_id);

        Notification::send($user, new RequisitionEmailNotification($notify_user_id, $message, $requisition_id));
    }

    // function for generate requisition Id
    public function generateRequisitionId()
    {
        $vrequistion_last_id  = VehicleRequisition::latest('id')->first();
        $new_id = '';
        if ($vrequistion_last_id) :
            $new_id = $vrequistion_last_id->id + 1;
        else :
            $new_id = 1;
        endif;
        $date = Carbon::now();
        $generate_id = date('ymd', strtotime($date)) . $new_id;
        return  $generate_id;
        // $generate_requistion_id  = Auth::user()->company_id . random_int(10000000, 99999999) . $req_id ;
        // return $generate_requistion_id;
    }

    // 

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleRequisition  $vehicleRequisition
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requisition = VehicleRequisition::select('vehicle_requisitions.*', 'users.department_id', 'users.name as username', 'users.email','users.phone')
                      ->leftJoin('navana_portal.users', 'users.id','vehicle_requisitions.user_id')
                       ->where('generate_id', $id)
                       ->first();
        
        $dept_head= VmsApprovalPath::leftJoin('navana_portal.users', 'vms_approval_paths.user_id', 'users.id')
                        ->where('dept_id', $requisition->department_id)
                        ->groupBy('stage')
                        ->orderBy('stage','ASC')
                        ->get();

        return view('backend.common.vms.v-show', ['requisition' => $requisition]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VehicleRequisition  $vehicleRequisition
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vRequisition = VehicleRequisition::find($id);

        $checkUserLastStage = VmsApprovalPath::where('user_id', Auth::user()->id)->first();

        $dept_manager = $this->getTeamId();
        
        if ($dept_manager) :
            $available_vehicle = VmsVehicle::where('assign_team', $dept_manager)->get();
        else :
            $available_vehicle = VmsVehicle::all();
        endif;

        $vh_data = '<option disabled selected> Select Vehicle </option>';
        foreach ($available_vehicle as $vehicle) {
            $vh_data .= '<option value="' . $vehicle->id . '">' . $vehicle->vehicle_no . '</option>';
        }

        $form_field = '';
        $last_stage = $this->getFinalStage();
        if ($vRequisition->is_team == 1 ||  $last_stage == $checkUserLastStage->stage) :
            $form_field .= '<div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Assign Vehicle</label>
                                    <select class="liveVehicleSearch w-100 form-select" id="vehicle" name="vehicle" data-width="100%">' . $vh_data . '</select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Assign Driver</label>
                                <select class="liveDriverSearch w-100 form-select" id="driverId" name="driverId" data-width="100%">
                                </select>
                            </div>
                        </div>';
        else :
            $form_field = '';
        endif;

        return response()->json(['vRequisition' => $vRequisition, 'form_field' => $form_field]);
    }

    /**
     * Approve the form for editing the specified resource.
     *
     * @param  \App\Models\VehicleRequisition  $vehicleRequisition
     * @return \Illuminate\Http\Response
     */

    public function approveStore(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'is_approve'        => 'required',
            'requisition_id'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = [
            'subject'         => 'stage',
            'activity'        => $request->stage,
            'data_id'         => (int)$request->requisition_id,
            'user_id'         => Auth::user()->id,
            'added_on'        => Carbon::now(),
        ];

        $existing_stage = VmsActivity::where([['data_id', '=', $request->requisition_id], ['activity', '=', $request->stage]])
            ->first();

        if (is_null($existing_stage)) {

            if ($request->is_approve == 1) :

                $check_stage = $this->checkUserStage($request->stage);

                if ($check_stage) :

                    $last_stage = $this->getFinalStage();

                    if ($request->stage == $last_stage) :

                        if ($request->vehicle && $request->driverId) :

                            // check vehicle abailablitity
                            $isavailAbleVehicle = $this->checkVehicleAvailable($request->requisition_id, $request->vehicle);

                            if ($isavailAbleVehicle < 1) :
                                VehicleRequisition::where('id', (int)$request->requisition_id)
                                    ->update(['stage' => $last_stage, 'vehicle_id' => $request->vehicle, 'driver_id' => $request->driverId, 'status' => 3]);
                                VmsActivity::create($data);
                                $this->stageSendNotification($request->requisition_id, $last_stage); // send email notification
                            else :
                                return response()->json(['errors' => [0 => 'This Vehicle is not available in this time. Please select another vehicle']]);
                            endif;

                        else :
                            return response()->json(['errors' => [0 => 'Please select vehicle and driver']]);
                        endif;

                    else :

                        $checkTeamManager = $this->isTeamManager($request->stage); // check if exist team manager from approval path table

                        if ($checkTeamManager == false && $request->is_team != 1) :

                            $next_stage = $request->stage + 1;

                            if ($this->checkOutSideDhaka($request->requisition_id) == TRUE && $request->stage == 2) :
                                if (Auth::user()->id == 960) {
                                    VehicleRequisition::where('id', (int)$request->requisition_id)->update(['stage' => $next_stage, 'status' => 2]);
                                    VmsActivity::create($data);
                                    $this->stageSendNotification($request->requisition_id, $next_stage);
                                } else {
                                    return response()->json(['errors' => [0 => 'You haven not permission ']]);
                                };
                            else :
                                VehicleRequisition::where('id', (int)$request->requisition_id)
                                    ->update(['stage' => $next_stage, 'status' => 2]);
                                VmsActivity::create($data);
                                $this->stageSendNotification($request->requisition_id, $next_stage); // send email notification
                            endif;

                        else :
                            if ($request->vehicle && $request->driverId) :

                                $isavailAbleVehicle = $this->checkVehicleAvailable($request->requisition_id, $request->vehicle);

                                if ($isavailAbleVehicle < 1) :
                                    
                                    $checkTeamManager = $this->isTeamManager($request->stage);
                                    if($checkTeamManager==false){
                                        return response()->json(['errors' => [0 => 'You haven not permission ']]);
                                    }

                                    VehicleRequisition::where('id', (int)$request->requisition_id)
                                        ->update(['stage' => $last_stage, 'vehicle_id' => $request->vehicle, 'driver_id' => $request->driverId, 'status' => 3]);

                                    VmsActivity::create($data);

                                    $this->stageSendNotification($request->requisition_id, 3); // send email notification to Transport

                                    // notification to Team Head
                                    if ($checkTeamManager->notify_uid) :
                                        $this->sendNotificationToTeamHead($checkTeamManager->notify_uid, (int)$request->requisition_id);
                                    endif;
                                // end Notification to team head

                                else :
                                    return response()->json(['errors' => [0 => 'This Vehicle is not available in this time. Please select another vehicle']]);
                                endif;

                            else :
                                return response()->json(['errors' => [0 => 'Please select Driver or vehicle']]);
                            endif;
                        endif;

                    endif;

                    return response()->json(['success' => 'You have approved successfully']);

                else :
                    return response()->json(['errors' => [0 => 'You haven not permission to update.']]);
                endif;

            else :

                $next_stage = $request->stage;

                $data['ext_data'] = $request->reason;

                VehicleRequisition::where('id', (int)$request->requisition_id)
                    ->update(['stage' => $next_stage, 'status' => 4]);

                VmsActivity::create($data);

                $this->stageSendNotification($request->requisition_id, $next_stage); // send email notification

                return response()->json(['success' => 'You have not approved successfully']);

            endif;
        } else {
            return response()->json(['errors' => [0 => 'Already someone have changed.']]);
        }
    }

    public function checkVehicleAvailable($requ_id, $vehicle)
    {

        $data_vreq = VehicleRequisition::where('id', (int)$requ_id)->first();

        $pic_time = $data_vreq->pick_time;
        $drop_time = $data_vreq->drop_time;

        // $query = VehicleRequisition::where('requi_date', $data_vreq->requi_date)
        //         ->where('vehicle_id', $vehicle)
        //         ->where(function ($query) use ($pic_time, $drop_time) {
        //             $query->whereBetween('pick_time', [$pic_time, $drop_time])
        //                 ->orWhereBetween('drop_time', [$pic_time, $drop_time]);
        //         })->count();

        $query = VehicleRequisition::where('vehicle_id', $vehicle)
            ->where(function ($query) use ($pic_time, $drop_time) {
                $query->whereBetween('pick_time', [$pic_time, $drop_time])
                    ->orWhereBetween('drop_time', [$pic_time, $drop_time]);
            })->count();
        return  $query;
    }

    public function checkOutSideDhaka($req_id)
    {

        $requistion_data = VehicleRequisition::where('id', $req_id)->where('is_dhaka', '1')->first();

        if ($requistion_data) :
            return TRUE;
        else :
            return FALSE;
        endif;
    }

    public function stageSendNotification($requisition_id, $next_stage)
    {

        $requistion_data = VehicleRequisition::where('vehicle_requisitions.id', $requisition_id)
            ->leftJoin('vms_drivers', 'vehicle_requisitions.driver_id', 'vms_drivers.id')
            ->select('vehicle_requisitions.*', 'vms_drivers.name as driver_name', 'vms_drivers.mobile as driver_mobile')
            ->first();

        $req_message = 'Requisition#' . $requistion_data->generate_id . ' . <br> <strong>Approved By: </strong>' . getUserName(Auth::user()->id) . '<br><br><strong>Requisition Details:<br> Raised By: </strong>' . getUserName($requistion_data->user_id) . '<br><strong>Date: </strong>' . $requistion_data->created_at . '<br><strong>Required Time: </strong>' . $requistion_data->pick_time . '-' . $requistion_data->drop_time . '<br><strong>Pick & Drop: </strong>' . $requistion_data->pick_from . '-' . $requistion_data->drop_to . '.';

        $notifyUsers = VmsApprovalPath::where('stage', $next_stage)->select('user_id')->get();

        if ($requistion_data->driver_id) :

            $req_message .= '<br/>Driver Details:<br><strong>Name: </strong>' . $requistion_data->driver_name . '<br/><strong>Mobile: </strong>' . $requistion_data->driver_mobile;

            $this->sendNotification($requistion_data->user_id, $req_message, $requistion_data->generate_id);

        else :
            if (count($notifyUsers) > 0) :

                foreach ($notifyUsers as $user) {
                    $this->sendNotification($user->user_id, $req_message, $requistion_data->generate_id);
                }
            endif;
        endif;
    }

    public function sendNotificationToTeamHead($notify_uid, $reqId)
    {
        $requistion_data = VehicleRequisition::where('vehicle_requisitions.id', $reqId)
            ->leftJoin('vms_drivers', 'vehicle_requisitions.driver_id', 'vms_drivers.id')
            ->select('vehicle_requisitions.*', 'vms_drivers.name as driver_name', 'vms_drivers.mobile as driver_mobile')
            ->first();

        $req_message = 'Requisition#' . $requistion_data->generate_id . ' . <br> <strong>Approved By: </strong>' . getUserName(Auth::user()->id) . '<br><br><strong>Requisition Details:<br> Raised By: </strong>' . getUserName($requistion_data->user_id) . '<br><strong>Date: </strong>' . $requistion_data->created_at . '<br><strong>Required Time: </strong>' . $requistion_data->pick_time . '-' . $requistion_data->drop_time . '<br><strong>Pick & Drop: </strong>' . $requistion_data->pick_from . '-' . $requistion_data->drop_to . '.';


        $req_message .= '<br/>Driver Details:<br><strong>Name: </strong>' . $requistion_data->driver_name . '<br/><strong>Mobile: </strong>' . $requistion_data->driver_mobile;

        $this->sendNotification($notify_uid, $req_message, $reqId);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VehicleRequisition  $vehicleRequisition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleRequisition $vehicleRequisition)
    {
        //
    }


    public function openReleaseModal($id){
        $vRequisition = VehicleRequisition::find($id);
        return response()->json(['vRequisition' => $vRequisition]);
    }

    public function requisitionRelease(Request $request)
    {   

        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'release_time' => 'required',
            'req_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $req_id = (int)$request->req_id;

        $requition_data = VehicleRequisition::where('id', $req_id)
                            ->first();

        $lastStatge = $this->getFinalStage();

        if(!empty($requition_data->driver_id)):

            if($this->getUserStage() == $lastStatge || $requition_data->user_id == Auth::user()->id):
                VehicleRequisition::where('id', $req_id)
                ->update(['release_time' => Carbon::now(), 'status' => 5]);

                return response()->json(['success' => 'You have updated successfully']);
            else:
                return response()->json(['errors' => [0 => 'You have not permission to release']]);
            endif;

        else:
            return response()->json(['errors' => [0 => 'Release not possible at this moment']]);
        endif;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleRequisition  $vehicleRequisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleRequisition $vehicleRequisition)
    {
        //
    }

    public function isTeamManager($stage)
    {
        $data = VmsApprovalPath::where('user_id', Auth::user()->id)
            ->where('stage', $stage)
            ->first();
            
        if (!empty($data->team_id)) {
            return $data;
        } else {
            return false;
        }
    }

    public function checkUserStage($stage)
    {
        $getStatge = VmsApprovalPath::where('user_id', Auth::user()->id)
            ->where('stage', $stage)
            ->first();
            
        if ($getStatge) {
            return true;
        } else {
            return false;
        }
    }

    public function getFinalStage()
    {
        $lastStage = VmsApprovalPath::max('stage');
        return $lastStage;
    }

    public function getUserStage()
    {
        $getStatge = VmsApprovalPath::where('user_id', Auth::user()->id)
            ->select('stage')
            ->first();
        if(!empty($getStatge->stage)){
            return $getStatge->stage;
        }else{
            return FALSE;
        }
        
    }

}
