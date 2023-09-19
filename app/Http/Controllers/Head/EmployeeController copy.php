<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\LeaveHierarchy;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$employees = User::join('employees', 'users.emp_id', 'employees.employee_id')->join('designations','designations.designation_id','employees.designation_id')
                    ->select('users.*','employees.designation_id', 'employees.joining_date', 'designations.name as designation_name')
                    ->where('users.company_id',Auth::user()->company_id)->get(); */

        // $query = "SELECT companies.slug as company_name, users.emp_id as employee_id, users.name, users.email, employees.mobile, users.company_id, users.department_id, 
        //             (SELECT designations.name from designations WHERE designations.id = users.designation_id) as desigantion_name,
        //             (SELECT departments.name from departments WHERE departments.id = users.department_id) as department_name,
        //             employees.joining_date, employees.gross, employees.employee_status, users.id as user_id
        //                 FROM users
        //                 LEFT OUTER JOIN employees
        //                     ON users.emp_id = employees.employee_id
        //                 LEFT OUTER JOIN companies
        //                     ON users.company_id = companies.comp_id
        //                 ORDER BY users.company_id ASC";
        
        // $users = DB::select($query);  
        

        return view('backend.head.employee.index');

    }

    public function jsonUsers(){

        $query = "SELECT companies.slug as company_name, users.emp_id as employee_id, users.name, users.email, employees.mobile, users.company_id, users.department_id, users.image, 
            (SELECT designations.name from designations WHERE designations.id = users.designation_id) as designations_name,
            (SELECT departments.short_name from departments WHERE departments.id = users.department_id) as department_name,
            employees.joining_date, employees.gross, employees.employee_status, users.id as user_id
                FROM users
                LEFT OUTER JOIN employees
                    ON users.emp_id = employees.employee_id
                LEFT OUTER JOIN companies
                    ON users.company_id = companies.comp_id
                WHERE users.emp_id NOT IN ('NULL')
                    AND users.department_id = ".Auth::user()->department_id."
                ORDER BY users.company_id ASC";

        $users = DB::select($query);

        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('employee_name', function($data){
            return '<a href="'.route('head.employee.show',$data->user_id).'" class="d-flex align-items-center">
                        <div class="mr-3">
                            <img src="'.asset('uploads/img/').'/'.$data->image.'" class="rounded-circle wd-35" alt="user">
                        </div>
                        <div class="w-100">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-body mb-2">'.$data->name.'</h6>
                        </div>
                        <p class="text-muted tx-13">'.$data->designations_name.'</p>
                        </div>
                    </a>';
        })
        ->addColumn('action', function($data){
            $button = '<button type="button" class="btn btn-primary btn-sm border-radius-0"><i class="fas fa-eye"></i> View</button>';
            /*$button .= '&nbsp;';*/
            return "<div class='btn-group btn-group-sm' role='group' aria-label='View'>".$button."</div>";
        })
        ->rawColumns(['employee_name','action'])
        ->toJson();
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


    public function hierarchyStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'leave_level_id' => 'required',
            'department'     => 'max:200',
            'emp_id'         => 'required',
            'hierarchy'      => 'required',
            'user_id'        =>'required',
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = [
            'user_id'             => $request->user_id,
            'approval_user_id'    => $request->emp_id,
            'hierarchie'          => $request->hierarchy,
            'leave_level_id'      => $request->leave_level_id,
        ];

        LeaveHierarchy::updateOrInsert(
            ['user_id' =>  $request->user_id, 'leave_level_id' => $request->leave_level_id], $data
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
        $employee = User::where('id', $id)->first();
        $leavehierarchies = LeaveHierarchy::where('user_id', $id)->orderBy('hierarchie')->get();
        return view('backend.head.employee.show', compact('employee', 'leavehierarchies'));
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
