<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\PrTaskDuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PrTaskDurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('backend.pnc.tasks.index');
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
            'department'    => 'required',
            'task'          => 'max:200',
            'duration'      => 'required',
            'project_id'    => 'required',
        ]);            
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
           // return response()->json(['errors' => $validator->errors()->all()]);
        }

        $prTaskDuration     = new PrTaskDuration;

        $prTaskDuration->department_id  = $request->department;
        $prTaskDuration->task_id        = $request->task;
        $prTaskDuration->duration       = $request->duration;
        $prTaskDuration->project_id     = $request->project_id;
        $prTaskDuration->user_id        =  Auth::id();
        $prTaskDuration->save();

        return redirect()->back()->with('success', 'Data is successfully added');

        //return response()->json(['success'=>'Data is successfully added']);
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
