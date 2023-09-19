<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveHierarchy;
use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        // $query = "SELECT companies.slug as company_name, employees.employee_id, employees.employee_name, employees.email, employees.mobile, employees.company_id, employees.department_id, 
        //             (SELECT designations.name from designations WHERE designations.id = employees.designation_id AND designations.company_id = employees.company_id) as designations_name,
        //             (SELECT departments.name from departments WHERE departments.id = employees.department_id AND departments.company_id = employees.company_id) as department_name,
        //             employees.joining_date, employees.gross, employees.employee_status, users.id as user_id
        //         FROM employees
        //         LEFT OUTER JOIN users
        //             ON employees.employee_id = users.emp_id
        //         LEFT OUTER JOIN companies
        //             ON employees.company_id = companies.comp_id
        //         WHERE employees.employee_status NOT IN ('E', 'RG', 'T', 'D', 'A')
        //         ORDER BY employees.company_id ASC";
        // $employees = DB::select($query);

        // dd($employees);
        
        return view('backend.admin.employee.emp-list');
    }

    public function jsonEmployeeData(Request $request){
        
        if(!empty($request->department_id)){
            $query = "SELECT companies.slug as company_name, employees.employee_id, employees.employee_name, employees.email, employees.mobile, employees.company_id, employees.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = employees.designation_id AND designations.company_id = employees.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = employees.department_id AND departments.company_id = employees.company_id) as department_name,
                    employees.joining_date, employees.gross, employees.employee_status, users.id as user_id
                FROM employees
                LEFT OUTER JOIN users
                    ON employees.employee_id = users.emp_id
                LEFT OUTER JOIN companies
                    ON employees.company_id = companies.comp_id
                WHERE employees.employee_status NOT IN ('E', 'RG', 'T', 'D', 'A')
                AND employees.department_id = ".$request->department_id."
                AND employees.company_id =".$request->company_id." 
                ORDER BY employees.company_id ASC";
        }else{
            $query = "SELECT companies.slug as company_name, employees.employee_id, employees.employee_name, employees.email, employees.mobile, employees.company_id, employees.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = employees.designation_id AND designations.company_id = employees.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = employees.department_id AND departments.company_id = employees.company_id) as department_name,
                    employees.joining_date, employees.gross, employees.employee_status, users.id as user_id
                FROM employees
                LEFT OUTER JOIN users
                    ON employees.employee_id = users.emp_id
                LEFT OUTER JOIN companies
                    ON employees.company_id = companies.comp_id
                WHERE employees.employee_status NOT IN ('E', 'RG', 'T', 'D', 'A')
                ORDER BY employees.company_id ASC";
        }
        

        $employees = DB::select($query);

    
        return Datatables::of($employees)
                            ->addIndexColumn()
                            ->addColumn('employee_name', function($data){
                                return '<a href="#" class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <img src="'.asset('uploads/img/').'/'.$data->employee_id.'.jpg'.'" class="rounded-circle wd-35" alt="user">
                                            </div>
                                            <div class="w-100">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="text-body mb-2">'.$data->employee_name.'</h6>
                                            </div>
                                            <p class="text-muted tx-13">'.$data->designations_name.'</p>
                                            </div>
                                        </a>';
                            })
                            ->addColumn('action', function($data){
                                $button = '<a href="'.route('admin.user.show',$data->user_id).'" class="btn btn-light align-items-center btn-sm btn-sm-custom">View</a>';
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
