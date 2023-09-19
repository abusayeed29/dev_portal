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

class EmployeeController2 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        return view('backend.admin.employee.index');
    }

    public function jsonEmployee(Request $request){
        
        if(!empty($request->department_id)){
            $query = "SELECT companies.slug as company_name, employees.employee_id, employees.employee_name, employees.email, employees.mobile, employees.company_id, employees.department_id, 
                    (SELECT designations.name from designations WHERE designations.designation_id = employees.designation_id AND designations.company_id = employees.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.dept_id = employees.department_id AND departments.company_id = employees.company_id) as department_name,
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
                    (SELECT designations.name from designations WHERE designations.designation_id = employees.designation_id AND designations.company_id = employees.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.dept_id = employees.department_id AND departments.company_id = employees.company_id) as department_name,
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

        //$employees = Employee::select('employee_id','user_id','employee_name','email', 'mobile','company_id', 'joining_date', 'gross', 'employee_status')->get();

        return Datatables::of($employees)
                            ->addIndexColumn()
                            ->addColumn('employee_name', function($data){
                                return '<a href="'.route('admin.employee.show',$data->employee_id).'" class="d-flex align-items-center">
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
                                $button = '<button type="button" data-toggle="modal" data-target="#addWebModal" data-emp_name="'.$data->employee_name.'" data-emp_id="'.$data->employee_id.'" class="btn btn-primary btn-sm border-radius-0"><i class="fas fa-eye"></i> Edit</button>';
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
       
        $emp = Employee::where('employee_id', $request->emp_id)->first();
        $get_department_id = Department::where([['dept_id', (int)$emp->department_id],['company_id',$emp->company_id]])->select('id')->first();
        $designation_id = Designation::where([['designation_id', $emp->designation_id],['company_id',$emp->company_id]])->select('id')->first();
        
        if (!empty($emp->employee_id) )
        {           
            $data = array(
                'name' => $emp->employee_name,
                'email' => $emp->email,
                'emp_id' => $emp->employee_id,
                'company_id' => $emp->company_id,
                'designation_id' => $designation_id->id,
                'department_id' => $get_department_id->id,
                'password' => Hash::make($emp->employee_id),
                'role_id' => 4,
                'image' => $emp->employee_id.'.jpg',
            );
           

            User::updateOrInsert(
                ['emp_id' => $request->emp_id, 'email' => $emp->email], $data
            );

            return response()->json(['success'=>'Data is successfully added']);
        }

        return response()->json(['errors' => ['No record found']]);
        

    }


    public function getDepartmentId($department_id, $company_id){
        $get_department_id = Department::where([['dept_id', $department_id],['company_id', $company_id]])->pluck('id');
        return  $get_department_id;
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::leftJoin('users','users.emp_id','=','employees.employee_id')->where('employee_id', $id)->select('employees.*','users.id as user_id')->first();
        $modules = Module::leftJoin('permissions','permissions.id','=','modules.perm_id')->get();
        $leavehierarchies = '';
        if($employee->user_id){
            $leavehierarchies = LeaveHierarchy::where('user_id', $employee->user_id)->orderBy('hierarchie')->get();
        }
        return view('backend.admin.employee.show', compact('employee', 'modules','leavehierarchies'));
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

    public function permissionUpdate(Request $request)
    {

        if($request->user_id)
        {
            $validator = Validator::make($request->all(), [
                'module_id'     => 'required',
                'user_id'        => 'required',
                'read'          => 'required'
            ]);

            if ($validator->fails())
            {
                return Redirect::back()->withErrors($validator);
            }
            $data = [
                'user_id' => $request->user_id,
                'module_id' => $request->module_id,
                'create' => !empty($request->create) ? 1 : 0,
                'read' => !empty($request->read) ? 1 : 0,
                'update' => !empty($request->update) ? 1 : 0,
                'delete' => !empty($request->mdelete) ? 1 : 0,
                'cancel' => !empty($request->cancel) ? 1 : 0,
                'approval' => !empty($request->approval) ? 1 : 0,
            ];

            Permission::updateOrInsert(
                ['user_id' =>  $request->user_id, 'module_id' => $request->module_id], $data
            );
            return redirect()->back()->with('success', 'Data is successfully added');
        }else{
            return redirect()->back()->with('success', 'Please add user at first');
        }
    
    }


    public function hierarchyStore(Request $request)
    {
        //dd($request->all());

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
