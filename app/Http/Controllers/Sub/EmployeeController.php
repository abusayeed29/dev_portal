<?php

namespace App\Http\Controllers\Sub;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveHierarchy;
use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        return view('backend.sub.employee.index');
    }

    public function jsonUsers(Request $request)
    {
        //dd($request->all());

        if (!empty($request->company_id) && $request->department_id == NULL) {

            $query = "SELECT companies.slug as company_name, users.image, users.emp_id as employee_id, users.name as employee_name, users.email, users.phone as mobile, users.company_id, users.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = users.designation_id AND designations.company_id = users.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = users.department_id AND departments.company_id = users.company_id) as department_name,
                    employees.joining_date, employees.gross, users.status, users.id as user_id
                FROM users
                LEFT OUTER JOIN employees
                    ON users.id = employees.user_id
                LEFT OUTER JOIN companies
                    ON users.company_id = companies.comp_id
                WHERE users.status NOT IN ('E', 'D', 'A', 'NO')
                AND users.company_id =" . $request->company_id . " 
                ORDER BY users.company_id ASC";
                
        } elseif(!empty($request->company_id) && !empty($request->department_id)){

            $query = "SELECT companies.slug as company_name, users.image, users.emp_id as employee_id, users.name as employee_name, users.email, users.phone as mobile, users.company_id, users.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = users.designation_id AND designations.company_id = users.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = users.department_id AND departments.company_id = users.company_id) as department_name,
                    employees.joining_date, employees.gross, users.status, users.id as user_id
                FROM users
                LEFT OUTER JOIN employees
                    ON users.id = employees.user_id
                LEFT OUTER JOIN companies
                    ON users.company_id = companies.comp_id
                WHERE users.status NOT IN ('E', 'D', 'A', 'NO')
                AND users.company_id =" . $request->company_id . " 
                AND users.department_id = " . $request->department_id . "
                ORDER BY users.company_id ASC";

        }else {
            $query = "SELECT companies.slug as company_name, users.image, users.emp_id as employee_id, users.name as employee_name, users.email, users.phone as mobile, users.company_id, users.department_id, 
                    (SELECT designations.name from designations WHERE designations.id = users.designation_id AND designations.company_id = users.company_id) as designations_name,
                    (SELECT departments.name from departments WHERE departments.id = users.department_id AND departments.company_id = users.company_id) as department_name,
                    employees.joining_date, employees.gross, users.status, users.id as user_id
                FROM users
                LEFT OUTER JOIN employees
                    ON users.id = employees.user_id
                LEFT OUTER JOIN companies
                    ON users.company_id = companies.comp_id
                WHERE users.status NOT IN ('E', 'D', 'A', 'NO')
                ORDER BY users.company_id ASC";
        }

        $employees = DB::select($query);

        return Datatables::of($employees)
            ->addIndexColumn()
            ->addColumn('employee_name', function ($data) {
                return '<a href="' . route('sub.employee.show', $data->user_id) . '" class="d-flex align-items-center">
                                <div class="mr-3">
                                    <img src="' . asset('uploads/img/') . '/' . $data->image . '" class="rounded-circle wd-35" alt="user">
                                </div>
                                <div class="w-100 ms-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="text-body mb-2">' . $data->employee_name . '</h6>
                                    </div>
                                    <p class="text-muted tx-13">' . $data->designations_name . '</p>
                                </div>
                            </a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('sub.employee.show', $data->user_id) . '" class="btn btn-light align-items-center btn-sm btn-sm-custom">View</a>';
                /*$button .= '&nbsp;';*/
                return "<div class='btn-group btn-group-sm' role='group' aria-label='View'>" . $button . "</div>";
            })
            ->rawColumns(['employee_name', 'action'])
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

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'emp_id' => ['required', 'unique:users'],
            'department' => 'required',
            'designation' => 'required',
            'company' => 'required',
            'blood' => ['max:15'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'emp_id' => $request->emp_id,
            'company_id' => $request->company,
            'designation_id' => $request->designation,
            'department_id' => $request->department,
            'password' => Hash::make($request->emp_id . $request->company),
            'role_id' => 4,
            'image' => 'default.jpg',
        );


        User::updateOrInsert(
            ['emp_id' => $request->emp_id, 'email' => $request->email],
            $data
        );

        return redirect()->back()->with('success', 'Data is successfully added');
    }

    public function hierarchyStore(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'leave_level_id' => 'required',
            'department'     => 'max:200',
            'emp_id'         => 'required',
            'hierarchy'      => 'required',
            'user_id'        => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $hierarchy     = new LeaveHierarchy;
        $hierarchy->user_id             = $request->user_id;
        $hierarchy->approval_user_id    = $request->emp_id;
        $hierarchy->hierarchie          = $request->hierarchy;
        $hierarchy->leave_level_id      = $request->leave_level_id;
        $hierarchy->save();

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
        $employee = User::leftJoin('employees', 'users.id', '=', 'employees.user_id')
            ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
            ->leftJoin('companies', 'users.company_id', '=', 'companies.comp_id')
            ->where('users.id', $id)
            ->select('users.*', 'employees.blood_group', 'employees.marital_status', 'employees.sex', 'designations.name as designation_name', 'companies.name as company_name')
            ->first();

        return view('backend.sub.employee.show', compact('employee'));
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

        $empUpdate_perm = getEmpUpdatePermission(10, 1);

        if ($empUpdate_perm == true) :

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
        else :
            return back()->with('error', 'Access denied');
        endif;
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
