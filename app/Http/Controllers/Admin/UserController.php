<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveHierarchy;
use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        return view('backend.admin.users.index');
    }

    public function jsonUserData(Request $request){
        
        if(!empty($request->department_id)){
    
            $query = "SELECT companies.slug as company_name, users.image, users.emp_id as employee_id, users.name as employee_name, users.email, users.phone as mobile, users.company_id, users.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = users.designation_id AND designations.company_id = users.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = users.department_id AND departments.company_id = users.company_id) as department_name,
                    employees.joining_date, employees.gross, users.status, users.id as user_id
                FROM users
                LEFT OUTER JOIN employees
                    ON users.id = employees.user_id
                LEFT OUTER JOIN companies
                    ON users.company_id = companies.comp_id
                WHERE users.status NOT IN ('E', 'RG', 'T', 'D', 'A', 'NO')
                AND users.department_id = ".$request->department_id." AND users.company_id = ".$request->company_id." 
                ORDER BY users.company_id ASC";
        }else{
            $query = "SELECT companies.slug as company_name, users.image, users.emp_id as employee_id, users.name as employee_name, users.email, users.phone as mobile, users.company_id, users.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = users.designation_id AND designations.company_id = users.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = users.department_id AND departments.company_id = users.company_id) as department_name,
                    employees.joining_date, employees.gross, users.status, users.id as user_id
                FROM users
                LEFT OUTER JOIN employees
                    ON users.id = employees.user_id
                LEFT OUTER JOIN companies
                    ON users.company_id = companies.comp_id
                WHERE users.status NOT IN ('E', 'RG', 'T', 'D', 'A', 'NO')
                ORDER BY users.company_id ASC";
        }
        

        $employees = DB::select($query);

        //$employees = Employee::select('employee_id','user_id','employee_name','email', 'mobile','company_id', 'joining_date', 'gross', 'employee_status')->get();

        return Datatables::of($employees)
                            ->addIndexColumn()
                            ->addColumn('employee_name', function($data){
                                return '<a href="'.route('admin.user.show',$data->employee_id).'" class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <img src="'.asset('uploads/img/').'/'.$data->image.'" class="rounded-circle wd-35" alt="user">
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
                                $button = '<a href="'.route('admin.user.show',$data->employee_id).'" class="btn btn-light align-items-center btn-sm btn-sm-custom">View</a>';

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
        
        $emp = Employee::where('employee_id', $request->emp_id)->where('company_id', $request->company_id)->first();

        // $get_department_id = Department::where([['dept_id', (int)$emp->department_id],['company_id',$emp->company_id]])->select('id')->first();
        // $designation_id = Designation::where([['designation_id', $emp->designation_id],['company_id',$emp->company_id]])->select('id')->first();
        
        if (!empty($emp->employee_id) )
        {           
            $data = array(
                'name' => $emp->employee_name,
                'email' => $emp->email,
                'emp_id' => $emp->employee_id,
                'company_id' => $emp->company_id,
                'designation_id' => $emp->designation_id,
                'department_id' => $emp->department_id,
                'phone' => $emp->mobile,
                'blood' => $emp->blood_group,
                'password' => Hash::make($emp->employee_id),
                'role_id' => 4
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
        $employee = User::where('emp_id', $id)->first();

        $modules = Module::where('parent_id',0)->get();

        // $modules = Module::leftJoin('permissions','permissions.id','=','modules.perm_id')
        //          ->get();
        

        return view('backend.admin.users.show', compact('employee', 'modules'));
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
    public function update(Request $request)
    {
        dd($request->all());
        
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
            'emp_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'required'],
            'phone' => ['required', 'max:20'],
            'designation' => ['required'],
            'department' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'designation_id' => $request->designation,
            'department_id' => $request->department,
            'phone' => $request->phone,
            'pabx' => $request->pabx,
            'birth_date' => $request->birthdate,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood' => $request->blood,
            'ip_address' => $request->ip_address,
        );

        User::where('id', $request->user_id)
            ->update($data);

        return redirect()->back()->with('success', 'Data is successfully added');

    }

    public function permissionUpdate(Request $request)
    {

        //dd($request->all());

        if($request->user_id)
        {
            $validator = Validator::make($request->all(), [
                'module_id'     => 'required',
                'user_id'        => 'required',
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

            //dd($data);
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
