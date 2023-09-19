<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\PrTaskDuration;
use App\Models\PrTaskStatus;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //    $permission = Permission::where([['user_id','=', Auth::user()->id],['module_id','=', 1],])->first();
        //     if(!empty($permission->module_id) != 1){
        //         return back()->with('no permission');
        //     }
        // });

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $projects = Project::select('name', 'pr_task_durations.project_id', 'location','projects.created_at')
        //             ->leftJoin('pr_task_durations', 'projects.id', '=', 'pr_task_durations.project_id')
        //             ->where('department_id', '=', Auth::user()->department_id)
        //             ->groupBy('pr_task_durations.project_id')
        //             ->orderBy('projects.id', 'DESC')
        //             ->get();
        // return view('backend.user.projects.index', ['projects' => $projects]);

        $projects = Project::orderBy('id', 'DESC')->get();
        return view('backend.user.projects.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.user.projects.create');
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
            'name'          => 'required|max:150',
            'location'      => 'max:200',
            'project_type'  => 'required',
            'area_stories'  => 'required',
        ]);            
        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $project = new Project;
        $project->name          = $request->name;
        $project->slug          = Str::slug($request->name);
        $project->location      = $request->location;
        $project->project_type  = $request->project_type;
        $project->area_stories  = $request->area_stories;
        $project->user_id       =  Auth::id();
        $project->save();

        return response()->json(['success'=>'Data is successfully added']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return view('backend.user.projects.single');
        $project = Project::where('id', $id)->cursor()->first();
        $tasks = PrTaskDuration::where('project_id', $id)->get();
        return view('backend.user.projects.single', ['project'=>$project, 'tasks'=>$tasks]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $project = Project::where('id', $id)->cursor()->first();
        // $tasks = PrTaskDuration::where([['project_id','=',$id],['department_id','=', Auth::user()->department_id],])->get();
        // return view('backend.user.projects.edit', ['project'=>$project, 'tasks'=>$tasks]);

        $project = Project::where('id', $id)->cursor()->first();

        $getTaskPerm = Permission::where([['user_id', '=',Auth::user()->id],['module_id', '=', 1]])->first();

        
        if(!empty($getTaskPerm->create) && $getTaskPerm->create == 1 && $getTaskPerm->approval == 1){
            
            $tasks = PrTaskDuration::where('project_id', $id)->get();
        }else{
        
            $tasks = PrTaskDuration::where([['project_id','=',$id],['department_id','=', Auth::user()->department_id],])->get();
        }
        //dd($getTaskPerm);
        
        return view('backend.user.projects.edit', ['project'=>$project, 'tasks'=>$tasks, 'taskPerm' => $getTaskPerm]);
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

    public function changeStatus(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'task_id'       => 'integer|required',
            'project_id'    => 'integer|required',
            'status'        => 'required',
        ]);            
        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $status                      = new PrTaskStatus;
        $status->user_id             = Auth::id();
        $status->department_id       = Auth::user()->department_id;
        $status->task_duration_id    = $request->task_id;
        $status->project_id          = $request->project_id;
        $status->status              = $request->status;
        $status->save();

        if($status){
            PrTaskDuration::where('id', $status->task_duration_id)
            ->update(['status' => $request->status]);
        }

        return response()->json(['success'=>'Data is successfully added', 'status'=> $status->status]);

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
