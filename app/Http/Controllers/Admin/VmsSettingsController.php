<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VmsApprovalPath;
use App\Models\VmsTeamUser;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class VmsSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $teamLeads = VmsApprovalPath::select('vms_approval_paths.*', DB::raw("(SELECT vms_look_ups.data_values FROM vms_look_ups WHERE vms_approval_paths.team_id = vms_look_ups.data_keys AND vms_look_ups.data_type ='req.team') AS team_name"), DB::raw("(SELECT users.name FROM navana_portal.users WHERE vms_approval_paths.notify_uid = users.id) AS team_head"), 'navana_portal.users.name as username')
            ->leftJoin('navana_portal.users', 'users.id', 'vms_approval_paths.user_id')
            ->where('vms_approval_paths.stage', 1)
            ->whereNotNull('vms_approval_paths.team_id')
            ->orderBy('vms_approval_paths.team_id', 'asc')
            ->get();


        $dep_head = VmsApprovalPath::select('departments.name as dept_name', 'vms_approval_paths.*', DB::raw('count(vms_approval_paths.id) as total'), 'navana_portal.companies.name as company_name')
            ->leftJoin('navana_portal.departments', 'departments.id', 'vms_approval_paths.dept_id')
            ->leftJoin('navana_portal.companies', 'companies.comp_id', 'vms_approval_paths.company_id')
            //->leftJoin('navana_portal.users', 'users.id', 'vms_approval_paths.user_id')
            ->whereNotNull('vms_approval_paths.dept_id')
            ->where('vms_approval_paths.stage', 1)
            ->orderBy('company_id', 'asc')
            ->groupBy('vms_approval_paths.dept_id')
            ->get();

        //dd($dep_head);

        $stage_2 = VmsApprovalPath::select('navana_portal.users.name as username', 'navana_portal.departments.name as dept_name', 'vms_approval_paths.*')
            ->leftJoin('navana_portal.users', 'users.id', 'vms_approval_paths.user_id')
            ->leftJoin('navana_portal.departments', 'departments.id', 'navana_portal.users.department_id')
            ->where('vms_approval_paths.stage', 2)
            ->orderBy('username', 'asc')
            ->get();

        $stage_3 = VmsApprovalPath::select('navana_portal.users.name as username', 'navana_portal.departments.name as dept_name', 'vms_approval_paths.*')
            ->leftJoin('navana_portal.users', 'users.id', 'vms_approval_paths.user_id')
            ->leftJoin('navana_portal.departments', 'departments.id', 'navana_portal.users.department_id')
            ->where('vms_approval_paths.stage', 3)
            ->orderBy('username', 'asc')
            ->get();
        
        return view('backend.admin.vms.settings', ['dep_heads' => $dep_head, 'stage_2'=>$stage_2, 'stage_3'=>$stage_3,'teamLeads'=>$teamLeads]);
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
        $validator =Validator::make($request->all(), [
            'stage' => 'required',
            'user_id' => 'required',
            'team' => 'required',
            'company' => 'required',
        ]);

        if ($validator->fails()){
            return Redirect::back()->withErrors($validator);
        }
       
        $data = array(
            'stage'        => $request->stage,
            'user_id'      => $request->user_id,
            'team_id'      => $request->team,
            'company_id'   => $request->company,
            'dept_id'      => $request->department,
            'notify_uid'   => $request->notify_user,
            'module_id'    => 14,
            'created_at'   => Carbon::now(),
            'updated_at'   =>Carbon::now(),
        );
    

        VmsApprovalPath::updateOrInsert(
            ['id' => $request->path_id], $data
        );

        return redirect()->back()->with('success', 'Data is successfully added');

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

    public function addTeamUser($id)
    {
        $data = VmsTeamUser::where('team_id', $id)->get();
        return response()->json($data);
    }


    public function storeTeamUser(Request $request){

        if (count((array)$request->user_id) > 0) :

            foreach ($request->user_id as $userId) {

                $isExistUser = $this->isExistUser((int)$userId, $request->team_id);
                if ($isExistUser == false) :
                    $event = new VmsTeamUser;
                    $event->user_id      = (int)$userId;
                    $event->team_id      = $request->team_id;
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

    public function isExistUser($user_id, $team_id)
    {
        $user = VmsTeamUser::where('user_id', $user_id)->where('team_id',$team_id)->first();
        if ($user) {
            return  $user->user_id;
        } else {
            return false;
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
        //
    }



}
