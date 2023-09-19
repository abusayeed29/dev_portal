<?php

namespace App\Http\Controllers;

use App\Models\SlaDepartment;
use App\Models\SlaTask;
use App\Models\SlaTaskCategory;
use App\Models\SlaTaskProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SlaTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = SlaTask::all();
        $departmentTask = SlaDepartment::all();
        return view('backend.head.sla.task', ['tasks' => $tasks, 'departmentTask'=>$departmentTask]);
    }

    public function slaTaskCateModal(Request $request)
    {

        $task_id = $request->task_id;
        $cat_id = $request->cat_id;
        $subcat_id = $request->subcat_id;

        if($request->subcat_id):
            $cat_id = $request->subcat_id;
        else:
            $cat_id = $request->cat_id;
        endif;

        $tsk_progess = SlaTaskProgress::where('task_id', $task_id)
            ->where('task_cat_id', $cat_id)
            ->first();

        $ischeck = "";
        $isdisable = "disabled";
        $c_ischeck = "";
        if (!empty($tsk_progess->started_at)) :
            $ischeck = 'checked';
            $isdisable = "";
        endif;

        if (!empty($tsk_progess->completed_at)) :
            $c_ischeck = 'checked';
        endif;

        $form ='<div class="form-check mb-2">
                    <input type="checkbox" name="started_date" class="form-check-input" id="started" '.$ischeck.'>
                    <label class="form-check-label" for="started">
                        Start
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input type="checkbox" name="completed_date" class="form-check-input" id="completed" '.$isdisable.' '.$c_ischeck.'>
                    <label class="form-check-label" for="completed">
                        Complete
                    </label>
                </div>';

        if(SlaTaskCategory::where('parent_id', $cat_id)->count() > 0 ):
            if($subcat_id):
                $output = $form;
            else:
                return response()->json(['output' => 'No need to update']);
            endif;
        else:
            $output =$form;
        endif;

        return response()->json(['output' => $output]);

    }

    public function slaTaskCateUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'started_date' => 'required',
            'task_id' => 'required',
            'cat_id' => 'required',
        ]); 
        if ($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if($request->subcat_id):
            $cat_id = $request->subcat_id;
        else:
            $cat_id = $request->cat_id;
        endif;

        if($request->started_date):
            $stated_at = Carbon::now();
        endif;
        
        if($request->completed_date):
            $completed_at = Carbon::now();
        endif;

        $existTask = SlaTaskProgress::where([['task_id','=',$request->task_id],['task_cat_id','=',$cat_id]])
                    ->first();

        if(empty($existTask->started_at)){
            $data = [
                'user_id'           => Auth::user()->id,
                'task_cat_id'       => $cat_id,
                'started_at'        => $stated_at,
                //'completed_at'      => !empty($completed_at) ? $completed_at : NULL,
                'task_id'           => $request->task_id,
                'status_percent'    => $request->started_date,
            ];
            $taskProgress = SlaTaskProgress::create($data);

            if($taskProgress->id):

                $is_tskstarted = SlaTask::where('sla_tasks.id', $taskProgress->task_id)
                                ->leftJoin('sla_task_categories','sla_tasks.cat_id', 'sla_task_categories.id')
                                ->select('sla_tasks.*', 'sla_task_categories.lead_time')
                                ->first();
                // get lead time for if sub parent
                if($is_tskstarted->parent_id):
                    $getLeadTime = SlaTaskCategory::where('id', $is_tskstarted->parent_id)->find('lead_time');
                else:
                    $getLeadTime = $is_tskstarted->lead_time;
                endif;   
                
                $ended_at = date('Y-m-d H:i:s', strtotime(Carbon::now(). ' + '.$getLeadTime.' hours'));

                if(empty($is_tskstarted->started_at)):
                    SlaTask::where('id', $taskProgress->task_id)
                            ->update(['started_at' => Carbon::now(), 'ended_at'=> $ended_at]);
                endif;
            endif;

            return response()->json(['success'=>'Your Data has been updated successfully -  started at']);

        }else{

            if(empty($existTask->completed_at) && !empty($completed_at)){

                SlaTaskProgress::where('task_id', $request->task_id)
                                ->where('task_cat_id', $cat_id)
                                ->update(['completed_at'      => !empty($completed_at) ? $completed_at : NULL]);
                return response()->json(['success'=>'Your Data has been updated successfully - comepeted at']);

            }else{
                return response()->json(['errors'=>[ 0 =>'Compelted field is required.']]);
            }

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
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = SlaTask::where('id', $id)->first();
        $subTasks = SlaTaskCategory::select('sla_task_categories.*', 'sla_task_progress.started_at', 'sla_task_progress.completed_at')
                    ->leftJoin('sla_task_progress', 'sla_task_categories.id', 'sla_task_progress.task_cat_id' )
                    ->where('parent_id', $task->cat_id)
                    ->get();
        return view('backend.head.sla.task-show',['task'=>$task, 'subTasks'=>$subTasks]);
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
