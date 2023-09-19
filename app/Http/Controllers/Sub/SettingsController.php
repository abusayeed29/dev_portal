<?php

namespace App\Http\Controllers\Sub;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userInfo = User::where('users.id', Auth::user()->id)
                    ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
                    ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
                    ->leftJoin('companies', 'users.company_id', '=', 'companies.comp_id')
                    ->select('users.*', 'departments.name as department_name','designations.name as designation_name','companies.name as company_name')
                    ->first();
        return view('backend.sub.profile.index',['userInfo' => $userInfo]);
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
    public function store(Request $request){

        dd($request->all());

        // $validator =Validator::make($request->all(), [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'emp_id' => ['required','unique:users'],
        //     'department' => 'required',
        //     'designation' => 'required',
        //     'company' => 'required',
        // ]);

        // if ($validator->fails()){
        //     return Redirect::back()->withErrors($validator);
        // }
       
        // $data = array(
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'emp_id' => $request->emp_id,
        //     'company_id' => $request->company,
        //     'designation_id' => $request->designation,
        //     'department_id' => $request->department,
        //     'password' => Hash::make($request->emp_id.$request->company),
        //     'role_id' => 4,
        //     'image' =>'default.jpg',
        // );
    

        // User::updateOrInsert(
        //     ['emp_id' => $request->emp_id, 'email' => $request->email], $data
        // );

        // return redirect()->back()->with('success', 'Data is successfully added');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $validator =Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'max:20'],
            'designation' => ['required'],
            'department' => ['required'],
            'blood' => ['max:20'],
        ]);

        if ($validator->fails()){
            return Redirect::back()->withErrors($validator);
        }
       
            $data = array(
                'name' => $request->name,
                'designation_id' => $request->designation,
                'department_id' => $request->department,
                'phone' => $request->phone,
                'pabx' => $request->pabx,
                'birth_date' => $request->birthdate,
                'gender' => $request->gender,
                'reportto_id' => $request->report_to,
                'address' => $request->address,
                'blood' => $request->blood,
                'ip_address' => $request->ip_address,
            );
            
            User::where('id', Auth::user()->id)
                ->update($data);
                
            return redirect()->back()->with('success', 'Data is successfully added');


    }

    public function changePasswordGet(){
        return view('backend.sub.profile.change-password');
    }

    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            // Current password and new password same
            return redirect()->back()->with("error","New Password cannot be same as your current password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password successfully changed!");
    }

    public function phoneSave(Request $request){

        $validator =Validator::make($request->all(), [
            'phone' => ['required','min:11','max:20'],
        ]);

        if ($validator->fails()){
            return Redirect::back()->withErrors($validator);
        }

        $user = Auth::user();
        $user->phone =$request->phone;
        $user->save();

        return redirect()->back()->with("success","Your mobile number saved successfully!");

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
